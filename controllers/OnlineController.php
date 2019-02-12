<?php
/**
 * User: SeaReef
 * Date: 2018/9/4 20:52
 *
 * 在线分析
 */
namespace app\controllers;

use Yii;
use yii\db\Query;
use app\models\Index;

class OnlineController extends CommonBaseController
{
    /**
     * 在线分析
     */
    public function actionRealOnline()
    {
        return $this->render('real_online');
    }

    /**
     * 玩家七天在线
     */
    public function actionOnlineSeven()
    {
        $time = Yii::$app->request->get('time');
        if ($time) {//有查询时间
            $today = strtotime($time);//查询当天的
        } else {
            $today = strtotime('today');
        }
        $yesterday = $today-86400;//查询时间当天前一天的
        $yesterday_1 = $today-(86400*6);//查询时间七天前的
        $model = new Index();
        $player[] = $model->convertOnlinePlayer($today,$today+86400);
        $player[] = $model->convertOnlinePlayer($yesterday,$today);
        $player[] = $model->convertOnlinePlayer($yesterday_1,$yesterday_1+86400);


        $new_data = [];
        foreach ($player as $key => $val) {
            $new_data[$key]['time'] = array_column($val,'time',10000);
            $new_data[$key]['num'] = array_column($val,'num');
        }
        return $this->writeResult(self::CODE_OK,'',$new_data);

    }

    /**
     * 每日在线用户
     */
    public function actionOnlineDay()
    {
        $start_time = Yii::$app->request->get('start_time');
        $end_time = Yii::$app->request->get('end_time');
        if ($start_time) {
            $where[] = ' unix_timestamp(stat_date) >= '.strtotime($start_time);
        }
        if ($end_time) {
            $where[] = ' unix_timestamp(stat_date) <= '.strtotime($end_time);
        }
        if (!$start_time && !$end_time) {
            $where[] = ' unix_timestamp(stat_date) <= '.strtotime('today');
            $where[] = ' unix_timestamp(stat_date) >= '.(strtotime('today')-86400*6);
        }

        $where = implode(' and ',$where);
        $rows = (new Query())
            ->select('*')
            ->from('stat_online')
            ->where($where)
            ->orderBy('stat_date desc')
            ->all();
        $this->writeResult(0,'',$rows?$rows:[]);
    }

    /**
     * 实时玩家数量
     */
    public function actionPlayerNums()
    {
        ini_set('max_execution_time', '5');
        $redis1 = Yii::$app->gate_redis1;
        $num1 = $redis1->hlen('hame_socket_hash1');

        $redis2 = Yii::$app->gate_redis2;
        $num2 = $redis2->hlen('hame_socket_hash1');
        /*if ($num1 && $num2) {

        }
        $num = 0;*/
        $this->writeResult(self::CODE_OK,'',$num1+$num2);

    }
}