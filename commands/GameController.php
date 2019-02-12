<?php
/**
 * User: SeaReef
 * Date: 2018/8/10 17:53
 *
 * 游戏服相关定制任务
 */
namespace app\commands;

use app\models\GeneralRobot;
use app\models\HundredRobot;
use Yii;
use yii\console\Controller;
use app\models\LogGeneralRobotGoldPool;
use app\common\Tool;

class GameController extends Controller
{
    const RECORD_LOG_HUNDRED_RECORD = 'br_table_log_';

    /**
     * 每分钟读取战绩数量
     */
    const READ_COUNT = 10000;

    public function actionRecord ()
    {
        $redis = Yii::$app->redis_2;//Yii::$app->params['game_log']
        $gameRecordIds = Yii::$app->params['games_record_id'];
        foreach ($gameRecordIds as $value){
            for ($i = 0; $i <= self::READ_COUNT; $i++) {
                $key = Yii::$app->params['redisKeys']['game_log'].$value;
                $log = $redis->lpop($key);

                Yii::info('战绩log-----:'.$log);

                if (!$log) {
                    break;
                }

                $game_info = null;
                $player_info = null;

                $data = json_decode($log, 1);
                $db = Yii::$app->db;
                //游戏信息
                $game_info[$i]['channel_id'] = 1;
                $game_info[$i]['gid'] = $data['gid'];
                $game_info[$i]['table_id'] = $data['tableId'];
                $game_info[$i]['dizhu'] = $data['dizhu'];
                $game_info[$i]['start_time'] = $data['startTime'];
                $game_info[$i]['end_time'] = $data['overTime'];
                $game_info[$i]['player_num'] = count($data['playerInfo']);
                $game_info[$i]['player_method'] = $data['playerMethod'];
                //TODO::操作记录待定
                $game_info[$i]['operation_logs'] = json_encode($data['operationLogs'],JSON_UNESCAPED_UNICODE);
                $game_info[$i]['created_time'] = date('Y-m-d H:i:s',time());
                $game_info[$i]['updated_time'] = date('Y-m-d H:i:s',time());
                $game_info_keys = array_keys($game_info[$i]);
                $game_info_values[0] = array_values($game_info[$i]);

                $info = $db->createCommand()->batchInsert('log_game_record', $game_info_keys, $game_info_values)->execute();
                if ($info) {
                    $id = $db->getLastInsertID();
                    foreach ($data['playerInfo'] as $key => $val) {
                        //游戏玩家信息
                        $data_[$key]['record_id'] = $id;
                        $data_[$key]['player_id'] = $val['playerId'];
                        $data_[$key]['nickname'] = $val['nickName'];
                        $data_[$key]['player_card'] = implode(',',$val['playerCard']);
                        $data_[$key]['mengxin'] = $val['mengxin'];
                        $data_[$key]['gold_new'] = $val['playerGoldNew'];
                        $data_[$key]['gold_old'] = $val['playerGoldOld'];
                        $data_[$key]['portrait'] = $val['playerPortrait'];
                        $data_[$key]['table_pos'] = $val['tablePos'];
                        $data_[$key]['win_gold'] = $val['winGold'];
                        $data_[$key]['operate'] = [];
                        foreach ($data['operationLogs'] as $v) {
                            if ($v['playerIndex'] == $val['playerId']) {
                                $data_[$key]['operate'][] = $v['operationTime'].$v['type'];
                            }
                        }
                        $data_[$key]['operate'] = implode(',',$data_[$key]['operate']);
                        $data_[$key]['created_time'] = date('Y-m-d H:i:s',time());
                        $data_[$key]['updated_time'] = date('Y-m-d H:i:s',time());

                        $player_info_keys = array_keys($data_[$key]);
                         
                        $player_info[] = array_values($data_[$key]);
                    }
                    $result = $db->createCommand()->batchInsert('log_game_player_record', $player_info_keys, $player_info)->execute();
                }
            }
        }



    }

