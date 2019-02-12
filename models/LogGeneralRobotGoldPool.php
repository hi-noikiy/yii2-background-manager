<?php
/**
 * User: jw
 * Date: 2018/9/5 0005
 */
namespace app\models;

use yii;
use yii\db\ActiveRecord;

class LogGeneralRobotGoldPool extends ActiveRecord
{
    public static function tableName()
    {
        return 'log_general_robot_gold_pool';
    }

    public function rules()
    {
        return [

        ];
    }

    /**
     * 通用机器人奖池操作记录
     */
    public function saveLogGeneralRobotGoldPool($data,$uid)
    {
        $data['uid'] = $uid;
        $data['create_time'] = date('Y-m-d H:i:s',time());
        Yii::$app->db->createCommand()->insert(self::tableName(),$data)->execute();
    }
}