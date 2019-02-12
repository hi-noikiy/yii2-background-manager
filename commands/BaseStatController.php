<?php
/**
 * User: SeaReef
 * Date: 2018/10/8 16:41
 *
 * 基础指标数据统计
 * 使用原生SQL语句进行统计
 */
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\db\Query;

class BaseStatController extends Controller
{
    public $db;

    /**
     * 初始化操作
     */
    public function init()
    {
//        设置最大执行时间、最大执行内存
        ini_set("max_execution_time", "600");
        ini_set('memory_limit', '128M');

        $this->db = Yii::$app->db;
    }

    /**
     * 用户相关
     */
    public function actionPlayer($start_time = '', $end_time = '')
    {
        $start_time = $start_time ? : date('Y-m-d 00:00:00', time() - 86400);
        $end_time = $end_time ? : date('Y-m-d 00:00:00', time());
        $s_start_time = strtotime($start_time);
        $s_end_time = strtotime($end_time);
        $stat_date = date('Y-m-d', strtotime($start_time));

        $data['regist'] = $this->db->createCommand($sql1 = "SELECT COUNT('*') FROM `t_player_member` WHERE BIND_TIME >= '{$start_time}' AND BIND_TIME < '{$end_time}'")->queryScalar();
        $data['dnu'] = $this->db->createCommand($sql2 = "SELECT COUNT(*) FROM login_db.t_lobby_player WHERE reg_time >= '{$start_time}' AND reg_time < '{$end_time}'")->queryScalar();
        $data['all_user'] = $this->db->createCommand($sql3 = "SELECT COUNT(*) FROM login_db.t_lobby_player")->queryScalar();
        $data['dau'] = $this->db->createCommand($sql4 = "SELECT COUNT(DISTINCT player_id) FROM login_db.t_login WHERE create_time >= '{$start_time}' AND create_time < '{$end_time}' AND type = 1")->queryScalar();

        $d = $this->retainDate();

        foreach ($d as $k => $v) {
            $player_list = $this->db->createCommand("SELECT u_id FROM login_db.t_lobby_player WHERE reg_time >= '{$v[0]}' AND reg_time < '{$v[1]}'")->queryColumn();
            $list = implode(',', $player_list);
            $data[$k] = $this->db->createCommand("SELECT COUNT(*) FROM login_db.t_login WHERE create_time >= '{$start_time}' AND create_time < '{$end_time}' AND type = 1 AND player_id IN ('$list')")->queryScalar();
        }

        $data['channel_id'] = 1;
        $data['stat_date'] = $stat_date;

        $info = $this->db->createCommand()->insert('stat_base_player', $data)->execute();
        if (!$info) {
            Yii::info('用户相关统计失败：' . $info);
        }
    }

    /**
     * 充值相关
     */
    public function actionRecharge($start_time = '', $end_time = '')
    {
        $start_time = $start_time ? : date('Y-m-d 00:00:00', time() - 86400);
        $end_time = $end_time ? : date('Y-m-d 00:00:00', time());
        $s_start_time = strtotime($start_time);
        $s_end_time = strtotime($end_time);
        $stat_date = date('Y-m-d', strtotime($start_time));

        $all_pay = $this->db->createCommand($sql1 = "SELECT COUNT(player_id) AS `pay_user`, COUNT(DISTINCT player_id) AS `pay_count` FROM t_order WHERE status = 1 AND create_time >= '{$start_time}' AND create_time < '{$end_time}'")->queryOne();
        $data['pay_user'] = $all_pay['pay_user'];
        $data['pay_count'] = $all_pay['pay_count'];

        $date_pay = $this->db->createCommand($sql2 = "SELECT COUNT(player_id) AS `new_pay_count`, COUNT(DISTINCT player_id) AS `new_pay_user` FROM t_order WHERE status = 1 AND create_time >= '{$start_time}' AND create_time < '{$end_time}'")->queryOne();
        $data['new_pay_user'] = $date_pay['new_pay_user'];
        $data['new_pay_count'] = $date_pay['new_pay_count'];

        $data['amt'] = $this->db->createCommand($sql3 = "SELECT SUM(goods_price) FROM t_order WHERE status = 1 AND create_time >= '{$start_time}' AND create_time < '{$end_time}'")->queryScalar();
        $data['all_amt'] = $this->db->createCommand($sql4 = "SELECT SUM(goods_price) FROM t_order WHERE status = 1")->queryScalar();
        $data['stat_date'] = $stat_date;

//        系统增发
//        $add_amt = $this->db->createCommand($sql5 = "SELECT SUM(gold_num) AS `gold_num`, COUNT(player_id) AS `add_count` FROM t_service_recharge_log WHERE status = 1 AND `time` > ='{$start_time}' AND `time` < '{$end_time}'")->queryOne();
        $add_amt = (new Query())->from('t_service_recharge_log')->where(['and', 'status = 1', "time >= '{$start_time}'", "time < '{$end_time}'"])->sum('gold_num');
        $data['add_amt'] = $add_amt;
//        淤积
//        $data['depost'] = $this->db->createCommand($sql6 = "SELECT SUM(`gold_bar`) AS `depost` FROM login_db.t_lobby_player")->queryScalar();
//        vip充值
        $data['vip'] = $this->db->createCommand($sql7 = "SELECT SUM(out_amount) FROM t_vip_recharge_log WHERE status = 1 AND create_time >= '{$start_time}' AND create_time < '{$end_time}'")->queryScalar();

        $info = $this->db->createCommand()->insert('stat_base_recharge', $data)->execute();
        if (!$info) {
            Yii::info('充值统计失败'. $info);
        }
    }

