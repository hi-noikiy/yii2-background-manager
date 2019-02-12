<?php
/**
 * User: SeaReef
 * Date: 2018/7/4 11:15
 *
 * 各功能模块的数据统计
 */
namespace app\commands;

use app\common\Code;
use app\common\Common;
use app\common\DailiCalc;
use app\common\RedisData;
use app\models\AgentBusinessList;
use app\models\DailiPlayer;
use app\models\LobbyPlayer;
use app\models\PlayerMember;
use app\models\Robot;
use yii\db\Query;
use Yii;
use app\models\GeneralRobot;

class StatController extends AppController
{
    const INTERVAL_TIME = 600;

    const MENGXIN_KEY = 'mengxin';

    const MENGXIN_JUSH = 'jushu';

    /**
     * 萌新输赢统计
     * 新用户总数、总场数、赢五局的人数、输掉的场数、赢元宝、输元宝
     */
    public function actionMengxin($start_date = '', $end_date = '')
    {
        $start_date = empty($start_date) ? date('Y-m-d 00:00:00',time() - 86400) : $start_date;
        $end_date = empty($end_date) ? date('Y-m-d 00:00:00', time()) : $end_date;
        //测试
//        $start_date = empty($start_date) ? date('Y-m-d 00:00:00',time()) : $start_date;
//        $end_date = empty($end_date) ? date('Y-m-d 00:00:00', time() + 86400) : $end_date;

        $db = Yii::$app->db;

        //新用户总数、id列表
        $all_count = $db->createCommand("SELECT COUNT(*) FROM login_db.t_lobby_player WHERE reg_time >= '{$start_date}' AND reg_time < '{$end_date}'")->queryScalar();
        $uid_list = $db->createCommand($sql = "SELECT u_id FROM login_db.t_lobby_player WHERE reg_time >= '{$start_date}' AND reg_time < '{$end_date}'")->queryColumn();
        $uid_list_str = implode(',', $uid_list);


        $player_log = 'log_game_player_record';

        //输赢统计
        if($uid_list_str){
            $win = $db->createCommand("SELECT COUNT(win_gold) AS `win_count`, SUM(win_gold) AS `win_sum` FROM $player_log WHERE win_gold > 0 AND player_id IN ($uid_list_str)")->queryOne();
            $lose = $db->createCommand("SELECT COUNT(win_gold) AS `lose_count`, SUM(win_gold) AS `lose_sum` FROM $player_log WHERE win_gold < 0 AND player_id IN ($uid_list_str)")->queryOne();
            //满足赢五局的人数
            $accord = $db->createCommand($sql2 = "SELECT COUNT(*) FROM (SELECT COUNT(ID) AS `accord` FROM $player_log WHERE WIN_GOLD > 0 AND PLAYER_ID IN ($uid_list_str) GROUP BY PLAYER_ID) AS `tmp` WHERE accord > 5")->queryScalar();
            $info = $db->createCommand()->insert('oss.stat_mengxin', [
                'stat_date' => date("Y-m-d",strtotime($start_date)),
                'user_all' => $all_count,
                'play_all' => $win['win_count'] + $lose['lose_count'],
                'play_accord' => $accord,
                'win_count' => $win['win_count'],
                'win_sum' => $win['win_sum'],
                'lose_count' => $lose['lose_count'],
                'lose_sum' => -$lose['lose_sum'],
            ])->execute();
        }else{
            echo 'NO NEW USERS!';exit;
        }

    }

    /**
     * 萌新异常警报
     * 10分钟执行一次
     */
    public function actionMengxinAlert($start_time = '', $end_time = '')
    {
        $start_time = $start_time ? : date('Y-m-d H:i:00');
        $end_time = $end_time ? : date('Y-m-d H:i:00', time() - self::INTERVAL_TIME);

        $record = 'log_game_record';
        $person = 'log_game_player_record';

        $redis = Yii::$app->redis;
        $jushu = $redis->hget(self::MENGXIN_KEY, self::MENGXIN_JUSH);

        $db = Yii::$app->db;
        $sql = "SELECT * FROM (SELECT b.gid, a.player_id, COUNT(a.mengxin) AS `cnt` FROM {$person} AS `a` LEFT JOIN {$record} AS `b` ON a.record_id = b.id WHERE a.mengxin = 1 AND a.win_gold > 0 GROUP BY b.gid, a.player_id) AS `tmp` WHERE cnt > {$jushu}";

        $data = $db->createCommand($sql)->queryAll();

        foreach ($data as $v) {
            $sql2 = "INSERT INTO `log_mengxin_alert` VALUES(NULL, '{$v['gid']}', '{$v['player_id']}', '{$start_time}', '{$v['cnt']}') ON DUPLICATE KEY UPDATE trigger_count = {$v['cnt']}, stat_date = '{$start_time}'";
            $db->createCommand($sql2)->execute();
        }
    }

    /**
     * 普通机器人每日信息统计
     * 机器人总数、上场的机器人、陪玩数、充值元宝、消耗元宝、游戏总场次、赢场次、输出场次、输赢比
     */
    public function actionRobotDay($date = '')
    {
        $d = empty($date) ? date('Ymd', time() - 86400) : $date;
        $record_person_table = "mdwl_activity.t_game_record_person_log" . $d;
        $record_table = 'mdwl_activity.t_game_record_log' . $d;
        $recharge_table = 'player_log.t_lobby_player_log__' . $d;

        $db = Yii::$app->db;

        $robot = (new Query())->select("CONCAT(9, `player_id`)")->from('t_robot_common')->column();
        $robot_str = implode(',', $robot);

        $data['stat_date'] = $d;
        $data['recharge'] = $db->createCommand("SELECT SUM(`COUNT`) FROM {$recharge_table} WHERE PLAYER_ID IN ($robot_str) AND SOURCE_TYPE = 6")->queryScalar() ? : 0;
//        机器人玩的人数
        $robot_c = $db->createCommand("SELECT COUNT(DISTINCT RECORD_ID) AS `ro` FROM {$record_person_table} WHERE PLAYER_ID IN ($robot_str)")->queryScalar();
//        总玩家数
        $all_c = $db->createCommand("SELECT COUNT(DISTINCT PLAYER_ID) AS `all` FROM {$record_person_table} WHERE RECORD_ID IN(SELECT DISTINCT RECORD_ID FROM {$record_person_table} WHERE PLAYER_ID IN ($robot_str))")->queryScalar();
//        陪玩数量
        $data['accompany'] = $all_c - $robot_c ? : 0;

        $data['robot_count'] = $db->createCommand($sql = "SELECT COUNT(DISTINCT PLAYER_ID) FROM {$record_person_table} WHERE PLAYER_ID IN ($robot_str)")->queryScalar();
        $data['play_count'] = $db->createCommand("SELECT COUNT(DISTINCT RECORD_ID) FROM {$record_person_table} WHERE PLAYER_ID IN ($robot_str)")->queryScalar();

//        赢场数、金额
        $win = $db->createCommand("SELECT COUNT(ID) AS `win_count`, SUM(WIN_GOLD) AS `win_num` FROM {$record_person_table} WHERE PLAYER_ID IN ($robot_str) AND WIN_GOLD > 0")->queryOne();
        $data['win_count'] = $win['win_count'] ? : 0;
        $data['win_num'] = $win['win_num'] ? : 0;

//        输场次、金额
        $lose = $db->createCommand("SELECT COUNT(ID) AS `lose_count`, SUM(WIN_GOLD) AS `lose_num` FROM {$record_person_table} WHERE PLAYER_ID IN ($robot_str) AND WIN_GOLD < 0")->queryOne();
        $data['lose_count'] = $lose['lose_count'] ? : 1;
        $data['lose_num'] = $lose['lose_num'] ? : 0;
        $data['wl_ratio'] = $data['win_count'] / $data['lose_count'] ? : 0;
        $data['profit'] = $data['win_num'] + $data['lose_num'] ? : 0;

        $res = $db->createCommand()->insert('stat_robot_day', $data)->execute();
    }

