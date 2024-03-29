<?php
/**
 * User: SeaReef
 * Date: 2018/12/3 16:18
 */
namespace app\commands;

use Yii;
use yii\db\Query;

class RechargeStatController extends BaseController
{
    /**
     * 充值统计
     */
    public function actionBaseInfo($start_time = '', $end_time = '', $channel_id=1)
    {
        $start_time = $start_time ? : date('Y-m-d 00:00:00', time() - 86400);
        $end_time = $end_time ? : date('Y-m-d 23:59:59', time() - 86400);
        $stat_date = date('Y-m-d', strtotime($start_time));
        $table = 'stat_base_recharge';

        $under_list = $this->getChannelUnderList($channel_id);

//        付费人数、付费次数
        $pay1 = (new Query())
            ->select('COUNT(id) AS `pay_count`, COUNT(DISTINCT player_id) AS `pay_user`, SUM(goods_price) AS `amt`')
            ->from('t_order')
            ->where(['and', 'status = 1', "create_time >= '{$start_time}'", "create_time < '{$end_time}'"])
            ->andFilterWhere(['in', 'player_id', $under_list])
            ->one();

//        新增付费人数、付费次数
        $pay2 = (new Query())
            ->select('COUNT(id) AS `new_pay_count`, COUNT(DISTINCT player_id) AS `new_pay_user`')
            ->from('t_order')
            ->where(['and', 'status = 1', "create_time >= '{$start_time}'", "create_time < '{$end_time}'", "player_create >= '{$start_time}'", "player_create < '{$end_time}'"])
            ->andFilterWhere(['in', 'player_id', $under_list])
            ->one();

//        今日充值
        $day_recharge = (new Query())->from('t_order')->where(['and', 'status = 1', "create_time >= '{$start_time}'", "create_time < '{$end_time}'"])->andFilterWhere(['in', 'player_id', $under_list])->sum('goods_price');

//        历史所有充值、截止统计日期前
        $all_recharge = (new Query())->from('t_order')->where(['and', 'status = 1', "create_time < '{$end_time}'"])->andFilterWhere(['in', 'player_id', $under_list])->sum('goods_price');

//        vip充值
        $vip_recharge = (new Query())->from('t_vip_recharge_log')->where(['and', 'status = 1', "create_time >= '{$start_time}'", "create_time < '{$end_time}'"])->andFilterWhere(['in', 'player_id', $under_list])->sum('amount');

//        系统增发
        $system_recharge = (new Query())->from('t_service_recharge_log')->where(['and', 'status = 1', "time >= '{$start_time}'", "time < '{$end_time}'"])->andFilterWhere(['in', 'player_id', $under_list])->sum('gold_num');
        $system_recharge = $system_recharge / 100 ? : 0;

        $db = Yii::$app->db;
        $db->createCommand()->insert($table, [
            'channel_id' => $channel_id,
            'stat_date' => $stat_date,
            'pay_user' => $pay1['pay_user'] ? : 0,
            'pay_count' => $pay1['pay_count'] ? :0,
            'new_pay_user' => $pay2['new_pay_user'] ? : 0,
            'new_pay_count' => $pay2['new_pay_count'] ? : 0,
            'amt' => $day_recharge ? : 0,
            'all_amt' => $all_recharge ? : 0,
            'add_amt' => $system_recharge ? : 0,
            'vip' => $vip_recharge ? : 0,
        ])->execute();
    }

    public function actionPollChannel()
    {
        parent::actionPollChannel(); // TODO: Change the autogenerated stub
    }
}
