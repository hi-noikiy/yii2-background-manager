<?php
/**
 * User: SeaReef
 * Date: 2018/7/17 13:44
 *
 * 在线统计
 */
namespace app\commands;

use app\common\RedisKey;
use app\models\Channel;
use Yii;
use yii\console\Controller;

class OnlineController extends BaseController
{
    /**
     * 实时在线统计、没5分钟统计一次
     */
    public function actionRealOnline($channel_id = 1)
    {
        $redis = Yii::$app->redis_1;
        $num = 0;
        if($channel_id == 1){
            $num = $redis->hlen(RedisKey::REAL_ONLINE);
        }else{
            $channel_under_list = $this->getChannelUnderList($channel_id);
            $redisAllNum = $redis->hkeys(RedisKey::REAL_ONLINE);
            $num = count(array_intersect($redisAllNum,$channel_under_list));
        }

        $info = Yii::$app->db->createCommand()->insert('t_real_online', [
            'id' => NULL,
            'channel_id' => $channel_id,
            'gid' => 1,
            'num' => $num,
            'stat_time' => date('Y-m-d H:i:s', time()),
        ])->execute();

        Yii::info('统计在线人数'. print_r(['id' => NULL,
                'channel_id' => $channel_id,
                'gid' => 1,
                'num' => $num,
                'stat_time' => date('Y-m-d H:i:s', time())], 1));
    }

    public function actionPollChannelRealOnline(){
        $channelModel = new Channel();
        $channelList = $channelModel->getDataByCon($con=[]);

        foreach ($channelList as $key=>$val){
            $this->actionRealOnline($val['channel_id']);
        }
    }

    /**
     * 在线分析
     */
    public function actionAnalysisOnline($start_time = '', $end_time = '')
    {
        $start_time = $start_time ? : date('Y-m-d 00:00:00', time() - 86400);
        $end_time = $end_time ? : date('Y-m-d 00:00:00', time());
        $stat_date = date('Y-m-d', strtotime($start_time));
        $db = Yii::$app->db;

        $online = $db->createCommand($sql = "SELECT `channel_id`, MAX(`num`) AS `max_online`, AVG(`num`) AS `avg_online` FROM t_real_online WHERE stat_time >= '{$start_time}' AND stat_time < '{$end_time}' GROUP BY channel_id")->queryOne();
        if (!$online) {
            $online = [
                'channel_id' => 1,
                'avg_online' => 0,
                'max_online' => 0,
            ];
        }

        $time = $db->createCommand("SELECT `channel_id`, MAX(`online_time`) AS `max_time`, AVG(`online_time`) AS `avg_time` FROM login_db.t_login WHERE create_time >= '{$start_time}' AND create_time < '{$end_time}' AND type = 2 GROUP BY channel_id")->queryOne();

        $info = $db->createCommand()->insert('stat_online', [
            'channel_id' => $online['channel_id'] ? : 1,
            'stat_date' => $stat_date,
            'max_online' => $online['max_online'] ? : 0,
            'avg_online' => round($online['avg_online']) ? : 0,
            'max_time' => round($time['max_time'] / 60000) ? : 0,
            'avg_time' => round($time['avg_time'] / 60000) ? : 0,
        ])->execute();
    }
}