    /**
     * 单一机器人现状
     *
     */
    public function actionRobotOne($interval = '')
    {
        //$end_time = time();
        //$start_time = $end_time-$interval;
        $d = date('Ymd', time()-86400);
        $record_person_table = "mdwl_activity.t_game_record_person_log" . $d;
        $record_table = 'mdwl_activity.t_game_record_log' . $d;
        $recharge_table = 'player_log.t_lobby_player_log__' . $d;
        $db = Yii::$app->db;

        $data = $db->createCommand("select * from t_robot_common")->queryAll();
        //var_dump($data);exit;
        if ($data) {
            foreach ($data as $val) {
                if (!$val['init_yuanbao']) {
                    (new Robot())->saveRobotInitYuanbao($val);
                }
                //当前元宝数
                $current = $db->createCommand("select player_gold_new from {$record_person_table} where player_id like '%{$val['player_id']}' order by id desc")->queryScalar();
                $val['dangqian'] = $current?$current:$val['dangqian'];
                //充值次数
                $val['recharge'] += $db->createCommand("select count(id) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id like '%{$val['player_id']}'")->queryScalar();
                //补充总额
                $val['all_recharge'] += $db->createCommand("select sum(count) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id like '%{$val['player_id']}'")->queryScalar();
                //游戏场次
                $val['game_count'] += $db->createCommand("select count(id) as num from {$record_person_table} where player_id like '%{$val['player_id']}'")->queryScalar();
                //赢场次
                $val['win_count'] += $db->createCommand("select count(id) as num from {$record_person_table} where player_id like '%{$val['player_id']}' and win_gold>0")->queryScalar();
                //输场次
                $val['lose_count'] += $db->createCommand("select count(id) as num from {$record_person_table} where player_id like '%{$val['player_id']}' and win_gold<0")->queryScalar();
                //赢元宝数
                $val['win_yuanbao'] += $db->createCommand("select sum(`count`) as num from {$recharge_table} where player_id like '%{$val['player_id']}' and operation_type = 1 and source_type = 0")->queryScalar();
                //输元宝数
                $val['lose_yuanbao'] += $db->createCommand("select sum(`count`) as num from {$recharge_table} where player_id like '%{$val['player_id']}' and operation_type = 2 and source_type = 0")->queryScalar();
                if ($val['lose_count'] == 0) {
                    $val['win_lose'] = 1;
                } else {
                    $val['win_lose'] = round($val['win_count']/($val['lose_count'] + $val['win_count']),5);
                }
                $db->createCommand()->update('t_robot_common',$val,'player_id='.$val['player_id'])->execute();
            }
        }
    }

    /**
     * 百人场当天统计
     * 十分钟更新一次
     */
    public function actionHundredsStat()
    {
        $gid_arr = Yii::$app->params['hundreds_games'];
        foreach ($gid_arr as $value) {
            $gid = $value;
            //当前奖池额度，累计输赢，元宝库，今日输赢，今日用户，今日玩家消耗
            $day_table = 'log_hundred_game_day_record';
            $table = 'log_hundred_game_record';
            $player_table = 'log_hundred_game_player_record';
            $start_time = strtotime('today');
            $end_time = strtotime('tomorrow');
            $db = Yii::$app->db;
            //今日玩家消耗
            $rows = (new Query())
                ->select('sum(service_fee) as service_fee')
                ->from($table)
                ->where('unix_timestamp(`date`) <'.$end_time.' and unix_timestamp(`date`) >='.$start_time.' and gid = '.$gid)
                //->groupBy(['gid'])
                ->orderBy('id asc')
                ->one();
            //今日用户,今日输赢
            $player = (new Query())
                ->select('count(distinct player_id) as player_num,sum(win_num) as win')
                ->from($player_table)
                ->where('robot_type = 0 and unix_timestamp(`date`) <'.$end_time.' and unix_timestamp(`date`) >='.$start_time.' and gid = '.$gid)
                //->groupBy(['gid'])
                ->one();
            //最早一条数据
            $old_data = (new Query())
                ->select('*')
                ->from($table)
                ->where('gid = '.$gid)
                ->orderBy('date asc')
                ->one();

            //最新一条数据
            $new_data = (new Query())
                ->select('*')
                ->from($table)
                ->where('gid = '.$gid)
                ->orderBy('date desc')
                ->one();
            $game_redis_info = Yii::$app->game_dev_redis;
            $result = $game_redis_info->hgetall(Yii::$app->params['redisKeys']['br_table_config'].$gid);
            foreach ($result as $k=>$v) {
                if ($k%2 == 0) {
                    $data[$v] = $result[$k+1];
                }
            }
            if (isset($new_data['totalGoldPoolFinal'])) {
                $win = $new_data['gold_pool']-$data['totalGoldPoolFinal'];
            } else {
                $win = 0;
            }
            if (isset($new_data['income_gold'])) {
                $recoveryPool = $new_data['income_gold']-$old_data['income_gold'];
            } else {
                $recoveryPool = 0;
            }
            $redis_data = [
                'service_fee' => isset($rows['service_fee'])?$rows['service_fee']:0,//今日玩家消耗
                'player_num' => isset($player['player_num'])?$player['player_num']:0,//玩家人数
                'today_win' => isset($player['win'])?$player['win']:'',//今日输赢
                'win' => $win,//累计输赢
                'goldPool' => $new_data['gold_pool'],//当前奖池额度
                'recoveryPool' => $recoveryPool,//元宝库
                'time' => date('Y-m-d H:i:s',time())
            ];
            //$redis = Yii::$app->redis;
            $game_redis_info->lpush(Yii::$app->params['redisKeys']['br_robot_info'].$gid.'_'.date('Ymd',time()),json_encode($redis_data,JSON_UNESCAPED_UNICODE));
        }

    }

