<?php
/**
 * User: SeaReef
 * Date: 2018/7/28 17:13
 *
 * 支付回调发货
 */

namespace app\controllers\api;

use app\common\Code;
use app\controllers\common\beepay\beePay;
use app\models\ConfPayChannel;
use app\models\ExchangeRecord;
use PhpOffice\PhpSpreadsheet\Reader\Xls\MD5;
use Yii;
use yii\base\Curl;

class NotifyController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * 应用id
     */
    private $appid;

    /**
     * cp订单号
     */
    private $cp_oid;

    /**
     * 订单金额
     */
    private $money;

    /**
     * 支付结果
     */
    private $status;

    /**
     * 渠道订单号
     */
    private $channel_oid;

    /**
     * 签名
     */
    private $sign;

    /**
     * 实际金额
     */
    private $real_money;


    /**
     * 竣付通支付回调
     * 1、添加毫秒级订单锁机制
     */
    public function actionJpay()
    {
        $lock_key = '__ORDER_PAY_)' . $_REQUEST['p2_ordernumber'];
        $redis = Yii::$app->redis;

        if ($redis->get($lock_key)) {
            echo 'error';
        } else {
            $redis->set($lock_key, 1);
            $redis->expire($lock_key, 5);
        }

        $games = Yii::$app->params['mj'];
        $gid = Yii::$app->params['gid'] = 1114112;

//        验证签名
        $compkey = "040109171758RwDlhSSk";

        $p1_yingyongnum = $_REQUEST['p1_yingyongnum'];
        $p2_ordernumber = $_REQUEST['p2_ordernumber'];
        $p3_money = $_REQUEST['p3_money'];
        $p4_zfstate = $_REQUEST['p4_zfstate'];
        $p5_orderid = $_REQUEST['p5_orderid'];
        $p6_productcode = $_REQUEST['p6_productcode'];
        $p7_bank_card_code = empty($_REQUEST['p7_bank_card_code']) ? '' : $_REQUEST['p7_bank_card_code'];
        $p8_charset = $_REQUEST['p8_charset'];
        $p9_signtype = $_REQUEST['p9_signtype'];
        $p10_sign = $_REQUEST['p10_sign'];
        $p11_pdesc = empty($_REQUEST['p11_pdesc']) ? '' : $_REQUEST['p11_desc'];
        $presign = $p1_yingyongnum . "&" . $p2_ordernumber . "&" . $p3_money . "&" . $p4_zfstate . "&" . $p5_orderid . "&" . $p6_productcode . "&" . $p7_bank_card_code . "&" . $p8_charset . "&" . $p9_signtype . "&" . $p11_pdesc . "&" . $compkey;
        // echo $presign."<br/>";
        $sign = strtoupper(md5($presign));
        if ($sign == $_REQUEST['p10_sign'] && $_REQUEST['p4_zfstate'] == "1") {
            $order = Order::findOne(['f_order_id' => $p2_ordernumber]);
            if (!$order) {
                $redis->pexpire($lock_key, 1);
                $msg = "order not found in table `t_order`";
                Yii::info($msg, 'jtpay');
                echo 1;
                die();
                false;
            }
            // 验证订单状态
            if ($order->f_status == 1) {
                $redis->pexpire($lock_key, 1);
                $msg = "already processed, current order status is `1`";
                Yii::info($msg, 'jtpay');
                echo 2;
                die();
                true;
            }

            // 验证订单金额
            if ($p3_money != $order->f_price) {
                $redis->pexpire($lock_key, 1);
                $msg = "fee check fail, expect {$order->f_price} but {$arr->money} returned";
                Yii::info($msg, 'iappPay');
                return false;
            }

            Api::RechargeFlowingToAgentSystem($order->f_uid, $p5_orderid, $order->f_price, '');

            // 开始发货
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $order->f_charge_paramdown = json_encode($_REQUEST);
                $order->f_status = 1;
                $order->save();

                if (isset($games[$gid]['recharge']['apiUrlOld'])) {

                    $total = $order->f_num + $order->f_award;
                    $apiParams = [
                        'gid' => $gid,
                        'order_id' => $p2_ordernumber,
                        'player_id' => $order->f_uid,
                        'recharge_diamond' => $order->f_num,
                        'recharge_money' => $order->f_price,
                        'remark' => 'iappPay',
                        'send_diamond' => $order->f_award,
                        'recharge_type' => $order->f_type,
                        'operation_type' => $order->f_pay_channel,
                    ];
                    file_put_contents('/tmp/notify.log', date('Y-m-d H:i:s', time()) . print_r([$games[$gid]['recharge']['apiUrlOld'], $apiParams], 1), FILE_APPEND);
//var_dump(Api::PlayerRechargeOld($games[$gid]['recharge']['apiUrlOld'], $apiParams, "iappPay"));
//die();
                    if (!Api::PlayerRechargeOld($games[$gid]['recharge']['apiUrlOld'], $apiParams, "iappPay")) {
                        $msg = "recharge api error";

                        Yii::info($msg, 'iappPay');
                    }
                }
                $transaction->commit();
                $redis->pexpire($lock_key, 1);
            } catch (Exception $e) {
                $redis->pexpire($lock_key, 1);
                file_put_contents("/home/eryanWebUser/UpdateFile/log.txt", $e->getMessage() . "/r/n", FILE_APPEND);
                $msg = "transaction rollback: {$e->getMessage()}";
                Yii::info($msg, 'iappPay');
                if (defined(YII_ENV_TEST) && YII_ENV_TEST) {
                    Yii::error($msg);
                } else {
                    Yii::info($msg, 'iappPay');
                }
                $transaction->rollBack();
                $db->close();
                return false;
            }
            echo 'success';
        } else {
            $redis->pexpire($lock_key, 1);
            echo 'error';
            die();
        }
    }

    /**
     * beelcloud回调发货
     */
    public function actionBeepay()
    {
        $jsonStr = file_get_contents("php://input");
        $info = json_decode($jsonStr, true);
        $beepay = new beePay();
        $check = $beepay->checkSign($info);

        if (!$check) { // 支付验证不通过
            echo 'fail';
            exit;
        }
        if ($info['transaction_type'] != "PAY") { //非支付订单
            echo 'fail';
            exit;
        }
        if ($info['trade_success']) {
            //进行发货设置
            $pay = $beepay->NotifyProcess($info);
            if ($pay) {
                echo 'success';
                exit;
            } else {
                echo 'fail';
                exit;
            }
        } else {
            //此次消息表示支付失败
            echo 'fail';
            exit;
        }
    }


    /**
     * 初始化A渠道参数
     */
    private function initParamsA()
    {
        $request = Yii::$app->request;
        $this->appid = $request->post('p1_yingyongnum');
        $this->cp_oid = $request->post('p1_yingyongnum');
        $this->money = $request->post('p1_yingyongnum');
        $this->status = $request->post('p1_yingyongnum');
        $this->channel_oid = $request->post('p1_yingyongnum');
        $this->sign = $request->post('p1_yingyongnum');
        $this->real_money = $request->post('p1_yingyongnum');
    }


    /**
     * 支付渠道A
     */
    public function actionA()
    {
//        初始化该渠道参数
        $this->initParamsA();

//        获取渠道信息
        $channel_info = ConfPayChannel::getInfoById($this->appid);
    }

    /**
     * 直兑回调
     *
     */
    public function actionExchangeNotify()
    {
        file_put_contents('/tmp/notivy.log', print_r([$_POST, $_GET, $_REQUEST], 1), FILE_APPEND);
        Yii::info("汇付宝回调开始");
        $request = $_REQUEST;

        $config = Yii::$app->params['exchange_config']['bank_hfb'];
        $ret_msg = iconv('GB2312', 'UTF-8', $request['ret_msg']);
        $detail_data = iconv('GB2312', 'UTF-8', $request['detail_data']);

        file_put_contents('/tmp/notivy.log', print_r([$ret_msg, $detail_data], 1), FILE_APPEND);

        $signStr = 'ret_code=' . $request['ret_code'] . '&ret_msg=' . $ret_msg . '&agent_id=' . $request['agent_id'] . '&hy_bill_no=' . $request['hy_bill_no'] . '&status=' . $request['status'] . '&batch_no=' . $request['batch_no'] . '&batch_amt=' . $request['batch_amt'] . '&batch_num=' . $request['batch_num'] . '&detail_data=' . $detail_data . '&ext_param1=' . $request['ext_param1'] . '&key=' . $config['md5_key'];
        Yii::info('签名前字符串：' . strtolower($signStr));

        $sign = md5(strtolower($signStr));

        Yii::info("汇付宝回调验签");

        if ($sign != $request['sign']) {
            Yii::info("汇付宝回调验签失败:" . "汇付宝签名：" . $request['sign'] . "自己的sign:" . $sign);
            $this->updatePayOrderStatus($request['batch_no'], Code::CODE_ORDER_SIGN_ERROR, '汇付宝回调验签失败');
            return 'error';
        }

        $exchangeRecordModel = new ExchangeRecord();
        $recordInfo = $exchangeRecordModel->getRecord(['order_id' => $request['batch_no'], 'type' => 2], '*', 2);

        if (!$recordInfo) {
            Yii::info("汇付宝银行卡直兑回调订单不存在");
            return 'error';
        }

        if ($recordInfo['status'] != 2) {
            Yii::info("订单已经完成");
            return 'error';
        }

        if($request['status'] != 1){
            Yii::info("汇付宝直兑失败,回滚用户金币");
            $this->updatePayOrderStatus($request['batch_no'], Code::RECHECK_PAY_ERROR_BACK, Code::RECHECK_PAY_ERROR_BACK_MESSAGE);
            $this->disposeGold($recordInfo['player_id'],$recordInfo['amount'],1);
            return 'ok';
        }

        $detailData = explode('^',$request['detail_data']);
        if($detailData[4] != 'S'){
            Yii::info("汇付宝直兑失败,回滚用户金币,核对信息的有效性");
            $exchangeRecordModel->updateRecordInfo($request['batch_no'], ['channel_id' => $request['hy_bill_no']]);
            $this->updatePayOrderStatus($request['batch_no'], Code::EXCHANGE_ERROR, Code::THIRD_RETURN_ERROR);
            $this->disposeGold($recordInfo['player_id'],$recordInfo['amount'],1);
            return 'ok';
        }

        $amount = ($recordInfo['amount'] - $recordInfo['service_charge']) / 100;
        if ($request['batch_amt'] != $amount) {
            Yii::info("汇付宝银行卡直兑回调订单金额错误！");
            return 'error';
        }

//        $checkOrder = $this->checkExchangeOrder($request['batch_no']);
//        if($checkOrder['ret_code'] == '0000' && $checkOrder['hy_bill_no']){
//            Yii::info("订单不存在：".$request['batch_no']);
//            return 'error';
//        }

        $exchangeRecordModel->updateRecordInfo($request['batch_no'], ['channel_id' => $request['hy_bill_no']]);
        $this->updatePayOrderStatus($request['batch_no'], Code::SUCCESS_PAY_STATUS, '直兑成功');
        Yii::info("汇付宝直兑成功");

        return 'ok';
    }

    /**
     * 直兑订单查询
     * sign规则
     */
    public function checkExchangeOrder($batchNo)
    {
        Yii::info("汇付宝订单查询开始");
        $checkConfig = Yii::$app->params['exchange_config']['bank_hfb'];
        $checkUrl = $checkConfig['check_order_url'];

        $param = [];
        $param['version'] = 3;
        $param['batch_no'] = $batchNo;
        $param['agent_id'] = $checkConfig['agent_id'];

        $sign = md5(strtolower('agent_id=' . $param['agent_id'] . '&batch_no=' . $param['batch_no'] . '&key=' . $checkConfig['md5_key'] . '&version=' . $param['version']));
        $requestParamStr = "agent_id=" . $param['agent_id'] . "&batch_no=" . $param['batch_no'] . '&version=' . $param['version'] . "&sign=" . $sign;

        $requestUrl = $checkUrl . '?' . $requestParamStr;
        $curl = new Curl();
        $res = $curl->get($requestUrl);
        $data =  (array) simplexml_load_string(utf8_encode($res));
        if (!$data) {
            Yii::info("汇付宝订单查询失败：" . $res);
            return false;
        }

        Yii::info("汇付宝订单查询成功返回");
        return $data;
    }
}
