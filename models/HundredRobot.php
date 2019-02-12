<?php
/**
 * User: jw
 * Date: 2018/8/21 0021
 */
namespace app\models;

use yii;
use yii\db\ActiveRecord;

class HundredRobot extends ActiveRecord
{
    public static function tableName()
    {
        return 't_hundred_robot';
    }

    public function rules()
    {
        return [
            [['player_id'], 'required'],
            [['player_id','gid','is_system'], 'integer'],
            [['is_system'], 'default','value' => 0],
            [['player_id'], 'unique'],
            [['created_time','updated_time'], 'default','value'=>date('Y-m-d H:i:s',time())],
            [['nickname','img_url','ip','init_yuanbao','yuanbao_range'],'safe']
        ];
    }


}