    /**
     * 百人场每日统计
     * 5-10秒更新一次
     */
    public function actionHundredsDayStat()
    {
        $gid_arr = Yii::$app->params['hundreds_games'];
        foreach ($gid_arr as $value) {
            $gid = $value;
            $day_table = 'log_hundred_game_day_record';
            $table = 'log_hundred_game_record';
            $player_table = 'log_hundred_game_player_record';
            $start_time = strtotime('today');
            $end_time = strtotime('tomorrow');
            $db = Yii::$app->db;
            //计算游戏场次，元宝消耗，天门、地门、顺门投注额
            $row = (new Query())
                ->select('count(id) as game_count,sum(service_fee) as service_fee,sum(win_num_2) as shun_men,sum(win_num_3) as tian_men,sum(win_num_4) as di_men,sum(playerWinNum) as win_num,sum(playerLoseNum) as lose_num')
                ->from($table)
                ->where('unix_timestamp(`date`) <'.$end_time.' and unix_timestamp(`date`) >='.$start_time.' and gid = '.$gid)
                ->one();
            //玩家总人数,去除机器人，去重
            $players = (new Query())
                ->select('count(distinct player_id) as player_num,sum(case when zhuang=1 then 1 else 0 end) as zhuang_num,count(distinct case when zhuang=1 then player_id
             end) as zhuang_count,sum(case when win_num>0 then win_num else 0 end) as win_num,sum(case when win_num<0 then win_num else 0 end) as lose_num')
                ->from($player_table)
                ->where('unix_timestamp(`date`) <'.$end_time.' and unix_timestamp(`date`) >='.$start_time.' and robot_type = 0 and gid = '.$gid)
                ->one();
            /*if ($players) {
                $player_num = count(array_unique(array_column($players,'player_id')));
            } else {
                $player_num = 0;
            }*/
            $player_num = null;//玩家人数
            $zhuang_num = null;//上庄人数
            $zhuang_count = null;//上庄次数
            $win_num = null;//人赢总
            $lose_num = null;//人输总
            //yii::error($players);
            /*foreach ($players as $key=>$val) {
                if ($val['zhuang']) {
                    $zhuang_count++;
                    $zhuang[] = $players['player_id'];
                }
                if ($val['win_num'] >= 0) {
                    $win_num += $val['win_num'];
                } else {
                    $lose_num += $val['win_num'];

                }
            }
            if ($zhuang) {
                $zhuang_num = count(array_unique($zhuang));
            }*/
            if ($players) {
                $player_num = isset($players['player_num'])?$players['player_num']:0;
                $zhuang_num = isset($players['zhuang_num'])?$players['zhuang_num']:0;//上庄人数
                $zhuang_count = isset($players['zhuang_count'])?$players['zhuang_count']:0;//上庄次数
            }
            $income_gold = 0;

            //今日最新数据
            $new_record = (new Query())
                ->select('*')
                ->from($table)
                ->where('gid = '.$gid.' and unix_timestamp(`date`) <'.$end_time.' and unix_timestamp(`date`) >='.$start_time)
                ->orderBy('date desc')
                ->one();
            //今日第一条数据
            $old_record = (new Query())
                ->select('*')
                ->from($table)
                ->where('gid = '.$gid.' and unix_timestamp(`date`) <'.$end_time.' and unix_timestamp(`date`) >='.$start_time)
                ->orderBy('date asc')
                ->one();
            if (isset($new_record['income_gold']) && isset($old_record['income_gold'])) {
                $income_gold = $new_record['income_gold'] - $old_record['income_gold'];
            }
            $shun_men = null;
            $tian_men = null;
            $di_men = null;
            if ($row) {
                $win_num = isset($row['win_num'])?$row['win_num']:0;//人赢总
                $lose_num = isset($row['lose_num'])?$row['lose_num']:0;//人输总
                $total_men = $row['shun_men']+$row['tian_men']+$row['di_men'];
                if (!$total_men) {
                    $shun_men = 0;
                    $tian_men = 0;
                    $di_men = 0;
                } else {
                    $shun_men = round($row['shun_men']/($row['shun_men']+$row['tian_men']+$row['di_men']),5);
                    $tian_men = round($row['tian_men']/($row['shun_men']+$row['tian_men']+$row['di_men']),5);
                    $di_men = round($row['di_men']/($row['shun_men']+$row['tian_men']+$row['di_men']),5);
                }
            }

            $new_data = null;
            if ($new_record) {
                $new_data = [
                    'date' => date('Y-m-d',$start_time),
                    'gid' => $gid,
                    'game_count' => isset($row['game_count'])?$row['game_count']:0,
                    'gold_pool' => isset($new_record['gold_pool'])?$new_record['gold_pool']:0,
                    'income_gold' => $income_gold,
                    'service_fee' => isset($row['service_fee'])?$row['service_fee']:0,
                    'shun_men' => $shun_men,
                    'tian_men' => $tian_men,
                    'di_men' => $di_men,
                    'player_num' => $player_num,
                    'zhuang_num' => $zhuang_num,
                    'zhuang_count' => $zhuang_count,
                    'total_lose' => $lose_num,
                    'total_win' => $win_num,
                ];
            }else{
                $game_dev_redis = Yii::$app->game_dev_redis;
                $gold_pool = $game_dev_redis->hget(Yii::$app->params['redisKeys']['br_table_config'].$gid,'totalGoldPool');

                $new_data = [
                    'date' => date('Y-m-d',$start_time),
                    'gid' => $gid,
                    'game_count' => 0,
                    'gold_pool' => $gold_pool,
                    'income_gold' => 0,
                    'service_fee' => 0,
                    'shun_men' => 0,
                    'tian_men' => 0,
                    'di_men' => 0,
                    'player_num' => 0,
                    'zhuang_num' => 0,
                    'zhuang_count' => 0,
                    'total_lose' => 0,
                    'total_win' => 0,
                ];
            }
            //yii::error($new_data);
            $result = $db->createCommand('select * from '.$day_table.' where gid = '.$gid.' and unix_timestamp(`date`) = '.$start_time)->queryOne();
            if ($result) {
                $db->createCommand()->update($day_table,$new_data,'gid = '.$gid.' and unix_timestamp(`date`) ='. $start_time)->execute();
                //yii::error('-------');
                //yii::error($new_data);
            } else {
                $db->createCommand()->insert($day_table,$new_data)->execute();
            }
        }
    }

    /**
     * 机器人统计数据每日更新
     * 每天0点1分更新
     */
    public function actionHundredsRobotStat()
    {
        $db = Yii::$app->db;
        $gid_arr = Yii::$app->params['hundreds_games'];
        foreach ($gid_arr as $value) {
            $gid = $value;
            $start_time = strtotime('yesterday');
            $end_time = strtotime('today');
            $robot_table = 't_hundred_robot';
            $player_record = 'log_hundred_game_player_record';
            $where = 'unix_timestamp(`date`) >= '.$start_time.' and unix_timestamp(`date`)< '.$end_time.' and robot_type in (1,2) and gid = '.$gid;

            $lose_rows = (new Query())
                ->select('player_id,count(id) as count')
                ->from($player_record)
                ->where($where.' and win_num < 0')
                ->groupBy('player_id')
                ->all();
            $win_rows = (new Query())
                ->select('player_id,count(id) as count')
                ->from($player_record)
                ->where($where.' and win_num >= 0')
                ->groupBy('player_id')
                ->all();
            $robot_rows = (new Query())
                ->select('*')
                ->from($robot_table)
                ->where('gid ='.$gid)
                ->all();
            if ($robot_rows) {
                foreach ($robot_rows as $key=>$val) {
                    foreach ($win_rows as $k_1=>$v_1) {
                        if ($val['player_id'] == $v_1['player_id']) {
                            $db->createCommand()->update($robot_table,['win_nums'=>$val['win_nums']+$v_1['count']],'player_id = '.$val['player_id'])->execute();
                            $win_nums = $val['win_nums']+$v_1['count'];
                        }
                    }
                    foreach ($lose_rows as $k_2=>$v_2) {
                        if ($val['player_id'] == $v_2['player_id']) {
                            $db->createCommand()->update($robot_table,['lose_nums'=>$val['lose_nums']+$v_2['count']],'player_id = '.$val['player_id'])->execute();
                            $lose_nums = $val['lose_nums']+$v_2['count'];
                        }
                    }
                    $nums = null;
                    if (isset($win_nums)) {
                        $nums += $win_nums;
                    }
                    if (isset($lose_nums)) {
                        $nums += $lose_nums;
                    }
                    if ($nums) {
                        $db->createCommand()->update($robot_table,['game_nums'=>$val['game_nums']+$nums],'player_id = '.$val['player_id'])->execute();
                        $data = $db->createCommand('select * from '.$robot_table.' where player_id = '.$val['player_id'])->queryOne();
                        if ($data['game_nums']) {
                            $db->createCommand()->update($robot_table,['win_percent'=>round($data['win_nums']/$data['game_nums'],5)],'player_id = '.$val['player_id'])->execute();
                        }
                    }
                }
            }
        }

    }

