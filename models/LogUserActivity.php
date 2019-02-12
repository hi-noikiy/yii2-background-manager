<?php
/**
 * User: SeaReef
 * Date: 2018/9/26 17:36
 *
 * 活动日志模型
 */
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

class LogUserActivity extends ActiveRecord
{
    /**
     * 活动操作类型、1领取、2点击
     */
    const OPERATE_TYPE_RECEIVE = 1;

    const OPERATE_TYPE_CLICK = 2;

    /**
     * 操作完成情况
     */
    const OPERATE_FINISHED = 1;

    const OPERATE_NO_FINISHED = 0;

    public static function tableName()
    {
        return 'log_user_activity';
    }

    /**
     * @params $player_id、玩家id
     * @params $activity_id、活动id
     */
    public static function isReceive($player_id, $activity_id)
    {
        return self::find()
            ->select('is_operate')
            ->where(['player_id' => $player_id, 'activity_id' => $activity_id, 'operate_type' => self::OPERATE_TYPE_RECEIVE, 'is_operate' => self::OPERATE_FINISHED])
            ->scalar();
    }

    /**
     * 插入活动记录
     */
    public static function saveActivityLog($uid, $activity_id, $operate_type)
    {
        $db = Yii::$app->db;
        $t = date('Y-m-d H:i:s');
        $res  = $db->createCommand("INSERT INTO log_user_activity VALUES(NULL, '{$uid}', '{$activity_id}', '{$operate_type}', 1, 1, '{$t}', '{$t}') ON DUPLICATE KEY UPDATE operate_count = operate_count + 1, last_operate = '{$t}'")->execute();

        return $res;
    }
}