    /**
     * 百人场游戏信息
     * 每分钟一次
     */
    public function actionHundredsGameRecord()
    {
        $gid_arr = Yii::$app->params['hundreds_games'];

        foreach ($gid_arr as $value) {
            $gid = $value;
            $redis = Yii::$app->game_dev_redis_2;

            if ($redis->llen(self::RECORD_LOG_HUNDRED_RECORD.$gid.'_'.date('Y-m-d',time()-86400))) {
                $d = date('Y-m-d',time()-86400);
            } else {
                $d = date('Y-m-d',time());
            }
            $db = Yii::$app->db;
            for ($i = 0; $i <= self::READ_COUNT; $i++) {
                $log = $redis->lpop(self::RECORD_LOG_HUNDRED_RECORD.$gid.'_'.$d);
                if (!$log) {
                    break;
                }
                $log = json_decode($log, 1);

                $data['gid'] = $gid;
                $data['date'] = $log['data'];
                $data['gold_pool'] = $log['goldPool'];
                $data['income_gold'] = $log['incomeGold'];
                $data['player_id'] = isset($log['playerIndex']) ? $log['playerIndex'] : 0;
                $data['take_gold'] = isset($log['takeGold']) ? $log['takeGold'] : 0;
                $data['poker_str_1'] = isset($log['pokerStr1']) ? $log['pokerStr1'] : '';
                $data['win_2'] = $log['win2'];
                $data['win_num_2'] = $log['winNum2'];
                $data['poker_str_2'] = $log['pokerStr2'];
                $data['win_3'] = $log['win3'];
                $data['win_num_3'] = $log['winNum3'];
                $data['poker_str_3'] = $log['pokerStr3'];
                $data['win_4'] = $log['win4'];
                $data['win_num_4'] = $log['winNum4'];
                $data['poker_str_4'] = isset($log['pokerStr4']) ? $log['pokerStr4'] : '';
                $data['robot_change_gold'] = $log['robotChangeGold'];
                $data['service_fee'] = $log['serviceFee'];
                $data['playerWinNum'] = isset($log['playerWinNum']) ? $log['playerWinNum'] : 0;
                $data['playerLoseNum'] = isset($log['playerLoseNum']) ? $log['playerLoseNum'] : 0;
                $data['created_time'] = date('Y-m-d H:i:s',time());
                $data['updated_time'] = date('Y-m-d H:i:s',time());
                $result = $db->createCommand()->insert('log_hundred_game_record',$data)->execute();
                $id = $db->getLastInsertID();
                if ($result) {
                    $data_ = [];
                    $new_data = [];
                    foreach ($log['brLogPlayers'] as $key => $val) {
                        $data_[$key]['record_id'] = $id;
                        $data_[$key]['gid'] = $gid;
                        $data_[$key]['player_id'] = $val['playerIndex'];
                        $data_[$key]['robot_type'] = isset($val['robotType']) ? $val['robotType'] : 0;
                        $data_[$key]['zhuang'] = isset($val['zhuang']) ? $val['zhuang'] : 0;
                        $data_[$key]['win_num'] = $val['winNum'];
                        $data_[$key]['date'] = $log['data'];
                        $data_[$key]['created_time'] = date('Y-m-d H:i:s',time());
                        $data_[$key]['updated_time'] = date('Y-m-d H:i:s',time());
                        $keys = array_keys($data_[$key]);
                        $new_data[] = array_values($data_[$key]);
                    }

                    $db->createCommand()->batchInsert('log_hundred_game_player_record',$keys,$new_data)->execute();
                }
            }
        }

    }

    /**
     * 每天6点，所有通用机器人下线
     */
    public function actionGeneralRobotSwitchOff()
    {
        Yii::$app->game_dev_redis->hset(Yii::$app->params['redisKeys']['robot_switch'],'sum_switch','false');
    }

    /**
     * 每日早上6点，机器人已下线
     * 早上6点10分执行金币回收
     */
    public function actionGeneralRobotGoldInit()
    {
        $db = Yii::$app->db;
        $game_dev_redis = Yii::$app->game_dev_redis;
        $gold_pool_table = 't_general_robot_gold_pool';
        $player_table = 'login_db.t_lobby_player';
        //$user_id = Yii::$app->user->getId();
        //当前所有机器人
        $rows = $game_dev_redis->hgetall(Yii::$app->params['redisKeys']['general_robot_config']);
        foreach ($rows as $k => $v) {
            if ($k % 2 == 0) {
                $key[] = $v;
            } else {
                $value[] = json_decode($v,true);
            }

        }
        $data = array_combine($key,$value);
        foreach ($data as $key => $val) {
            if (!$val['uid']) {
                unset($data[$key]);
            }
        }
        $uids = [];
        if ($data) {
            $uids = array_column($data,'uid');
            $uids = implode(',',$uids);
        }
        //机器人当前金币总数和初始数
        if ($uids) {
            //$robots = $db->createCommand('select * from '.$player_table.' where u_id in ('.$uids.')')->queryAll();
            $robots_gold = $db->createCommand('select sum(extend_1) as total_init_gold,sum(gold_bar) as total_now_gold from '.$player_table.' where u_id in ('.$uids.')')->queryOne();

            $recovery_gold = $robots_gold['total_now_gold'] - $robots_gold['total_init_gold'];
            $data = [
                'gold_pool' => 0,
                'recovery_pool' => 0
            ];
            if ($recovery_gold > 0) {//回收金额
                $data = [
                    'gold_pool' => 0,
                    'recovery_pool' => $recovery_gold
                ];
                //
            }
            //回收金额写入奖池操作记录
            (new LogGeneralRobotGoldPool())->saveLogGeneralRobotGoldPool($data,1);
            //(new LogGeneralRobotGoldPool())->saveLogGeneralRobotGoldPool($data,$user_id?$user_id:1);

            //重置机器人的金额
            foreach (explode(',',$uids) as $key => $val) {
                $player = $db->createCommand('select * from '.$player_table.' where u_id = '.$val)->queryOne();
                if ($player['extend_1'] > $player['gold_bar']) {//输钱，需借钱
                    Tool::sendGold(6,3,($player['extend_1']-$player['gold_bar']),1,$val);
                } else if ($player['extend_1'] < $player['gold_bar']) {//赢钱，回收钱
                    Tool::sendGold(6,3,($player['gold_bar']-$player['extend_1']),2,$val);
                }
            }

        }
        //读取奖池数据
        $gold_pool = $db->createCommand('select * from '.$gold_pool_table)->queryOne();
        //更新当前奖池额度
        //TODO::更新当前奖池额度
        //打开机器人开关
        Yii::$app->game_dev_redis->hset(Yii::$app->params['redisKeys']['robot_switch'],'sum_switch','true');
    }