    /**
     * 通用机器人每日统计(所有机器人的和)
     * 每天0点10分统计
     */
    public function actionGeneralRobotDayStat()
    {
        //$gid = 524815;
        $start_time = strtotime('yesterday');
        $end_time = strtotime('today');
        $player_record = 'log_game_player_record';
        $game_record = 'log_game_record';
        $character_table = 't_general_robot_character';
        $lobby_table = 'login_db.t_lobby_player';
        $db_player_log = Yii::$app->player_log;

        $d = date('Ymd', $start_time);
        $recharge_table = 'player_log.t_lobby_player_log__' . $d;
        $gold_record = 't_gold_record__' . $d;//台费记录
        $pool_table = 't_general_robot_gold_pool';
        $log_pool = 'log_general_robot_gold_pool';

        $db = Yii::$app->db;

        //机器人性格占比
        $characters = (new Query())
            ->select('*')
            ->from($character_table)
            ->where('id>0')
            ->all();
        $characters_id = array_column($characters,'id');
        $characters_name = array_column($characters,'commont');

        $game_dev_redis = Yii::$app->game_dev_redis;
        $rows = $game_dev_redis->hgetall(Yii::$app->params['redisKeys']['general_robot_config']);
        foreach ($rows as $k => $v) {
            if ($k % 2 == 0) {
                $key[] = $v;
            } else {
                $value[] = json_decode($v,true);
            }
        }
        $characters_data = [];
        $data = array_combine($key,$value);
        foreach ($data as $key => $val) {
            if (!$val['open']) {//去除删掉的机器人
                unset($data[$key]);
            } else {
                //Yii::error($val);
                //机器人各个性格占比
                $k = array_search($val['characterId'],$characters_id);
                //Yii::error($characters_id);
                $characters_data[$val['characterId']] = [
                    'id' => $val['characterId'],
                    'commont' => $characters_name[$k],
                    'count' => isset($characters_data[$val['characterId']]['count'])?($characters_data[$val['characterId']]['count']+1):1
                ];
            }
        }

        //有效机器人数量
        $robot_num = count($data);

        $uids = array_column($data,'uid');
        $uids = implode(',' ,$uids);
        $yesterdayBorrow = 0;
        $cost_gold = 0;//机器人对局中玩家的消耗
        $player_num = 0;
        if ($uids) {
            //机器人玩的场次
            $robot_record = $db->createCommand('select distinct a.record_id as record
                                             from '.$player_record.' as a left join '.$game_record.' as b on a.record_id = b.id
                                              where player_id in ('.$uids.') and unix_timestamp(end_time) >='.$start_time.'
                                               and unix_timestamp(end_time)<'.$end_time)->queryAll();
            $robot_record = array_column($robot_record,'record');

            if ($robot_record) {
                //陪玩家数
                $player_num = $db->createCommand('select count(distinct player_id) as player_num
                                             from '.$player_record.' as a left join '.$game_record.' as b on a.record_id = b.id
                                              where record_id in ('.implode(',',$robot_record).') and player_id not in ('.$uids.') and unix_timestamp(end_time) >='.$start_time.'
                                               and unix_timestamp(end_time)<'.$end_time)->queryOne();
                //一元匹配场玩家消耗
                $cost_gold = $db->createCommand('select count(record_id) from '.$player_record.' where record_id in ('.implode(',',$robot_record).') and player_id not in ('.$uids.')')->queryScalar();
            }

            //陪玩家数，机器人游戏总场次，赢场次，输场次
            $result = $db->createCommand('select sum(b.player_num) as player_num, count(record_id) as game_count, count( case when win_gold > 0 then record_id end) as win_count,
                                              count( case when win_gold < 0 then record_id end) as lose_count
                                             from '.$player_record.' as a left join '.$game_record.' as b on a.record_id = b.id
                                              where player_id in ('.$uids.') and unix_timestamp(end_time) >='.$start_time.' and unix_timestamp(end_time)<'.$end_time)->queryOne();
            if ($db_player_log->createCommand('show tables like'."'t_lobby_player_log__".$d."'")->execute()) {
                //昨日总借贷数
                $yesterdayBorrow = $db->createCommand("select sum(`count`) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id in ({$uids}) and unix_timestamp(create_time) >= ".$start_time.' and unix_timestamp(create_time) <'.$end_time)->queryScalar();
            }
        }
        //yii::error($result);
        //结算奖池
        $curr_gold = $db->createCommand('select now_gold_pool from '.$pool_table)->queryScalar();
        //初始奖池额度(今日初始加上昨天借贷)
        $yesterdayAddPool = $db->createCommand('select sum(gold_pool) from '.$log_pool.' where recovery_pool = 0 and gold_pool != 0 and unix_timestamp(create_time) >= '.$start_time.' and unix_timestamp(create_time) <'.$end_time)->queryScalar();
        $init_gold = $curr_gold+$yesterdayBorrow-$yesterdayAddPool;

        if (!$db_player_log->createCommand("show tables like "."'t_lobby_player_log__".$d."'")->execute()) {
            $borrow_count = 0;
            $borrow_gold = 0;
        } else {
            //借贷次数
            $borrow_count = $db->createCommand("select count(id) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id in ({$uids})")->queryScalar();
            //借贷额度
            $borrow_gold = $db->createCommand("select sum(`count`) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id in ({$uids})")->queryScalar();
        }

        //输赢比
        $win_percent = 0;
        if ($result['game_count'] == 0) {
            $win_percent = 0;
        } else {
            $win_percent = round($result['win_count']/$result['game_count'],5);
        }
        $new_data = [
            'gid' => 0,
            'date' => date('Ymd',$start_time),
            'character' => json_encode($characters_data,JSON_UNESCAPED_UNICODE),
            'player_num' => isset($player_num['player_num'])?$player_num['player_num']:0,
            'robot_num' => $robot_num,
            'init_gold' => $init_gold,
            'curr_gold' => $curr_gold,
            'cost_gold' => $cost_gold?$cost_gold:0,
            'borrow_gold' => $borrow_gold,
            'borrow_count' => $borrow_count,
            'game_count' => $result['game_count'],
            'win_count' => isset($result['win_count'])?$result['win_count']:0,
            'lose_count' => isset($result['lose_count'])?$result['lose_count']:0,
            'win_percent' => $win_percent
        ];
        $date = (new Query())
            ->select('*')
            ->from('stat_general_robot_day')
            ->where('unix_timestamp(date) = '.$start_time)
            ->one();
        if ($date['date']) {
            if ($date['id']) {
                Yii::$app->db->createCommand()->update('stat_general_robot_day',$new_data,'id = '.$date['id'])->execute();
            }

        } else {
            Yii::$app->db->createCommand()->insert('stat_general_robot_day',$new_data)->execute();
        }
    }

    /**
     * 通用机器人统计
     * 更新前一天数据
     * 携带元宝，当前元宝，信贷次数，信贷额度，游戏场次，赢场次，输场次，输赢比例
     * 每天0点1分更新前一天的数据
     */
    public function actionGeneralRobotStat()
    {
        $start_time = strtotime('yesterday');
        $end_time = strtotime('today');
        $d = date('Ymd', $start_time);
        $recharge_table = 'player_log.t_lobby_player_log__' . $d;
        $lobby_player = 'login_db.t_lobby_player';
        $player_log = 'log_game_player_record';
        $db_player_log = Yii::$app->player_log;
        $game_log = 'log_game_record';

        $db = Yii::$app->db;

        $game_dev_redis = Yii::$app->game_dev_redis;
        $rows = $game_dev_redis->hgetall(Yii::$app->params['redisKeys']['general_robot_config']);
        foreach ($rows as $k => $v) {
            if ($k % 2 == 0) {
                $key[] = $v;
            } else {
                $value[] = json_decode($v,true);
            }

        }
        $data = array_combine($key,$value);
        if ($data) {
            foreach ($data as $val) {
                if (!$val['uid']) {
                    continue;
                }

                //当前元宝数(直接查询)
                $now_coin = $db->createCommand('select gold_bar from '.$lobby_player.' where u_id = '.$val['uid'])->queryScalar();
                //携带元宝数
                $take_coin = $db->createCommand('select extend_1 from '.$lobby_player.' where u_id = '.$val['uid'])->queryScalar();

                if (!$db_player_log->createCommand("show tables like "."'t_lobby_player_log__".$d."'")->execute()) {
                    $borrow_num = 0;
                    $borrow_limit = 0;
                } else {
                    //信贷次数
                    $borrow_num = $db->createCommand("select count(id) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id = {$val['uid']}")->queryScalar();

                    //信贷额度
                    $borrow_limit = $db->createCommand("select sum(`count`) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id = {$val['uid']}")->queryScalar();
                }

                //赢场次
                $win_num = $db->createCommand("select count(a.id)  from {$player_log} as a left join {$game_log} as b on a.record_id = b.id where player_id = {$val['uid']} and unix_timestamp(end_time) >= {$start_time} and unix_timestamp(end_time) < {$end_time} and win_gold >0")->queryScalar();

                //$win_num = $db->createCommand("select count(`id`) as num from {$player_log} where player_id = {$val['uid']} and win_gold > 0")->queryScalar();
                //输场次
                $lose_num = $db->createCommand("select count(a.id)  from {$player_log} as a left join {$game_log} as b on a.record_id = b.id where player_id = {$val['uid']} and unix_timestamp(end_time) >= {$start_time} and unix_timestamp(end_time) < {$end_time} and win_gold <0")->queryScalar();

                //$lose_num = $db->createCommand("select count(`id`) as num from {$player_log} where player_id = {$val['uid']} and win_gold < 0")->queryScalar();

                $result = $db->createCommand('select * from t_general_robot where player_id = '.$val['uid'])->queryOne();
                if ($result) {//已统计
                    $borrow_num +=isset($result['borrow_num'])?$result['borrow_num']:0;
                    $borrow_limit +=isset($result['borrow_limit'])?$result['borrow_limit']:0;
                    $win_num +=isset($result['win_num'])?$result['win_num']:0;
                    $lose_num +=isset($result['lose_num'])?$result['lose_num']:0;
                }
                //游戏场次
                $game_num = $win_num + $lose_num;
                if ($game_num != 0) {
                    $win_percent = round(($win_num/$game_num),5);
                } else {
                    $win_percent = 0;
                }

                $new_data = [
                    'player_id' => $val['uid'],
                    'nickname' => $val['name'],
                    'img_url' => $val['headImg'],
                    'ip' => $val['ip'],
                    'character_id' => $val['characterId'],
                    'now_coin' => $now_coin,
                    'take_coin' => $take_coin,
                    'borrow_num' => $borrow_num?$borrow_num:0,
                    'borrow_limit' => $borrow_limit?$borrow_limit:0,
                    'game_num' => $game_num?$game_num:0,
                    'win_num' => $win_num?$win_num:0,
                    'lose_num' => $lose_num?$lose_num:0,
                    'win_percent' => $win_percent,

                ];
                if ($result) {
                    $db->createCommand()->update('t_general_robot',$new_data,'player_id='.$val['uid'])->execute();
                } else {
                    $db->createCommand()->insert('t_general_robot',$new_data)->execute();
                }
            }
        }
    }

    /**
     * 单个机器人每天的统计
     * 每天0点1分更新
     * 当日初始元宝，当日结算元宝，借贷次数，借贷额度，游戏场次，赢场次，输场次，输赢额度
     */
    public function actionSignalGeneralRobotDayStat()
    {
        $start_time = strtotime('yesterday');
        $end_time = strtotime('today');
        $d = date('Ymd',$start_time);
        $recharge_table = 'player_log.t_lobby_player_log__' . $d;
        $player_log = 'log_game_player_record';
        $game_log = 'log_game_record';
        $stat_table = 'stat_signal_general_robot_day';
        $db_player_log = Yii::$app->player_log;
//        var_dump($db_player_log->createCommand("show tables like '%t_lobby_player_log__20181019%'")->execute());exit;
        //获取所有机器人
        $db = Yii::$app->db;
        $game_dev_redis = Yii::$app->game_dev_redis;
        $rows = $game_dev_redis->hgetall(Yii::$app->params['redisKeys']['general_robot_config']);
        foreach ($rows as $k => $v) {
            if ($k % 2 == 0) {
                $key[] = $v;
            } else {
                $value[] = json_decode($v,true);
            }

        }
        $data = array_combine($key,$value);
        if ($data) {
            foreach ($data as $val) {
                if (!$val['uid']) {//无效机器人
                    continue;
                }
                //昨天第一条记录
                $old_data = $db->createCommand("select * from {$player_log} as a left join {$game_log} as b on a.record_id = b.id where player_id = {$val['uid']} and unix_timestamp(end_time) >= {$start_time} and unix_timestamp(end_time) < {$end_time} order by end_time asc")->queryOne();
                //昨天最后一条记录
                $new_data = $db->createCommand("select * from {$player_log} as a left join {$game_log} as b on a.record_id = b.id where player_id = {$val['uid']} and unix_timestamp(end_time) >= {$start_time} and unix_timestamp(end_time) < {$end_time} order by end_time desc")->queryOne();
                if (!$db_player_log->createCommand('show tables like'."'t_lobby_player_log__".$d."'")->execute()) {
                    $borrow_count = 0;
                    $borrow_limit = 0;
                } else {
                    //信贷次数
                    $borrow_count = $db->createCommand("select count(id) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id = {$val['uid']}")->queryScalar();

                    //信贷额度
                    $borrow_limit = $db->createCommand("select sum(`count`) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id = {$val['uid']}")->queryScalar();
                    yii::error($borrow_limit);
                }

                //赢场次
                $win_count = $db->createCommand("select count(a.id)  from {$player_log} as a left join {$game_log} as b on a.record_id = b.id where player_id = {$val['uid']} and unix_timestamp(end_time) >= {$start_time} and unix_timestamp(end_time) < {$end_time} and win_gold >0")->queryScalar();
                //输场次
                $lose_count = $db->createCommand("select count(a.id)  from {$player_log} as a left join {$game_log} as b on a.record_id = b.id where player_id = {$val['uid']} and unix_timestamp(end_time) >= {$start_time} and unix_timestamp(end_time) < {$end_time} and win_gold < 0 ")->queryScalar();
                //输赢额度
                $win_num = $new_data['gold_new']-$old_data['gold_old'];
                $game_count = $win_count+$lose_count;
                $new_data = [
                    'player_id' => $val['uid'],
                    'date' => date('Ymd',$start_time),
                    'nickname' => $val['name'],
                    'init_gold' => $old_data['gold_old'],
                    'final_gold' => $new_data['gold_new'],
                    'borrow_count' => $borrow_count?$borrow_count:0,
                    'borrow_limit' => $borrow_limit?$borrow_limit:0,
                    'game_count' => $game_count?$game_count:0,
                    'win_count' => $win_count?$win_count:0,
                    'lose_count' => $lose_count?$lose_count:0,
                    'win_num' => $win_num,
                    'created_time' => date('Y-m-d H:i:s',time()),
                    'updated_time' => date('Y-m-d H:i:s',time())
                ];
                $is_exists = $db->createCommand("select id from {$stat_table} where `date` = {$new_data['date']} and player_id = {$val['uid']}")->queryOne();

                if ($is_exists) {
                    unset($new_data['created_time']);
                    $db->createCommand()->update($stat_table,$new_data,'date = '.$new_data['date'].' and player_id = '.$new_data['player_id'])->execute();
                } else {
                    $db->createCommand()->insert($stat_table,$new_data)->execute();
                }
            }
        }
    }

    /**
     * 更新当前额度
     * 每天0点1分更新前一天的额度
     */
    public function actionUpdateNowGoldPool()
    {
        $d = date('Ymd',strtotime('yesterday'));
        $recharge_table = 'player_log.t_lobby_player_log__' . $d;
        $pool_table = 't_general_robot_gold_pool';
        $db = Yii::$app->db;
        //一天机器人的借款额度
        $robots = (new GeneralRobot())->robotInfo();
        $uids = array_column($robots,'uid');
        $uids = implode(',',$uids);
        //一天机器人总借贷额度
        $result = $db->createCommand("select sum(`count`) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id in ({$uids})")->queryScalar();
        //当前机器人奖池信息
        $pool_info = $db->createCommand('select now_gold_pool from '.$pool_table)->queryScalar();
        if ($result) {
            $db->createCommand()->update($pool_table,['now_gold_pool'=>$pool_info-$result])->execute();
        }

    }

    /**
     * 用户每日输赢统计
     * 需要统计的数据
     * 用户id、用户昵称、当前元宝数、游戏局数、服务费、赢元宝数、输元宝数、毛收益、输赢比、
     * 当前元宝数.服务费、添加子游戏分析
     */
    public function actionWinLose($stat_day = '')
    {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d',time() - 86400);
        $stat_day = empty($stat_day) ? date('Ymd', time() - 86400) : $stat_day;

        $table1 = '`log_game_player_record`';
        $table2 = '`log_game_record`';

        $db = Yii::$app->db;
        $data = $db->createCommand($sql = "SELECT player_id, gid, nickname, SUM(dizhu) AS `DIZHU`, COUNT(*) AS `total_count`, SUM(win_gold) AS `GOLD` FROM (SELECT a.*, b.gid, b.table_id, b.dizhu FROM {$table1} AS `a` LEFT JOIN {$table2} AS `b` ON a.record_id = b.id WHERE a.updated_time > '{$yesterday}' AND a.updated_time < '{$today}' AND a.updated_time = b.updated_time) AS `tmp` GROUP BY player_id, gid WITH ROLLUP")->queryAll();

        foreach ($data as $k) {
            $gid = ($k['gid'] == NULL) ? 0 : $k['gid'];
            $gold = $db->createCommand("SELECT gold_bar FROM login_db.t_lobby_player WHERE u_id = '{$k['player_id']}'")->queryScalar();
            $parent_id = $db->createCommand("SELECT parent_id FROM t_player_member WHERE player_id = '{$k['player_id']}'")->queryScalar();
            if ($parent_id == false) {
                $parent_id = 0;
                $parent_name = '散户';
            } else {
                $parent_name = $db->createCommand("SELECT name FROM t_daili_player WHERE player_id = '{$parent_id}'")->queryScalar();
            }
            $top_id = $this->top($k['player_id']);
            if ($top_id == -1) {
                $top_name = '散户';
            } else {
                $top_name = $db->createCommand("SELECT name FROM t_daili_player WHERE player_id = '{$top_id}'")->queryScalar();
            }
            $nickname = str_replace("'", "\'", $k['nickname']);
            $parent_name = str_replace("'", "\'", $parent_name);
            $top_name = str_replace("'", "\'", $top_name);

            $info = $db->createCommand("INSERT INTO t_win_lose(id, stat_date, game_id, player_id, player_name, current_gold, counter, dizhu, counter_res, parent_id, parent_name, top_id, top_name) VALUES(NULL, '{$d}', '{$gid}', '{$k['player_id']}', '{$nickname}', '{$gold}', '{$k['total_count']}', '{$k['DIZHU']}', '{$k['GOLD']}', '{$parent_id}', '{$parent_name}', '{$top_id}', '{$top_name}')")->execute();
        }

        $data1 = $db->createCommand($sql2 = "SELECT player_id, gid, SUM(win_gold) AS `win`, COUNT(*) AS `win_count` FROM (SELECT a.player_id, a.record_id,a.updated_time, a.win_gold, b.gid, b.id FROM {$table1} AS `a` LEFT JOIN {$table2} AS `b` ON a.record_id = b.id WHERE a.updated_time > '{$yesterday}' AND a.updated_time < '{$today}' AND a.updated_time = b.updated_time AND a.win_gold >= 0) AS `tmp` GROUP BY player_id, gid WITH ROLLUP")->queryAll();

        foreach ($data1 as $v) {
            $db->createCommand("UPDATE t_win_lose SET win = '{$v['win']}', win_count = '{$v['win_count']}' WHERE game_id = '{$v['gid']}' AND player_id = '{$v['player_id']}' AND stat_date = '{$stat_day}'")->execute();
        }

        $data2 = $db->createCommand($sql3 = "SELECT player_id, gid, SUM(win_gold) AS `lose`, COUNT(*) AS `lose_count` FROM (SELECT a.player_id, a.record_id, a.win_gold, b.gid, b.id FROM {$table1} AS `a` LEFT JOIN {$table2} AS `b` ON a.record_id = b.id WHERE a.updated_time > '{$yesterday}' AND a.updated_time < '{$today}' AND a.updated_time = b.updated_time AND a.win_gold < 0 ) AS `tmp` GROUP BY player_id, gid WITH ROLLUP")->queryAll();

        foreach ($data2 as $v) {
            $db->createCommand($sql4 = "UPDATE t_win_lose SET lose = '{$v['lose']}', lose_count = '{$v['lose_count']}', rate_win_lose = `win_count` / ('{$v['lose_count']}' + `win_count`), gross_yield=`win` - '{$v['lose']}' WHERE game_id = '{$v['gid']}' AND player_id = '{$v['player_id']}' AND stat_date = '{$stat_day}'")->execute();
        }

        var_dump($sql2, $sql3);
    }

    /**
     * 求玩家顶级上司
     * @return
     */
    private function top($player_id)
    {
        $db = Yii::$app->db;
        $data = $db->createCommand("SELECT player_id, parent_id FROM t_player_member WHERE player_id = '{$player_id}'")->queryOne();
        if ($data == false) {
            return -1;
        }
        if ($data['parent_id'] == '999') {
            return $data['player_id'];
        } else {
            return $this->top($data['parent_id']);
        }
    }

    /**
     * 统计代理昨天收入排行
     */
    public function actionStatDailiYesterdayIncome()
    {
        $players = (new Query())
            ->select('name,player_id')
            ->from('t_daili_player')
            ->all();
        $ids = array_column($players,'player_id');
        //$where = '';
        $where = 'create_time >= '.strtotime('yesterday').' and create_time < '.strtotime('today');
        $sortList = [];
        foreach ($ids as $key => $val) {
            $father_num = (new Query())
                ->select('sum(father_num) as num')
                ->from('t_income_details')
                ->where($where)
                ->andWhere('father_id = '.$val)
                ->one();
            $gfather_num = (new Query())
                ->select('sum(gfather_num) as num')
                ->from('t_income_details')
                ->where($where)
                ->andWhere('gfather_id = '.$val)
                ->one();
            $ggfather_num = (new Query())
                ->select('sum(ggfather_num) as num')
                ->from('t_income_details')
                ->where($where)
                ->andWhere('ggfather_id = '.$val)
                ->one();
            $total_num = round(($father_num['num'] + $gfather_num['num'] + $ggfather_num['num'])/100,2);
            if ($total_num > 0) {
                $sortList[$val] = $total_num;
                arsort($sortList);
                if (count($sortList) >10) {
                    array_pop($sortList);
                }
            }


        }
        Yii::$app->redis->del(Yii::$app->params['redisKeys']['daili_income_rank']);
        foreach ($sortList as $key => $val) {
            Yii::$app->redis->hset(Yii::$app->params['redisKeys']['daili_income_rank'],$key,$val);
        }
    }

    /**
     * 代理经营列表统计
     *
     */
    public function actionAgentBusinessList(){
        $DaiLiPlayer = new DailiPlayer();
        $agentIds = $DaiLiPlayer->getDataByCon(array(),'player_id,name,tel,true_name,parent_index,create_time');

//        $start = "2018-12-04";
//        $end = "2018-12-05";
//
//        $common = new Common();
//
//        if (($start != $end) && !empty($end)) {
//            $resDate = $common->Date_segmentation($start, $end)['days_list'];
//        } else {
//            $resDate[] = $start;
//        }
        $resDate[] = date("Y-m-d",strtotime(date("Y-m-d")) - 86400);
        
        $data = [];
        $i = 0;
        foreach ($agentIds as $key => $val) {
            $playerId = $val['player_id'];
            $loginModel = new LobbyPlayer();
            $channelId = $loginModel->getPlayerInfo(['u_id'=>$playerId],'channel_id',3);
            $parentId = $val['parent_index'];
            $parentName = $DaiLiPlayer->getDataByCon(['player_id'=>$parentId],'name',3);
            $nickName = $val['name'];
            $trueName = $val['true_name'];
            $createTime = $val['create_time'];
            $tel = $val['tel'];
            foreach ($resDate as $k => $v) {
                $thisYesterday = date('Y-m-d', strtotime($v) - 86400);//当前的日期的昨日

                $data['stat_date'] = $v;
                $data['agent_id'] = $playerId;
                $data['channel_id'] = $channelId;
                $data['nickname'] = $nickName;
                $data['true_name'] = $trueName;
                $data['tel'] = $tel;
                $data['create_time'] = $createTime;
                $data['parent_id'] = $parentId;
                $data['parent_name'] = $parentName;

                $deposit = Yii::$app->params['gold_withdraw_deposit'];

                $dayUnderConsume = Common::disposeStr(RedisData::getPlayerTodayAchievements($playerId, 1, $v) / $deposit);//当日伞下业绩
                $yesterdayUnderConsume = Common::disposeStr(RedisData::getPlayerTodayAchievements($playerId, 1, $thisYesterday) / $deposit);//当日的昨日伞下业绩

                $data['day_under_consume'] = $dayUnderConsume;

                $diff = $yesterdayUnderConsume ? ($dayUnderConsume - $yesterdayUnderConsume) / $yesterdayUnderConsume : ($dayUnderConsume - $yesterdayUnderConsume);
                $diffStr = '';
                if ($diff < 0) {
                    $diff = abs($diff);
                }
                $radioUnderConsume = Common::disposeStr($diff) * 100;
                $tendency = '↓';
                if ($dayUnderConsume > $yesterdayUnderConsume) {
                    $tendency = '↑';
                }
                $data['radio_under_consume'] = $diffStr . $radioUnderConsume . "%" . $tendency;

                $dayDirectConsume = Common::disposeStr(RedisData::getDirectConsume($playerId, $v) / $deposit);
                $yesterdayDirectConsume = (float)Common::disposeStr(RedisData::getDirectConsume($playerId, $thisYesterday) / $deposit);
                $diff2 = $yesterdayDirectConsume ? ($dayDirectConsume - $yesterdayDirectConsume) / $yesterdayDirectConsume : ($dayDirectConsume - $yesterdayDirectConsume);
                $diffStr = '';
                if ($diff2 < 0) {
                    $diff2 = abs($diff2);
                }
                $radioDirectConsume = Common::disposeStr($diff2) * 100;
                $data['day_direct_consume'] = $dayDirectConsume;
                $tendency = '↓';
                if ($dayDirectConsume > $yesterdayDirectConsume) {
                    $tendency = '↑';
                }
                $data['radio_direct_consume'] = $diffStr . $radioDirectConsume . '%' . $tendency;

                $playerMemberModel = new PlayerMember();
                $topId = $playerMemberModel->getTopId($playerId);
                if($topId){
                    $DailiModel = new DailiPlayer();
                    $topName = $DailiModel->getDataByCon(['player_id'=>$topId],'name',3);
                }else{
                    $topName = '';
                }

                $data['top_id'] = $topId;
                $data['top_name'] = $topName;

                $newPlayer = DailiCalc::getDailiInfo($playerId,$v)['newDirectPlayer'];
                $newAgent = DailiCalc::getDailiInfo($playerId,$v)['newDirectDaili'];
                $data['new_player'] = $newPlayer;
                $data['new_agent'] = $newAgent;

//                $data['new_player'] = $this->getListNum($playerId,$v,2);
//                $data['new_agent'] = $this->getListNum($playerId,$v,1);

                $data['new_direct_consume'] = Common::disposeStr(RedisData::getNewDirectConsume($playerId,$v) / $deposit);

                $db = Yii::$app->db;
                $db->createCommand()->insert('agent_business_list',$data)->execute();
            }
        }
    }


    /**
     * 获取下级列表
     *
     * @param $playerId
     * @param $type 1代理 2玩家
     */
    public function getListNum($playerId, $date, $type){
        $memberModel = new PlayerMember();
        $lowerList = $memberModel->getDateLowerList($playerId, $date);

        $agentList = [];
        $playerList = [];
        $dailiModel = new DailiPlayer();
        foreach ($lowerList as $key=>$player){
            if($dailiModel->getDataByCon(['player_id'=>$player],'id',2)){
                $agentList[] = $player;
            }else{
                $playerList[] = $player;
            }
        }

        if($type == 1){
            $num = count($agentList);
        }else if($type == 2){
            $num = count($playerList);
        }else{
            $num = 0;
        }

        return $num;
    }

    /**
     * 代理经营列表统计测试
     *
     */
    public function actionAgentBusinessListTest(){
        $DaiLiPlayer = new DailiPlayer();
        $agentIds = $DaiLiPlayer->getDataByCon(array(),'player_id,name,tel,true_name,parent_index,create_time');

        $start = "2019-01-05";
        $end = "2019-01-07";

        $common = new Common();

        if (($start != $end) && !empty($end)) {
            $resDate = $common->Date_segmentation($start, $end)['days_list'];
        } else {
            $resDate[] = $start;
        }
//        $resDate[] = date("Y-m-d",strtotime(date("Y-m-d")) - 86400);

        $data = [];
        $i = 0;
        foreach ($agentIds as $key => $val) {
            $playerId = $val['player_id'];
//            $parentId = $val['parent_index'];
//            $parentName = $DaiLiPlayer->getDataByCon(['player_id'=>$parentId],'name',3);
//            $nickName = $val['name'];
//            $trueName = $val['true_name'];
//            $createTime = $val['create_time'];
//            $tel = $val['tel'];
            foreach ($resDate as $k => $v) {
//                $thisYesterday = date('Y-m-d', strtotime($v) - 86400);//当前的日期的昨日
//
//                $data['stat_date'] = $v;
//                $data['agent_id'] = $playerId;
//                $data['nickname'] = $nickName;
//                $data['true_name'] = $trueName;
//                $data['tel'] = $tel;
//                $data['create_time'] = $createTime;
//                $data['parent_id'] = $parentId;
//                $data['parent_name'] = $parentName;
//
                $deposit = Yii::$app->params['gold_withdraw_deposit'];
//
//                $dayUnderConsume = Common::disposeStr(RedisData::getPlayerTodayAchievements($playerId, 1, $v) / $deposit);//当日伞下业绩
//                $yesterdayUnderConsume = Common::disposeStr(RedisData::getPlayerTodayAchievements($playerId, 1, $thisYesterday) / $deposit);//当日的昨日伞下业绩
//
//                $data['day_under_consume'] = $dayUnderConsume;
//
//                $diff = $yesterdayUnderConsume ? ($dayUnderConsume - $yesterdayUnderConsume) / $yesterdayUnderConsume : ($dayUnderConsume - $yesterdayUnderConsume);
//                $diffStr = '';
//                if ($diff < 0) {
//                    $diff = abs($diff);
//                }
//                $radioUnderConsume = Common::disposeStr($diff) * 100;
//                $tendency = '↓';
//                if ($dayUnderConsume > $yesterdayUnderConsume) {
//                    $tendency = '↑';
//                }
//                $data['radio_under_consume'] = $diffStr . $radioUnderConsume . "%" . $tendency;
//
//                $dayDirectConsume = Common::disposeStr(RedisData::getDirectConsume($playerId, $v) / $deposit);
//                $yesterdayDirectConsume = (float)Common::disposeStr(RedisData::getDirectConsume($playerId, $thisYesterday) / $deposit);
//                $diff2 = $yesterdayDirectConsume ? ($dayDirectConsume - $yesterdayDirectConsume) / $yesterdayDirectConsume : ($dayDirectConsume - $yesterdayDirectConsume);
//                $diffStr = '';
//                if ($diff2 < 0) {
//                    $diff2 = abs($diff2);
//                }
//                $radioDirectConsume = Common::disposeStr($diff2) * 100;
//                $data['day_direct_consume'] = $dayDirectConsume;
//                $tendency = '↓';
//                if ($dayDirectConsume > $yesterdayDirectConsume) {
//                    $tendency = '↑';
//                }
//                $data['radio_direct_consume'] = $diffStr . $radioDirectConsume . '%' . $tendency;
//
//                $playerMemberModel = new PlayerMember();
//                $topId = $playerMemberModel->getTopId($playerId);
//                if($topId){
//                    $DailiModel = new DailiPlayer();
//                    $topName = $DailiModel->getDataByCon(['player_id'=>$topId],'name',3);
//                }else{
//                    $topName = '';
//                }
//
//                $data['top_id'] = $topId;
//                $data['top_name'] = $topName;

//                $newPlayer = DailiCalc::getDailiInfo($playerId,$v)['newDirectPlayer'];
//                $newAgent = DailiCalc::getDailiInfo($playerId,$v)['newDirectDaili'];
//                $data['new_player'] = $newPlayer;
//                $data['new_agent'] = $newAgent;

                $data['new_player'] = $this->getListNum($playerId,$v,2);
                $data['new_agent'] = $this->getListNum($playerId,$v,1);
                $data['new_direct_consume'] = Common::disposeStr(RedisData::getNewDirectConsumeMysql($playerId,$v) / $deposit);

                echo $playerId;echo '---';echo $v;

//                $model = new AgentBusinessList();
//                $res = $model->updateData($playerId,$v,$data);
//                var_dump($res);

//                $db = Yii::$app->db;
//                $db->createCommand()->insert('agent_business_list',$data)->execute();
            }

        }

    }


    public function actionTest(){
        $playerId = '30498486';
        $startTime = '2018-12-03';
        $endTime = "2019-01-01";

        $data=[];
        $allUnderPlayer = DailiCalc::getAgentList($playerId,'allUnderPlayer');
        $allUnderDaili = DailiCalc::getAgentList($playerId,'allUnderDaili');
        $underListArr = array_merge($allUnderPlayer,$allUnderDaili);
        $underList = implode(',',$underListArr);
        var_dump(implode(',',$allUnderDaili));exit;
        //伞下每日充值汇总(不包括首充)
        $db = Yii::$app->db;
        //$recharge = $db->createCommand("SELECT DATE_FORMAT(create_time,'%Y-%m-%d') as date,SUM(goods_num)/100 as sum FROM `t_order` where goods_type=1 and status=1 and create_time >= '{$startTime}' AND create_time < '{$endTime}' AND player_id in($underList) GROUP BY DATE_FORMAT(create_time,'%Y-%m-%d')")->queryAll();

        //伞下每日首充（不包括赠送的部分）
//        $rechargeFirst = $db->createCommand("SELECT DATE_FORMAT(create_time,'%Y-%m-%d') as date,(COUNT(id) * 800)/100 as sum FROM `t_order` where goods_type=2 and status=1 and create_time >= '{$startTime}' AND create_time < '{$endTime}' AND player_id in($underList) GROUP BY date")->queryAll();
//        $firstRechargeSend = $db->createCommand("SELECT DATE_FORMAT(create_time,'%Y-%m-%d') as date,COUNT(id) * 200 / 100 as sum FROM `t_order` where goods_type=2 and status=1 and create_time >= '{$startTime}' AND create_time < '{$endTime}' AND player_id in($underList) GROUP BY date")->queryAll();
//        $hongbao = $db->createCommand("SELECT DATE_FORMAT(create_time,'%Y-%m-%d') as date,SUM(gold) as sum FROM `t_hongbao`  WHERE create_time >= '{$startTime}' AND create_time < '{$endTime}' AND uid in ($underList) GROUP BY date")->queryAll();

//        $serverRecharge = $db->createCommand("SELECT DATE_FORMAT(time,'%Y-%m-%d') as date,SUM(gold_num)/100 as sum FROM `t_service_recharge_log` WHERE time >= '{$startTime}' AND time < '{$endTime}' AND player_id in ($underList) and status=1 GROUP BY date")->queryAll();
//        $vipRecharge = $db->createCommand("SELECT DATE_FORMAT(create_time,'%Y-%m-%d') as date,SUM(out_amount)/100 as sum FROM `t_vip_recharge_log` WHERE status=1 and create_time >= '{$startTime}' AND create_time < '{$endTime}' AND player_id in ($underList) GROUP BY date")->queryAll();
        $regList = $db->createCommand("SELECT u_id FROM login_db.`t_lobby_player` WHERE u_id in($underList) AND reg_time < '{$endTime}'")->queryAll();
        $disRegList = [];
        foreach($regList as $key=>$val){
            $disRegList[] = $val['u_id'];
        }
        $resRegList = implode(',',$disRegList);
        $newPeople = $db->createCommand("SELECT DATE_FORMAT(bind_time,'%Y-%m-%d') as date,count(id) as sum FROM `t_player_member` WHERE player_id in($resRegList) AND bind_time < '{$endTime}' GROUP BY date")->queryAll();

//$newPeopleSend = $db->createCommand("SELECT DATE_FORMAT(operate_time,'%Y-%m-%d') as date, COUNT(id) * 5 as sum FROM `log_user_activity` WHERE activity_id=2 AND is_operate=1 AND operate_type=1 and  operate_time >= '{$startTime}' AND operate_time < '{$endTime}' AND player_id in($underList) GROUP BY date")->queryAll();
        $this->dispose($newPeople);


    }
    public function dispose($data){
        $res = [];
        foreach ($data as $key=>$val){
            $res[$val['date']] = $val['sum'];
        }

        var_dump(json_encode($res));exit;
    }


}