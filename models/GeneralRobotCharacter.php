<?php
/**
 * User: jw
 * Date: 2018/8/28 0028
 */
namespace app\models;

use yii;
use yii\db\ActiveRecord;

class GeneralRobotCharacter extends ActiveRecord
{
    public static function tableName()
    {
        return 't_general_robot_character';
    }

    public function rules()
    {
        return [
            [['commont','timeInterval','setoutTime','leaveTableTime','leaveTableProp',
                'leaveTableMaxGameNum','sendTime','emojiProp','textProp','waitTime',
                'canWaitProp','downLine','upWinProp','upLine','downWinProp','seePoker',
                'openPokerTime','disPoker','followBet','addBet','pkPoker','qiangzhuang','yafen','ddzLevel'],'safe']
        ];
    }

    /**
     * 更新机器人性格到redis中
     */
    public function saveCharacterToRedis($data)
    {
        $new_data = $data;
        $keys_1 = ['seePoker','disPoker','followBet','addBet','pkPoker'];
        $keys_2 = ['qiangzhuang','yafen'];
        $new_data = $this->convertData($new_data,$data,$keys_1,"prop","rank");
        $new_data = $this->convertData($new_data,$data,$keys_2,"bet","rank");
        $new_data['characterId'] = $new_data['id'];
        unset($new_data['id']);
        Yii::$app->game_dev_redis->hset(Yii::$app->params['redisKeys']['general_robot_property'],$new_data['characterId'],json_encode($new_data,JSON_UNESCAPED_UNICODE));
    }

    public function convertData($new_data,$data,$key,$key1,$key2)
    {
        foreach ($key as $k => $val) {
            $data[$val] = json_decode($data[$val],true);
           // var_dump($data);exit;
            $new_data[$val] = [
                [
                    $key1=> isset($data[$val]['1'])?$data[$val]['1']:"",
                    $key2=> "1"
                ],
                [
                    $key1=> isset($data[$val]['2-3'])?$data[$val]['2-3']:"",
                    $key2=> "2-3"
                ],
                [
                    $key1=> isset($data[$val]['4-5'])?$data[$val]['4-5']:"",
                    $key2=>"4-5"
                ],
                [
                    $key1=> isset($data[$val]['6-9'])?$data[$val]['6-9']:"",
                    $key2=> "6-9"
                ]
            ];
        }
        return $new_data;
    }
}