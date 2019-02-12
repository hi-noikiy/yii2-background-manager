<?php
/**
 * User: SeaReef
 * Date: 2018/9/5 14:08
 *
 * 运营统计定时器
 * 使用原生SQL进行数据统计
 */
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\db\Exception;

class OperateStatController extends AppController
{
    public $db;

    /**
     * 初始化操作
     */
    public function init()
    {
        $this->db = Yii::$app->db;
    }

    /**
     * 玩法参与统计
     * 每个子游戏的参与人数、元宝消耗、环比上日
     */
    public function actionGamePlay($start_time = '', $end_time = '')
    {
        $start_time = $start_time ? : date('Ymd', time() - 86400);
        $stat_date = date('Y-m-d', strtotime($start_time));
        $yesterday_stat_date = date('Y-m-d', strtotime($start_time) - 86400);

        $tableName = 't_gold_record__'.$start_time;


        $game_play = $this->db->createCommand($sql = "SELECT `channel_id`, `gid`, COUNT(DISTINCT player_id) AS `player_number`, COUNT(player_id) AS `player_times`, SUM(num) AS `consume` FROM `{$tableName}` GROUP BY channel_id,gid")->queryAll();

        foreach ($game_play as $v) {
            $data['stat_date'] = $stat_date;
            $data['channel_id'] = $v['channel_id'];
            $data['game_id'] = $v['gid'];
            $data['player_number'] = $v['player_number'] ? : 0;
            $data['player_times'] = $v['player_times'] ? : 0;
            $data['consume'] = $v['consume'] ? : 0;
            $yesterday_player_number = $this->db->createCommand($sql = "SELECT player_number FROM stat_gameplay WHERE stat_date = '{$yesterday_stat_date}' AND channel_id = '{$v['channel_id']}' AND game_id = '{$v['gid']}'")->queryScalar() ? : 1;
            $yesterday_player_times = $this->db->createCommand("SELECT player_times FROM stat_gameplay WHERE stat_date = '{$yesterday_stat_date}' AND channel_id = '{$v['channel_id']}' AND game_id = '{$v['gid']}'")->queryScalar() ? : 1;
            $data['ratio_number'] = $data['player_number'] / $yesterday_player_number;
            $data['ratio_times'] = $data['player_times'] / $yesterday_player_times;

            $res = $this->db->createCommand()->insert('stat_gameplay', $data)->execute();
            if (!$res) {
                Yii::warning('玩法参与统计失败' . print_r($data, 1), FILE_APPEND);
            }
        }
    }

