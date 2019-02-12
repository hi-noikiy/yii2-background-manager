<?php
/**
 * User: SeaReef
 * Date: 2018/7/23 10:57
 *
 * 支付中心接口
 * 整体充值流程：
 * 拉起支付-》中心下单、调起支付链接-》easypay检测支付结果返回=》中心接收支付结果、更新订单、发货通知=》平台发货操作发货=》
 */
namespace app\controllers\api;

use app\controllers\CommonController;
use Yii;
use yii\base\Curl;

class PayController extends CommonController
{
    public $enableCsrfValidation = false;

    const PLACE_COUNT = 4;

    const PAYMENT_CONFIG = 'center_payment_conf';

    const PAYMENT_TMP_ORDER_NUM = 'center_payment_tmp_order_num';

    /**
     * 中心下单、添加一个订单时间检测、同样金额使用同一个账号转账的情况、等固定时间才允许进行支付
     */
    public function actionPlaceOrder()
    {
        $request = Yii::$app->request;
        if (count($request->post()) < self::PLACE_COUNT) {
            $this->writeJson(2, self::CODE_PARAM_NOT_ENOUGH);
        }

        $merchant_id = $request->post('merchant_id');
        $merchant_order = $request->post('merchant_order');
        $money = $request->post('money');
        $product_code = $request->post('product_code');

        $sign = $request->post('sign');
        $trans = $request->post('trans');
        if (empty($merchant_id) || empty($merchant_order) || empty($money) || empty($product_code) || empty($sign) || empty($trans)) {
            $this->writeJson(2, self::CODE_PARAM_ERROR);
        }

        $check_sign = md5($merchant_id . $merchant_order . $money . $product_code);
        if ($check_sign != $sign) {
            $this->writeJson(2, self::CODE_SIGN_ERROR);
        }

//        支付中心下单
        $db = Yii::$app->db;
        $center_order = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $time = time();
        $ip = $_SERVER['REMOTE_ADDR'];
        $sql = "INSERT INTO `center_order` VALUES(NULL, '{$center_order}', '{$merchant_order}', '0', '{$money}', '{$time}', '{$product_code}', '{$sign}', 0, '{$ip}', 1, '', '')";
        $info = $db->createCommand($sql)->execute();

//        轮询账号、拉起支付链接
        $redis = Yii::$app->redis_3;

//        使用redis存储充值配置信息
        $mod = $redis->hlen(self::PAYMENT_CONFIG);
        $num = $redis->get(self::PAYMENT_TMP_ORDER_NUM);
        $num = $num % $mod;
        $redis->incr(self::PAYMENT_TMP_ORDER_NUM);

        $recharge_info = $redis->hget(self::PAYMENT_CONFIG, $num);
        $info = json_decode($recharge_info, 1);
        $money = intval($money);
        $url = $info[$money];

        $this->redirect($url);
    }

    /**
     * 接收easypay监听结果
     * 通知平台发货
     */
    public function actionPayMonitor($type)
    {
//        添加监听回调
        file_put_contents('d:/1456.log', date('Y-m-d H:i:s', time()) . print_r($_REQUEST, 1), FILE_APPEND);

        $request = Yii::$app->request;
        $type = $request->get('type');
        $account_id = $request->get('account_id');
        $channel_order = $request->post('tradeNo');
        $time = $request->post('time');
        $amount = $request->post('amount');
        $end_time = strtotime($time);
//        三十秒内没有监听返回处理为异常订单
        $start_time = $end_time - 30;

//        先不进行签名验证
        $db = Yii::$app->center_db;
        $data = $db->createCommand($sql = "SELECT * FROM `center_order` WHERE account_id = '{$account_id}' AND money = '{$amount}' AND order_time >= '{$start_time}' AND order_time < '{$end_time}' AND status = 0")->execute();

//        更新支付中心订单信息
        $db->createCommand("UPDATE `center_order` SET channel_order = '{$channel_order}', status = 1, finish_time = '{$time}' WHERE id = '{$data['id']}'")->execute();

        $curl = new Curl();
        $res = $curl->setPostParams([
            'order_id' => $data['merchant_order'],
            'money' => $amount,
            'p10_sign' => md5($data['merchant_order']),
            'p1_state' => 1
        ])
            ->post('https://recharge-pk-t.dropgame.cn/notify/xiaofang-pay');
        var_dump($res);
    }

    public function actionT1()
    {
        /**
         * 金额
         * 10、50、100、300、500、1000
         */
        $account_info = [
                'account' => 'imissyoulang@126.com',
                10 => [
                    'pull_url' => 'HTTPS://QR.ALIPAY.COM/FKX09000XTHWRC0VAEKU1A'
                ],
                50 => [
                    'pull_url' => 'HTTPS://QR.ALIPAY.COM/FKX08687FFSUNCUZHHNV1D'
                ],
                100 => [
                    'pull_url' => 'HTTPS://QR.ALIPAY.COM/FKX042183QHWSRHOUD6PC7'
                ],
                300 => [
                    'pull_url' => 'HTTPS://QR.ALIPAY.COM/FKX09874YDZXBZMPQIZD97'
                ],
                500 => [
                    'pull_url' => 'HTTPS://QR.ALIPAY.COM/FKX097636MRBFVYPIHYR58'
                ],
                1000 => [
                    'pull_url' => 'HTTPS://QR.ALIPAY.COM/FKX040748CZLGQ1XXJYD01'
                ],
        ];

        echo json_encode($account_info);
    }
}