    /**
     * 每天6点15分，所有通用机器人开关打开
     */
    public function actionGeneralRobotSwitchOn()
    {
        Yii::$app->game_dev_redis->hset(Yii::$app->params['redisKeys']['robot_switch'],'sum_switch','true');
    }



    /**
     * 机器人的超出警戒线更改性格
     * 每分钟定时执行
     */
    public function actionGeneralRobotCharacter()
    {
        $pool_total = 't_general_robot_gold_pool';
        $db = Yii::$app->db;
        $game_dev_redis = Yii::$app->game_dev_redis;
        //今日额度
        $today_pool = Yii::$app->redis->get(Yii::$app->params['redisKeys']['general_robot_now_gold_pool']);
        $result = $db->createCommand('select * from '.$pool_total)->execute();
        //当前额度除以奖池额度
        if (!$result['total_gold_pool']) {
            $percent = 0;
        } else {
            $percent = ($result['now_gold_pool']-$today_pool)/$result['total_gold_pool'];
        }

        if (!$result) {
            return ;
        }
        if ($result['down_limit']/100 <=$percent && $percent <= $result['up_limit']/100) {//正常范围内终止
            return;
        }
        $character = $db->createCommand('select id from t_general_robot_character')->queryAll();
        $character_ids = array_column($character,'id');
        //获取所有机器人修改性格
        $rows = $game_dev_redis->hgetall(Yii::$app->params['redisKeys']['general_robot_config']);
        foreach ($rows as $k => $v) {
            if ($k % 2 == 0) {
                $key[] = $v;
            } else {
                $value[] = json_decode($v,true);
            }

        }
        $data = array_combine($key,$value);
        foreach ($data as $key => $val) {
            if (!$val['uid']) {
                unset($data[$key]);
            }
        }

        if ($percent > ($result['up_limit']/100)) {//上限修改机器人性格
            foreach ($data as $key => $val) {
                if (isset($result) && $result['character_id']) {
                    $val['characterId'] = $result['character_id'];
                    $game_dev_redis->hset(Yii::$app->params['redisKeys']['general_robot_config'],$key,json_encode($val,JSON_UNESCAPED_UNICODE));
                }
            }
        } else {//下限修改机器人额度
            $key = array_search($result['character_id'],$character_ids);
            unset($character_ids[$key]);
            foreach ($data as $key => $val) {
                if (isset($character_ids) && $character_ids) {
                    $val['characterId'] = $character_ids[0]?$character_ids[0]:$character_ids[count($character_ids)-1];
                    $game_dev_redis->hset(Yii::$app->params['redisKeys']['general_robot_config'],$key,json_encode($val,JSON_UNESCAPED_UNICODE));
                }
            }
        }
    }

    /**
     * 更新当前奖池的数值(暂存在redis中)
     * 每5-10秒统计一次
     */
    public function actionUpdateNowGoldPool()
    {
        $db = Yii::$app->db;
        $d = date('Ymd',strtotime('today'));
        $recharge_table = 'player_log.t_lobby_player_log__' . $d;

        $redis = Yii::$app->redis;
        $robots = (new GeneralRobot())->robotInfo();
        $uids = array_column($robots,'uid');
        $uids = implode(',',$uids);
        //当天机器人总借贷额度
        $result = $db->createCommand("select sum(`count`) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id in ({$uids}) and unix_timestamp(create_time) >= ".strtotime('today'))->queryScalar();
        $redis->set(Yii::$app->params['redisKeys']['general_robot_now_gold_pool'],$result?$result:0);
    }

}