    /**
     * 代理经营数据
     */
    public function actionAgentOperate($start_time = '', $end_time = '')
    {
        $start_time = $start_time ? : date('Y-m-d 00:00:00', time() - 86400);
        $end_time = $end_time ? : date('Y-m-d 00:00:00', time());
        $stat_date = date('Y-m-d', strtotime($start_time));
        $t_start_time = strtotime($start_time);
        $t_end_time = strtotime($end_time);

        $agent_list = $this->db->createCommand("SELECT DISTINCT father_id FROM (SELECT father_id FROM t_income_details WHERE create_time >= '{$t_start_time}' AND create_time < '{$t_end_time}' UNION ALL SELECT gfather_id FROM t_income_details WHERE create_time >= '{$t_start_time}' AND create_time < '{$t_end_time}' UNION ALL SELECT ggfather_id FROM t_income_details WHERE create_time >= '{$t_start_time}' AND create_time < '{$t_end_time}') AS `tmp`")->queryColumn();

        foreach ($agent_list as $v) {
            $data['stat_date'] = $stat_date;
            $data['channel_id'] = 1;
            $data['agent_id'] = $v;

            $s_info = $this->db->createCommand($sql = "SELECT COUNT(id) AS `sub_active`, SUM(`father_num`) AS `sub_offer`, COUNT(DISTINCT player_id) AS `sub_count` FROM `t_income_details` WHERE create_time >= '{$t_start_time}' AND create_time < '{$t_end_time}' AND father_id = '{$v}'")->queryOne();
            $data['sub_active'] = $s_info['sub_active'] ? : 0;
            $data['sub_offer'] = $s_info['sub_offer'] ? : 0;
            $data['sub_count'] = $s_info['sub_count'] ? : 0;

            $ss_info = Yii::$app->db->createCommand($sql = "SELECT COUNT(id) AS `ssub_active`, SUM(`gfather_num`) AS `ssub_offer`, COUNT(DISTINCT player_id) AS `ssub_count` FROM `t_income_details` WHERE create_time >= '{$t_start_time}' AND create_time < '{$t_end_time}' AND gfather_id = '{$v}'")->queryOne();
            $data['ssub_active'] = $ss_info['ssub_active'] ? : 0;
            $data['ssub_offer'] = $ss_info['ssub_offer'] ? : 0;
            $data['ssub_count'] = $ss_info['ssub_count'] ? : 0;

            $sssub_info = Yii::$app->db->createCommand($sql = "SELECT COUNT(id) AS `sssub_active`, SUM(`ggfather_num`) AS `sssub_offer`, COUNT(DISTINCT player_id) AS `sssub_count` FROM `t_income_details` WHERE create_time >= '{$t_start_time}' AND create_time < '{$t_end_time}' AND ggfather_id = '{$v}'")->queryOne();
            $data['sssub_active'] = $sssub_info['sssub_active'] ? : 0;
            $data['sssub_offer'] = $sssub_info['sssub_offer'] ? : 0;
            $data['sssub_count'] = $sssub_info['sssub_count'] ? : 0;

            $res = $this->db->createCommand()->insert('stat_agent_open', $data)->execute();
            if (!$res) {
                Yii::warning('代理经营数据失败' . print_r($data, 1), FILE_APPEND);
            }
        }
    }

    /**
     * 计算一天  玩过游戏的玩家消耗总值
     *
     */
    public function actionOperUserExpendDay(){
        //设置运行参数
        set_time_limit(0);
        $date = date('Ymd',strtotime('-1 day'));
        $tablename = 't_gold_record__'. $date;

        //连接到库了，查出相对应的台费然后求和就行了
        $info='';
        $db      = Yii::$app->db;
        $sql     = "SELECT SUM(num) as NUM,player_id from ".$tablename." where TYPE = 1 group by player_id";
        $info = $db->createCommand($sql)->queryAll();

        //拼装数组 用于批量插入操作；
        if($info){
            $data=[];
            $day = date('Y-m-d',strtotime('-1 day'));
            foreach($info as $k => $v){
                $data[$k]['player_index'] = $v['player_id'];
                $data[$k]['num'] = $v['NUM'];
                $data[$k]['day'] = $day;
            }

            try{
                $info = Yii::$app->db->createCommand()
                    ->batchInsert('t_oper_user_expend_day', ['player_index','num','day'],$data)
                    ->execute();
                var_dump($info);

                echo "success";
            }catch (Exception $e){
                echo "file".json_encode($data);
            }
        }
    }

