<?php
/**
 * User: SeaReef
 * Date: 2018/9/5 11:04
 *
 * 充值配置
 */
namespace app\controllers;

use app\common\Code;
use Yii;
use yii\db\Query;

class RechargeController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    public $layout = 'layui';

    /**
     * 充值白名单
     */
    public function actionWhiteList()
    {
        $start_date = date('Y-m-d', time() - 86400 * 30);
        $end_date = date('Y-m-d', time() + 86400);

        return $this->render('white_list', [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    /**
     * 充值白名单接口
     */
    public function actionWhiteListApi()
    {
        $request = Yii::$app->request;
        $start_date = $request->get('start_date') ? : date('Y-m-d', time() - 86400 * 30);
        $end_date = $request->get('end_date') ? : date('Y-m-d', time() + 86400);
        $limit = $request->get('limit') ? : 10;
        $page = $request->get('page', 1);
        $field = $request->get('field', 'create_time');
        $order = $request->get('order', 'desc');

        $data = (new Query())
            ->select('*')
            ->from('conf_recharge_white_list')
            ->where(['and', "create_time >= '{$start_date}'", "create_time < '{$end_date}'"])
            ->orderBy("$field $order")
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();
        $count = (new Query())
            ->from('conf_recharge_white_list')
            ->where(['and', "create_time >= '{$start_date}'", "create_time < '{$end_date}'"])
            ->count();

        $this->writeLayui(Code::OK, '', $count, $data);
    }

    /**
     * 充值黑名单
     */
    public function actionBlackList()
    {
        return $this->render('black_list');
    }

    /**
     * 充值方式配置
     */
    public function actionPayment()
    {
        if (Yii::$app->request->isPost) {
            $data = (new Query())->select('*')->from('conf_payment')->all();
            $count = (new Query())->from('conf_payment')->count();

            foreach ($data as $k => $v) {
                $data[$k]['pay_channel'] = (new Query())->select('pay_channel')->from('conf_payment_channel')->where(['and', "payment = {$v['id']}", "master = 1"])->scalar();
            }

            $this->writeLayui(Code::OK, '', $count, $data);
        } else {
            $payChannel = (new Query())->select('id,channel_code')->from('conf_pay_channel')->where(['status'=>1])->all();
            foreach($payChannel as $key=>$val){
                $payChannel[$key]['channel_name'] = $this->stat_trans($val['channel_code']);
            }

            return $this->render('payment',['payChannel'=>json_encode($payChannel)]);
        }
    }

    /**
     * 监控switch按钮
     */
    public function actionUpdateSwitch()
    {
//        file_put_contents('e:/1.log', print_r($_POST, 1), FILE_APPEND);
        $request = Yii::$app->request;

        if ($request->isPost) {
            $id = $request->post('id');
            $field = $request->post('field');
            $value = $request->post('value');

            $db = Yii::$app->db;
            $sql = "UPDATE conf_payment SET `{$field}` = {$value} WHERE id = {$id}";
            $res = $db->createCommand($sql)->execute();

            $this->writeAjaxResult(['res' => 1]);
        }
    }

    /**
     * 更新渠道
     */
    public function actionUpdateRadio()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $field = $request->post('field');
            $value = $request->post('value');

            $db = Yii::$app->db;
            $t = date('Y-m-d H:i:s');
            $sql = "UPDATE conf_payment_channel SET master = 0, weight = 0 WHERE payment = '{$value}'";
//            file_put_contents('e:/1.log', print_r($sql, 1), FILE_APPEND);


            $res = $db->createCommand($sql)->execute();

            $sql = "INSERT INTO conf_payment_channel VALUES (NULL, '{$value}', '{$field}', now(), 1, 10) ON DUPLICATE KEY UPDATE create_time = '{$t}', master = 1, weight = 10";

//            file_put_contents('e:/1.log', print_r($sql, 1), FILE_APPEND);


            $res = $db->createCommand($sql)->execute();

            $this->writeAjaxResult(['res' => $res]);
        }
    }

    /**
     * 充值方式日志
     */
    public function actionOpLog()
    {
        $request = Yii::$app->request;
        $data = (new Query())
            ->from('log_operation')
            ->all();
        $count = count($data);

        $this->writeLayui(Code::OK, '', $count, $data);
    }

    /**
     * 充值渠道配置表
     */
    public function actionPaychannel()
    {
        if (Yii::$app->request->isPost) {
            $data = (new Query())->select('*')->from('conf_pay_channel')->all();
            $count = (new Query())->from('conf_pay_channel')->count();

            $this->writeLayui(Code::OK, '', $count, $data);
        } else {
            return $this->render('paychannel');
        }
    }

    /**
     * 充值方式与渠道
     */
    public function actionPaymentChannel()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $data = (new Query())->select('*')->from('conf_payment_channel')->all();
            foreach ($data as $k => $v) {
//                var_dump($k, $v);
                $d['payment'] = (new Query())->select('remark')->from('conf_payment')->where(['id' => $v['payment']])->scalar();
                $d['pay_channel'] = (new Query())->select('channel_code')->from('conf_pay_channel')->where(['id' => $v['pay_channel']])->scalar();
                $d['create_time'] = $v['create_time'];
                $d['master'] = $v['master'];
                $d['weight'] = $v['weight'];
            }

            $count = (new Query())->select('*')->from('conf_payment_channel')->count();

            $this->writeLayui(Code::OK, '', $count, $data);
        } else {
            return $this->render('payment_channel');
        }
    }

    /**
     * 充值统计
     */
    public function actionStat()
    {
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');

        return $this->render('stat', [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    /**
     * 充值统计接口
     */
    public function actionStatApi()
    {
        $request = Yii::$app->request;
        $start_date = $request->get('start_date') ? $request->get('start_date') . ' 00:00:00' : date('Y-m-d 00:00:00', time());
        $end_date = $request->get('end_date') ? $request->get('end_date') . ' 23:59:59' : date('Y-m-d 23:59:59', time());


        $data = (new Query())
            ->select(['pay_channel', 'pay_type', 'SUM(goods_price) AS amt'])
            ->from('t_order')
            ->where(['and', 'status = 1', "create_time >= '{$start_date}'", "create_time <= '{$end_date}'"])
            ->groupBy('pay_channel, pay_type')
            ->all();
        $count = count($data) + 1;

        foreach ($data as $k => $v) {
            $pay_channel = (new Query())->from('conf_pay_channel')->where(['channel_code' => $v['pay_channel']])->one();
            $data[$k]['start_time'] = $start_date;
            $data[$k]['end_time'] = $end_date;
            $data[$k]['address'] = $pay_channel['reserve3'];
            $data[$k]['rate'] = $pay_channel['reserve4'];
            $data[$k]['pay_channel'] = $this->stat_trans($v['pay_channel']) . '-商户号：' . $pay_channel['appid'];
        }

//        添加vip充值
        $data[] = [
            'start_time' => $start_date,
            'end_time' => $end_date,
            'pay_type' => 'VIP充值',
            'address' => '',
            'rate' => '0',
            'amt' => (new Query())->from('t_vip_recharge_log')->where(['and', "create_time >='{$start_date}'", "create_time <= '{$end_date}'", 'status = 1'])->sum('amount') ? : 0,
        ];

        $this->writeLayui(Code::OK, '', $count, $data);
    }

    /**
     * 支付渠道转化
     */
    private function stat_trans($channel_code)
    {
        $arr = [
            'heepay_1' => '汇付宝、海南超然、只有网银',
            'heepay_2' => '汇付宝、海南超然、全有、银联可用',
            'heepay_3' => '汇付宝、上海一拳、全有、银联支付宝可用',
            'heepay_4' => '汇付宝、海南点动',
            'heepay_5' => '汇付宝、广州志勇',
            'heepay_6' => '汇付宝、上海一拳',
            'Jpay' => '竣付通、01018127907801',
            'jpay_2' => '竣付通、01018057625001',
            'wechatwap1' => '微信H5、1519439471',
            'Guangda' => '光大',
            'wechatwap2' => '微信H5、1518866601',
            'wechatwap3' => '微信H5、1515631881',
            'ecpss_1' => '汇潮、广州志勇、微信可用',
            'ecpss_2' => '汇潮、上海一拳、微信可用',
            'ecpss_3' => '汇潮、广州威杰、微信可用',
            'ecpss_4' => '汇潮、广州诗迪、微信可用',
        ];

        return $arr[$channel_code];
    }

    /**
     * 下拉框测试1
     */
    public function actionT1()
    {
        return $this->render('t1');
    }
}