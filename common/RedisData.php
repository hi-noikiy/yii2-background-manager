<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 14:33
 */

namespace app\common;


use app\models\PlayerMember;

class RedisData
{
    /**
     * 获取玩家伞下业绩
     *
     * @param $playerIds
     * @param $type查询类型 1今日 2本周 3历史
     */
    public static function getPlayerTodayAchievements($playerId,$type,$date=''){
        $redis = \Yii::$app->redis;

        $redisKey = '';$consume=0;
        $start_time = strtotime(date('Y-m-d'));
        switch ($type){
            case 1:
                if(!$date){
                    $date = date('Ymd');
                }else{
                    $date = date('Ymd',strtotime($date));
                }
                $redisKey = RedisKey::INF_UNDER_DAY_CONSUME.$date;
                break;
            case 2:
                if(!$date){
                    $week_suffix = date('Ymd',strtotime(date('Y-m-d', $start_time)) - date('w',strtotime(date('Y-m-d', $start_time - 86400))) * 86400);
                }else{
                    $week_suffix = date("Ymd",strtotime($date));
                }
                $redisKey = RedisKey::INF_UNDER_WEEK_CONSUME.$week_suffix;
                break;
            case 3:
                $redisKey = RedisKey::INF_UNDER_ALL_CONSUME;
                break;
            default:
                break;
        }
        if($redisKey){
            $consume = $redis->hget($redisKey,$playerId);
        }

        return $consume;
    }

    /**
     * 获取玩家直属业绩
     *
     * @param int $code
     * @param string $msg
     * @param string $data
     */
    public static function getDirectConsume($playerId,$date){
        $directList = self::getDirectPlayers($playerId);

        $consume = 0;
        foreach ($directList as $k=>$v){
            $consume += self::getConsumeByPlayerId($v['player_id'],2,$date);
        }

        return $consume;
    }

    /**
     * 获取玩家直属下级列表
     *
     * @param $playerId
     * @return bool|mixed
     */
    public static function getDirectPlayers($playerId){
        $memberModel = new PlayerMember();
        $directPlayers = $memberModel->getDataByCon(['parent_id'=>$playerId],"player_id");

        return $directPlayers;
    }

    /**
     * 获取玩家业绩
     *
     * @param $playerId
     * @param int $type 1 历史总业绩 2 当天业绩(date默认为今日) 3 周业绩（date默认为本周 0本周 1上一周，2上两周。。。以此类推）
     */
    public static function getConsumeByPlayerId($playerId,$type=1,$date=''){
        $redis = \Yii::$app->redis;
        $redisKey = '';
        if($type == 1){
            $redisKey = RedisKey::INF_ALL_CONSUME;
        }
        if($type == 2){
            if(!$date){
                $date = date('Ymd');
            }else{
                $date = date('Ymd',strtotime($date));
            }
            $redisKey = RedisKey::INF_DAY_CONSUME.$date;
        }

        if($type == 3){
            if(!$date && $date != 0){
                if($date == 0){
                    //本周
                    $thisMonday = date('Ymd', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
                    $redisKey = RedisKey::INF_UNDER_WEEK_CONSUME.$thisMonday;
                }else{
                    $type = $type+1;
                    $num = '-'.$type;
                    $monday = date('Ymd', strtotime($num.' monday', time()));
                    $redisKey = RedisKey::INF_UNDER_WEEK_CONSUME.$monday;
                }
            }
        }

        return $redis->hget($redisKey,$playerId);
    }

    /**
     * 获取当日新增直属玩家业绩和返利-redis
     *
     * @param $agentId
     * @param $date
     * @param $type 1业绩 2返利
     */
    public static function getNewDirectConsume($agentId,$date,$type=1){
        $directPlayerList = DailiCalc::getAgentList($agentId,'newDirectPlayer',$date);
        $directAgentList = DailiCalc::getAgentList($agentId,'newDirectDaili',$date);

        $directList = array_merge($directAgentList,$directPlayerList);

        $result = 0;
        foreach ($directList as $key=>$direct){
            if($type == 1){
                $result += self::getConsumeByPlayerId($direct,2,$date);
            }
        }

        return $result;
    }

    /**
     * 获取当日新增直属玩家业绩和返利-mysql
     *
     * @param $agentId
     * @param $date
     * @param $type 1业绩 2返利
     */
    public static function getNewDirectConsumeMysql($agentId,$date,$type=1){
        $memberList = new PlayerMember();
        $lowerList = $memberList->getDateLowerList($agentId,$date);

        $result = 0;
        foreach ($lowerList as $key=>$direct){
            if($type == 1){
                $result += self::getConsumeByPlayerId($direct['player_id'],2,$date);
            }
        }

        return $result;
    }
}