    /**
     * 每日运营统计
     */
    public function actionDayOperate($start_time = '', $end_time = '')
    {
        $start_time = $start_time ? : date('Y-m-d 00:00:00', time() - 86400);
        $end_time = $end_time ? : date('Y-m-d 00:00:00', time());
        $stat_date = date('Y-m-d', strtotime($start_time));
        $t_start_time = strtotime($start_time);
        $t_end_time = strtotime($end_time);

        $db = Yii::$app->db;
        $data = [];

        /** 代理分成 */
        $table = 't_income_details';
        $agentArr = Yii::$app->db->createCommand("SELECT SUM(father_num) AS `fencheng1`,SUM(gfather_num) AS `fencheng2`,SUM(ggfather_num) AS `fencheng3` FROM {$table} WHERE FROM_UNIXTIME(create_time) BETWEEN '{$start_time}' AND '{$end_time}'")->queryOne();
        $agent = $agentArr['fencheng1']+$agentArr['fencheng2']+$agentArr['fencheng3'];
        if (!$agent) {
            $agent = 0;
        }
        $data['fencheng'] = round($agent/100,2);

        /** 一级代理分成 */
        $data['first_fencheng'] = round($agentArr['fencheng1']/100,2);

        /** 总登录用户数 */
        $data['all_account'] = $db->createCommand("SELECT COUNT(*) FROM login_db.t_lobby_player WHERE reg_time < '{$end_time}'")->queryScalar();

        /** 新增注册 */
        $data['regist'] = $db->createCommand("SELECT COUNT(*) FROM t_player_member WHERE BIND_TIME >= '{$t_start_time}' AND BIND_TIME < '{$t_end_time}'")->queryScalar();

        /** 新增登录 */
        $data['dnu'] = $db->createCommand("SELECT COUNT(*) FROM login_db.t_lobby_player WHERE reg_time >= '{$start_time}' AND reg_time < '{$end_time}'")->queryScalar();

        /** DAU日登陆 */
        $todayLogin = Yii::$app->db->createCommand("SELECT COUNT(*) AS count FROM ( SELECT COUNT(id) FROM login_db.t_login WHERE create_time BETWEEN '{$start_time}' AND '{$end_time}' GROUP BY player_id) tmp")->queryScalar();
        if (!empty($todayLogin)) {
            $data['dau'] = $todayLogin;
        } else {
            $data['dau'] = 0;
        }

        /** 充值金额 */
        $recharge = Yii::$app->db->createCommand("SELECT SUM(f_price) AS `re` FROM lobby_daili.t_order WHERE f_created BETWEEN '{$start_time}' AND '{$end_time}' AND f_status=1 AND f_type=3")->queryScalar();
        if ($recharge == false) {
            $recharge = 0;
        }
        $data['recharge'] = $recharge;

        /** 系统增发 */
        $give = Yii::$app->db->createCommand("SELECT sum(gold_num) as gold_num FROM t_service_recharge_log WHERE TIME BETWEEN '{$start_time}' AND '{$end_time}'")->queryScalar();
        if (!$give) {
            $give = 0;
        }
        $data['system_recharge'] = $give;

        /** 台费消耗 */
        $table_name = 't_gold_record__' . date('Ymd', time() - 86400);
        $juge = Yii::$app->db->createCommand("show tables ")->queryAll();
        $cun =  $this->deep_in_array($table_name,$juge);
        if($cun){
            $goldRecord = Yii::$app->db->createCommand("SELECT SUM(num) FROM {$table_name} WHERE type = 1")->queryScalar();
            $goldRecord = $goldRecord ? : 0;
        }else{
            $goldRecord = 0;
        }
        $data['consume'] = $goldRecord;

        /** 元宝淤积 */
        $gold_bar = Yii::$app->db->createCommand("SELECT SUM(gold_bar) FROM login_db.t_lobby_player")->queryScalar();
        if ($gold_bar == false) {
            $gold_bar = 0;
        }
        $data['gold_bar'] = $gold_bar;

        /** 付费用户 */
        $pay_user = Yii::$app->db->createCommand("SELECT COUNT(DISTINCT f_uid) AS `pay_user` FROM(SELECT f_uid FROM lobby_daili.t_order WHERE f_created BETWEEN '{$start_time}' AND '{$end_time}' AND f_status=1 AND f_type=3) AS `tmp`")->queryScalar();
        if (!empty($pay_user)) {
            $data['pay_user'] = $pay_user;
        } else {
            $data['pay_user'] = 0;
        }

        /** 代理提现 */
        $tiqu = Yii::$app->db->createCommand("SELECT SUM(PAY_MONEY) AS `money` FROM t_pay_order WHERE UPDATE_TIME BETWEEN '{$t_start_time}' AND '{$t_end_time}' AND PAY_STATUS=1")->queryScalar();
        if ($tiqu == false) {
            $tiqu = 0;
        }
        $data['tiqu'] = round($tiqu/100,2);


        /** 新手赠送 */
        $new = $db->createCommand($sql = "SELECT COUNT(player_index) FROM player_log.player_activity_sign WHERE FROM_UNIXTIME(novice_gift_time) >= '{$start_time}' AND FROM_UNIXTIME(novice_gift_time) < '{$end_time}' AND novice_gift_sign = 2 AND gm_sign=1")->queryScalar() * 50;
        $data['new_user'] = $new;

        /** 首冲礼包 */
        $first_recharge = $db->createCommand("SELECT SUM(f_num) FROM lobby_daili.t_order WHERE f_charge_id = 239 AND f_status = 1 AND f_type = 3 AND f_created >= '{$start_time}' AND f_created < '{$end_time}'")->queryScalar();
        $data['fist_recharge'] = $first_recharge;

        /** 次日留存 */
        $data['ru1'] = 0;
        $three_day = date('Y-m-d 00:00:00', time() - 86400 * 2);
        $three_day_end = date('Y-m-d 23:59:59', time() - 86400 * 2);
        $s_day = date('Y-m-d 00:00:00', time() - 86400);
        $e_day = date('Y-m-d 23:59:59', time() - 86400);
        $stat_date_ru1 = date('Y-m-d', strtotime($start_time) - 86400);
        //前天新增
        $yesterdayAdd = Yii::$app->db->createCommand("SELECT COUNT(*) FROM login_db.t_lobby_player WHERE reg_time BETWEEN '{$three_day}' AND '{$three_day_end}'")->queryScalar();
        //前天新增增登陆，并且昨日日又登陆的用户数
        $todayLogin = Yii::$app->db->createCommand("SELECT COUNT(*) AS count FROM ( SELECT player_id FROM login_db.t_login AS a LEFT JOIN login_db.t_lobby_player AS b ON a.player_id = b.u_id WHERE a.create_time BETWEEN '{$s_day}' AND '{$e_day}' AND b.reg_time BETWEEN '{$three_day}' AND '{$three_day_end}' GROUP BY player_id ) tmp")->queryScalar();
        //前天的次日留存率
        $morrowRetentionRate = $yesterdayAdd == 0 ? 0 : round($todayLogin/$yesterdayAdd,4);
        //更新前天的次日留存
        $update_ru1['ru1'] =$morrowRetentionRate;
        $update_ru1['create_time'] =$stat_date_ru1;

        /** ARPU */
        $data['arpu'] = $data['pay_user'] == 0 ? 0 : $data['arpu'] = round($data['recharge'] / $data['pay_user'], 2);

        /** ARPPU */
        $data['arppu'] = $data['dau'] == 0 ? 0 : $data['arppu'] = round($data['recharge'] / $data['dau'], 2);

        //记录日志
        Yii::info('每日运营统计数据：'.json_encode($data));

        //入库
        Yii::$app->db->createCommand("INSERT INTO t_oper_user_day VALUES(NULL, '{$stat_date}', '{$data['regist']}', '{$data['dnu']}', '{$data['ru1']}', '{$data['all_account']}', '{$data['dau']}', '{$data['recharge']}', '{$data['system_recharge']}', '{$data['gold_bar']}', '{$data['consume']}', '{$data['pay_user']}', '{$data['arpu']}', '{$data['arppu']}', '{$data['fencheng']}','{$data['first_fencheng']}', '{$data['tiqu']}', '{$data['new_user']}', '{$data['fist_recharge']}')")->execute();

        //更新前天的次日留存
        Yii::$app->db->createCommand($sql = "UPDATE t_oper_user_day SET ru1 = '{$update_ru1['ru1']}' WHERE create_time = '{$update_ru1['create_time']}'")->execute();

    }

