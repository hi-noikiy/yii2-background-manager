<?php
/**
 * User: SeaReef
 * Date: 2018/11/27 23:05
 */
namespace app\commands;

use app\common\RedisKey;
use app\models\GoldRecord;
use app\models\LogRebate;
use app\models\Order;
use Symfony\Component\Cache\Tests\Adapter\NullAdapterTest;
use Yii;
use yii\console\Controller;
use yii\db\Query;
use yii\debug\models\search\Log;

class InfiniteAgentController extends BaseController
{
    /**
     * 需要记录的信息
     * 日伞下新增玩家、今日新增代理
     * 直属代理、直属玩家
     * 伞下玩家消耗、
     */

    /**
     * 根据台费计算每个用户每天的消耗
     * 5分钟执行一次、表取3分钟之前的数据
     */
    public function actionUserConsume($start_time = '', $end_time = '')
    {
        $start_time = $start_time ? : time() - 180;
        $end_time = $end_time ? : time();
        $suffix = date('Ymd', $start_time);

        $table_name = 't_gold_record__' . $suffix;

        $data = (new Query())
            ->select('*')
            ->from($table_name)
            ->where(['status' => GoldRecord::ORDER_UNFINISHED])
            ->all();

//        存储redis信息、
        $redis = Yii::$app->redis;
        $day_consume_key = RedisKey::INF_DAY_CONSUME . $suffix;
        $week_suffix = date('Ymd',strtotime(date('Y-m-d', $start_time)) - date('w',strtotime(date('Y-m-d', $start_time - 86400))) * 86400);
        $week_consume_key = RedisKey::INF_WEEK_CONSUME . $week_suffix;
        $all_consume_key = RedisKey::INF_ALL_CONSUME;
        $db = Yii::$app->db;

        foreach ($data as $v) {
//            更新日/周/所有单人消耗
            $res1 = $redis->hincrby($day_consume_key, $v['player_id'], $v['num']);
            $res2 = $redis->hincrby($week_consume_key, $v['player_id'], $v['num']);
            $res3 = $redis->hincrby($all_consume_key, $v['player_id'], $v['num']);

//            更新上级伞下消耗
            $res4 = $this->updateUnderConsume($v['player_id'], $v['num'], $suffix, $week_suffix);

            if ($res1 && $res2 && $res3 && $res4) {
                $db->createCommand()->update($table_name, [
                    'status' => GoldRecord::ORDER_FINISHED,
                    'update_time' => time(),
                ], "id = {$v['id']}")
                    ->execute();
            } else {
                Yii::info(print_r($v, 1), '更新消耗失败');
            }
        }
    }

    /**
     * 更新伞下消耗
     */
    public function updateUnderConsume($uid, $num, $suffix, $week_suffix)
    {
        $redis = Yii::$app->redis;
        $parent_id = $redis->zscore(RedisKey::INF_AGENT_RELATION, $uid);

        if (!empty($parent_id) && $parent_id != 999) {
            $res1 = $redis->hincrby(RedisKey::INF_UNDER_DAY_CONSUME . $suffix, $parent_id, $num);
            $res2 = $redis->hincrby(RedisKey::INF_UNDER_WEEK_CONSUME . $week_suffix, $parent_id, $num);
            $res3 = $redis->hincrby(RedisKey::INF_UNDER_ALL_CONSUME, $parent_id, $num);

            $this->updateUnderConsume($parent_id, $num, $suffix, $week_suffix);
        }

        return 1;
    }