    /**
     * 消耗统计
     */
    public function actionConsume()
    {

    }

    /**
     * 活动统计
     */
    public function actionAgent()
    {

    }

    /**
     * 在线相关
     */
    public function actionOnline($start_time = '', $end_time = '')
    {
        $start_time = $start_time ? : date('Y-m-d 00:00:00', time() - 86400);
        $end_time = $end_time ? : date('Y-m-d 00:00:00', time());
        $stat_date = date('Y-m-d', strtotime($start_time));

        $online = $this->db->createCommand("SELECT `channel_id`, MAX(`num`) AS `max_online`, AVG(`num`) AS `avg_online` FROM t_real_online WHERE stat_time >= '{$start_time}' AND stat_time < '{$end_time}' GROUP BY channel_id")->queryOne();

        $time = $this->db->createCommand("SELECT `channel_id`, MAX(`online_time`) AS `max_time`, AVG(`online_time`) AS `avg_time` FROM login_db.t_login WHERE create_time >= '{$start_time}' AND create_time < '{$end_time}' AND type = 2 GROUP BY channel_id")->queryOne();

        $info = $this->db->createCommand()->insert('stat_online', [
            'channel_id' => $online['channel_id'] ? : 1,
            'stat_date' => $stat_date,
            'max_online' => $online['max_online'] ? : 0,
            'avg_online' => round($online['avg_online']) ? : 0,
            'max_time' => round($time['max_time'] / 60000) ? : 0,
            'avg_time' => round($time['avg_time'] / 60000) ? : 0,
        ])->execute();
        if (!$info) {
            Yii::info('统计在线失败' . $info);
        }
    }

    /**
     * 获取日期的格式化数据
     */
//    private function retainDate($ts)
//    {
//        return [
//            'ru_1' => [
//                date('Y-m-d 00:00:00', $ts - 86400 * 2),
//                date('Y-m-d 00:00:00', $ts - 86400),
//            ],
//            'ru_2' => [
//                date('Y-m-d 00:00:00', $ts - 86400 * 3),
//                date('Y-m-d 00:00:00', $ts - 86400 * 2),
//            ],
//            'ru_3' => [
//                date('Y-m-d 00:00:00', $ts - 86400 * 4),
//                date('Y-m-d 00:00:00', $ts - 86400 * 3),
//            ],
//            'ru_4' => [
//                date('Y-m-d 00:00:00', $ts - 86400 * 5),
//                date('Y-m-d 00:00:00', $ts - 86400 * 4),
//            ],
//            'ru_5' => [
//                date('Y-m-d 00:00:00', $ts - 86400 * 6),
//                date('Y-m-d 00:00:00', $ts - 86400 * 5),
//            ],
//            'ru_6' => [
//                date('Y-m-d 00:00:00', $ts - 86400 * 7),
//                date('Y-m-d 00:00:00', $ts - 86400 * 6),
//            ],
//            'ru_7' => [
//                date('Y-m-d 00:00:00', $ts - 86400 * 8),
//                date('Y-m-d 00:00:00', $ts - 86400 * 7),
//            ],
//            'ru_14' => [
//                date('Y-m-d 00:00:00', $ts - 86400 * 15),
//                date('Y-m-d 00:00:00', $ts - 86400 * 14),
//            ],
//            'ru_30' => [
//                date('Y-m-d 00:00:00', $ts - 86400 * 31),
//                date('Y-m-d 00:00:00', $ts - 86400 * 30),
//            ],
//            'ru_60' => [
//                date('Y-m-d 00:00:00', $ts - 86400 * 61),
//                date('Y-m-d 00:00:00', $ts - 86400 * 60),
//            ],
//        ];
//    }


    private function retainDate()
    {
        return [
            'ru_1' => [
                date('Y-m-d 00:00:00', time() - 86400 * 2),
                date('Y-m-d 00:00:00', time() - 86400),
            ],
            'ru_2' => [
                date('Y-m-d 00:00:00', time() - 86400 * 3),
                date('Y-m-d 00:00:00', time() - 86400 * 2),
            ],
            'ru_3' => [
                date('Y-m-d 00:00:00', time() - 86400 * 4),
                date('Y-m-d 00:00:00', time() - 86400 * 3),
            ],
            'ru_4' => [
                date('Y-m-d 00:00:00', time() - 86400 * 5),
                date('Y-m-d 00:00:00', time() - 86400 * 4),
            ],
            'ru_5' => [
                date('Y-m-d 00:00:00', time() - 86400 * 6),
                date('Y-m-d 00:00:00', time() - 86400 * 5),
            ],
            'ru_6' => [
                date('Y-m-d 00:00:00', time() - 86400 * 7),
                date('Y-m-d 00:00:00', time() - 86400 * 6),
            ],
            'ru_7' => [
                date('Y-m-d 00:00:00', time() - 86400 * 8),
                date('Y-m-d 00:00:00', time() - 86400 * 7),
            ],
            'ru_14' => [
                date('Y-m-d 00:00:00', time() - 86400 * 15),
                date('Y-m-d 00:00:00', time() - 86400 * 14),
            ],
            'ru_30' => [
                date('Y-m-d 00:00:00', time() - 86400 * 31),
                date('Y-m-d 00:00:00', time() - 86400 * 30),
            ],
            'ru_60' => [
                date('Y-m-d 00:00:00', time() - 86400 * 61),
                date('Y-m-d 00:00:00', time() - 86400 * 60),
            ],
        ];
    }
}