    /**
     * 消耗分布
     */
    public function actionConsumeStat($start_time = 0, $end_time = 0)
    {
        $start_time = $start_time ? : date('Y-m-d 00:00:00', time() - 86400);
        //测试
//        $start_time = $start_time ? : date('Y-m-d 00:00:00');
        
        $end_time = $end_time ? : date('Y-m-d 00:00:00', time());
        $stat_date = date('Y-m-d', strtotime($start_time));
        $yes_stat_date = date('Y-m-d', strtotime($start_time) - 86400);
        $s_stat_date = date('Ymd', strtotime($start_time));
        $t_start_time = strtotime($start_time);
        $t_end_time = strtotime($end_time);
        $table_name = 't_gold_record__' . $s_stat_date;

        $db = Yii::$app->db;
        $sql = "SELECT channel_id, gid, level, COUNT(DISTINCT player_id) AS `active`, COUNT(player_id) AS `active_count`, SUM(num) AS `consume` FROM `{$table_name}` GROUP BY gid, level WITH ROLLUP";
        $data = $db->createCommand($sql)->queryAll();
//        var_dump($data);

        foreach ($data as $v) {
            if (empty($v['gid'])) {
                $v['gid'] = 'all';
            }
            if (empty($v['level'])) {
                $v['level'] = 'all';
            }
            $yes_consume = $db->createCommand($sql1 = "SELECT `consume` FROM `stat_consume` WHERE gid = '{$v['gid']}' AND level = '{$v['level']}'")->queryScalar() ? : 1;
            $ring_ratio = $v['consume'] / $yes_consume;

            $t = $db->createCommand($sql2 = "INSERT INTO stat_consume VALUES(NULL, '{$stat_date}', '{$v['channel_id']}', '{$v['gid']}', '{$v['level']}', '{$v['active']}', '{$v['active_count']}', '{$v['consume']}', 0, '{$ring_ratio}')")->execute();
            if (!$t) {
                Yii::warning("统计消耗分布失败" . print_r([$sql1, $sql2], 1), FILE_APPEND);
            }
        }
    }