    /**
     * 计算每周代理等级
     */
    public function actionAgentLevel($start_time = '', $end_time = '')
    {
        $start_time = $start_time ? : time() - 86400;
        $week_suffix = date('Ymd',strtotime(date('Y-m-d', $start_time)) - date('w',strtotime(date('Y-m-d', $start_time - 86400))) * 86400);
//        定代理等级
//        $key = 'inf_week_consume_20181126';
        $key = RedisKey::INF_UNDER_WEEK_CONSUME . $week_suffix;
        $redis = Yii::$app->redis;
        $data = $redis->hgetall($key);
        $player_list = $redis->hkeys($key);

        foreach ($player_list as $player_id) {
            $consume = $redis->hget($key, $player_id);
            $ratio = (new Query())
                ->select('ratio')
                ->from('conf_rebate_ratio')
//                ->where(['and', "min <= '{$consume}'", "max > '{$consume}'"])
                    ->where(['and', "'{$consume}' >= min", "'{$consume}' < max"])
                ->scalar();

            $redis->hset(RedisKey::INF_LEVEL . $week_suffix, $player_id, $ratio);
        }
    }

    const REBATE_RATIO = 110;

    /**
     * 计算代理消耗
     */
    public function actionRebate($start_time = '')
    {
        $start_time = $start_time ? : time() - 86400;
        $redis = Yii::$app->redis;
//        1、获取所有返利比例
        $week_suffix = date('Ymd',strtotime(date('Y-m-d', $start_time)) - date('w',strtotime(date('Y-m-d', $start_time - 86400))) * 86400);
        $date = date('Y-m-d', strtotime($week_suffix));
        $key = RedisKey::INF_LEVEL . $week_suffix;
        $level = $redis->hgetall($key);
        $user_list = $this->getHgetall($level);
        $t = date('Y-m-d H:i:s');

//        2、计算所有直属玩家代理的返利
        foreach ($user_list as $k => $v) {
            $member = $redis->zrangebyscore(RedisKey::INF_AGENT_RELATION, $k, $k);

            if (!empty($member)) {
                foreach ($member as $vv) {
                    $consume = $redis->hget(RedisKey::INF_WEEK_CONSUME . $week_suffix, $vv);
                    $data[] = $consume ? : 0;
                    $is_agent = $redis->hexists(RedisKey::INF_AGNET, $vv);

                    LogRebate::addLog([
                        'parent_id' => $k,
                        'player_id' => $vv,
                        'consume' => $consume ? : 0,
                        'ratio' => $v ? : 0,
                        'rebate' => ($consume * $v),
                        'type' => LogRebate::REBATE_SUB,
                        'rebate_week' => $date,
                        'create_time' => $t,
                        'is_agent' => $is_agent,
                    ]);
                }
                $a = array_sum($data)  ? : 0;
                $data = [];
            }

//            插入汇总返利信息
            LogRebate::addLog([
                'parent_id' => $k,
                'player_id' => 0,
                'consume' => $a ? : 0,
                'ratio' => $v ? : 0,
                'rebate' => ($a * $v),
                'type' => LogRebate::REBATE_SUB,
                'rebate_week' => $date,
                'create_time' => $t,
                'is_agent' => 0,
            ]);
        }

//        3、计算代理等级差
        foreach ($user_list as $k => $v) {
            $member_list = $redis->zrangebyscore(RedisKey::INF_AGENT_RELATION, $k, $k);
            if (!empty($member_list)) {
                foreach ($member_list as $vv) {
//                    $is_agent = $redis->hexists(RedisKey::INF_AGNET, $vv);
                    $redis = Yii::$app->redis_6;
                    $is_agent = $redis->sismember('relation:daili_list', $vv);
                    if ($is_agent) {
                        $redis = Yii::$app->redis;
                        $player_level = $redis->hget(RedisKey::INF_LEVEL . $week_suffix, $vv);
                        $diff = $v - $player_level;

                        $consume = $redis->hget(RedisKey::INF_UNDER_WEEK_CONSUME . $week_suffix, $vv);
                        $data[] = $consume ? : 0;

                        LogRebate::addLog([
                            'parent_id' => $k,
                            'player_id' => $vv,
                            'consume' => $consume ? : 0,
                            'ratio' => $diff,
                            'rebate' => ($consume * $diff),
                            'type' => LogRebate::REBATE_UNDER,
                            'rebate_week' => $date,
                            'create_time' => $t,
                            'is_agent' => $is_agent,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * 更新代理金额
     * 每周执行一次
     */
    public function actionUpdateAgent($start_time = '')
    {
        $db = Yii::$app->db;
        $start_time = $start_time ? : time() - 86400;
        $week_suffix = date('Y-m-d',strtotime(date('Y-m-d', $start_time)) - date('w',strtotime(date('Y-m-d', $start_time - 86400))) * 86400);

//        直属代理返利
        $data = (new Query())
            ->select(['parent_id', 'player_id', 'rebate', 'rebate_week'])
            ->from('log_rebate')
            ->where(['and', 'type = 1', 'player_id = 0', "rebate_week = '{$week_suffix}'", 'parent_id != 30773780'])
            ->all();
//        ->createCommand()->sql;
//        echo $data;
//        die();


        foreach ($data as $k => $v) {
//            var_dump($v);
            $t = date('Y-m-d H:i:s');
            $db->createCommand($sql = "INSERT INTO log_update_agent VALUES(NULL, '{$v['parent_id']}', '{$v['player_id']}', '{$v['rebate']}', '{$v['rebate_week']}', '{$t}')")->execute();
//            echo $sql;

            $db->createCommand("UPDATE t_daili_player SET pay_back_gold = pay_back_gold + '{$v['rebate']}', all_pay_back_gold = all_pay_back_gold + '{$v['rebate']}' WHERE player_id = '{$v['parent_id']}'")->execute();
        }
    }

    public function actionUpdateAgentUnder($start_time = '')
    {
        $db = Yii::$app->db;
        $start_time = $start_time ? : time() - 86400;
        $week_suffix = date('Y-m-d',strtotime(date('Y-m-d', $start_time)) - date('w',strtotime(date('Y-m-d', $start_time - 86400))) * 86400);

        $data = (new Query())
            ->select(['parent_id', 'player_id', 'rebate', 'rebate_week'])
            ->from('log_rebate')
            ->where(['and', 'type = 2', "rebate_week = '{$week_suffix}'", "rebate != 0.00", 'parent_id != 30773780'])
            ->all();
//        ->createCommand()->sql;

        foreach ($data as $k => $v) {
            $t = date('Y-m-d H:i:s');
            $db->createCommand("INSERT INTO log_update_agent VALUES(NULL, '{$v['parent_id']}', '{$v['player_id']}', '{$v['rebate']}', '{$v['rebate_week']}', '{$t}')")->execute();
            $db->createCommand("UPDATE t_daili_player SET pay_back_gold = pay_back_gold + '{$v['rebate']}', all_pay_back_gold = all_pay_back_gold + '{$v['rebate']}' WHERE player_id = '{$v['parent_id']}'")->execute();
        }
    }


/**
     * 返回hgetall的数组格式
     */
    private function getHgetall(array $arr)
    {
        foreach ($arr as $k => $v) {
            if ($k % 2 == 0) {
                $data[$v] = $arr[$k + 1];
            }
        }

        return $data;
    }

    /**
     * 矫正代理返利是否争取数据
     */
    public function actionT1()
    {
        $redis = Yii::$app->redis;
        $redis = Yii::$app->redis_6;
        $list = $redis->smembers('daili:all_under_daili_30118876');

        $l = implode(',', $list);
        $db = Yii::$app->db;
        $sql = "SELECT sum(rebate) FROM log_rebate where parent_id in ({$l}) AND rebate_week = '2019-01-14' and player_id != 0";
        $sum = $db->createCommand($sql)->queryScalar();
        var_dump($sum);
    }


    private function t2(array $player_id)
    {
        $redis = Yii::$app->redis_6;
        $list = $redis->smembers('daili:all_under_daili_30820923');
    }
}
