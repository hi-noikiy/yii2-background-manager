<?php
/**
 * User: jw
 * Date: 2018/9/5 0005
 */
namespace app\models;

use yii;
use yii\db\ActiveRecord;

class GeneralRobotGoldPool extends ActiveRecord
{
    public static function tableName()
    {
        return 't_general_robot_gold_pool';
    }

    public function rules()
    {
        return [
            [['gid', 'now_gold_pool','total_gold_pool', 'up_limit', 'down_limit', 'character_id',
                'create_time', 'uid'], 'safe']
        ];
    }

    /**
     * 实时机器人元宝库额度
     */
    public function generalRobotNowGoldPool()
    {
        $db = Yii::$app->db;
        $redis = Yii::$app->redis;
        $now_gold_pool = $db->createCommand('select now_gold_pool from t_general_robot_gold_pool');
        $today_gold_pool = $redis->get(Yii::$app->params['redisKeys']['general_robot_now_gold_pool']);
        return $now_gold_pool-$today_gold_pool;
    }
}