    /**
     * 更新消耗占比、环比
     */
    public function actionConsumeUpdate($start_time = 0, $end_time = 0)
    {
        $start_time = $start_time ? : date('Y-m-d 00:00:00', time() - 86400);
        $end_time = $end_time ? : date('Y-m-d 00:00:00', time());
        $stat_date = date('Y-m-d', strtotime($start_time));
        $s_stat_date = date('Ymd', strtotime($start_time));
        $t_start_time = strtotime($start_time);
        $t_end_time = strtotime($end_time);
        $table_name = 'stat_consume';

        $db = Yii::$app->db;
        $sql = "SELECT * FROM `{$table_name}` WHERE level != 'all' AND stat_date = '{$stat_date}'";
        $data = $db->createCommand($sql)->queryAll();
//        var_dump($data);

        foreach ($data as $v) {
            $prop = $v['consume'] / Yii::$app->db->createCommand($sql2 = "SELECT consume FROM `{$table_name}` WHERE gid = '{$v['gid']}' AND level = 'all' AND stat_date = '{$stat_date}'")->queryScalar() ? : 1;
            $info = $db->createCommand($sql = "UPDATE `{$table_name}` SET prop = '{$prop}' WHERE stat_date = '{$v['stat_date']}' AND gid = '{$v['gid']}' AND level = '{$v['level']}'")->execute();
            if (!$info) {
                Yii::warning('更新每日消耗占比失败', print_r([$sql2, $sql], 1), FILE_APPEND);
            }
        }
    }


    public function actionTest(){
        $str = '10.92973642';
        $str = explode('.',$str*10000)[0]/10000;
        echo ($str*100)."%";
    }
}

