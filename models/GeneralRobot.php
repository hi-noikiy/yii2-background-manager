<?php
/**
 * User: jw
 * Date: 2018/8/28 0028
 */
namespace app\models;

use yii;
use yii\db\ActiveRecord;

class GeneralRobot extends ActiveRecord
{
    public static function tableName()
    {
        return 't_general_robot';
    }

    public function rules()
    {
        return [
            [['player_id','nickname','character_id','bet','take_coin','gid'],'required'],
            [['img_url','ip','latitude','longitude'],'safe'],
        ];
    }

    /**
     * 获取redis中通用机器人的信息
     * @return array
     */
    public function robotInfo()
    {
        $game_dev_redis = Yii::$app->game_dev_redis;
        $rows = $game_dev_redis->hgetall(Yii::$app->params['redisKeys']['general_robot_config']);
        foreach ($rows as $k => $v) {
            if ($k % 2 == 0) {
                $key[] = $v;
            } else {
                $value[] = json_decode($v,true);
            }

        }
        return array_combine($key,$value);
    }
}