<?php
/**
 * User: SeaReef
 * Date: 2018/9/30 18:18
 *
 * 监控报警
 */
namespace app\commands;

use app\common\helpers\Sms;
use Yii;
use yii\console\Controller;
use yii\db\Query;

class AlarmController extends Controller
{
    const ORDER_NUM = 5;

    private $phone_list = [
        15652287989,
        15001352985,
        17610992185,
    ];

    /**
     * 异常订单报警
     */
    public function actionOrder()
    {
//        十分钟内异常订单5笔
        $start_time = date('Y-m-d H:i:s', time() - 300);
        $end_time = date('Y-m-d H:i:s', time());

        $sql = "SELECT f_remark, COUNT(*) AS `cnt` FROM lobby_daili.t_order WHERE f_created >= '{$start_time}' AND f_created < '{$end_time}' AND f_status = 0 GROUP BY f_remark";
        $db = Yii::$app->db;
        $data = $db->createCommand($sql)->queryAll();
        foreach ($data as $v) {
            if ($v['cnt'] > self::ORDER_NUM) {
                foreach ($this->phone_list as $num) {
                    $info = Sms::send($num, '支付渠道近5分钟内'. $v['f_remark'] . $v['cnt'] . '笔异常订单、请检测');
                    var_dump($info);
                }
            }
        }
    }

    /**
     * 元宝监控
     * 没一小时执行一次
     */
    public function actionGold($start_time = '')
    {
        $start_time = $start_time ? : date('Y-m-d H:00:00');
        $suffix = date('Ymd', time() - 3);

//        元宝淤积、必须第一个计算
        $yuji = (new Query())->from('login_db.t_lobby_player')->sum('gold_bar');

//        输入元宝汇总
        $order_gold = (new Query())->from('t_order')->where(['and', 'status = 1', "create_time < '{$start_time}'"])->sum('goods_num');

//        vip充值的元宝
        $vip_gold = (new Query())->from('t_vip_recharge_log')->where(['and', 'status = 1', "create_time < '{$start_time}'"])->sum('out_amount');

//        系统增发
        $system_gold = (new Query())->from('t_service_recharge_log')->where(['and', 'status = 1', "time < '{$start_time}'"])->sum('gold_num');

//        红包赠送
        $honbao = (new Query())->from('t_hongbao')->where("create_time < '{$start_time}'")->sum('gold');

//        活动赠送
        $activity = (new Query())->from('log_user_activity')->where(['and', 'activity_id = 2', 'operate_type = 1', 'is_operate = 1'])->count() ? : 0;
        $activity_gold = $activity * 500;

        $gold_record = 't_gold_record__' . $suffix;
        $history_consume = (new Query())->select('consume')->from('log_alarm_gold')->orderBy('id DESC')->limit(1)->scalar() ? : 0;
        $consume = (new Query())->from($gold_record)->sum('num');
        $consume = $consume + $history_consume;

        $tixian = (new Query())->from('t_pay_order')->where(['and', 'PAY_STATUS = 1', "CREATE_TIME < '{$start_time}'"])->sum('PAY_MONEY');

        $db = Yii::$app->db;
        $db->createCommand()->insert('log_alarm_gold', [
            'order_gold' => $order_gold ? : 0,
            'vip_gold' => $vip_gold ? : 0,
            'system_gold' => $system_gold ? : 0,
            'hongbao' => $honbao ? : 0,
            'activity' => $activity_gold ? : 0,
            'consume' => $consume ? : 0,
            'tixian' => $tixian ? : 0,
            'yuji' => $yuji ? : 0,
        ])->execute();
    }
}