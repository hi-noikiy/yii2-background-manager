<?php
/**
 * User: jw
 * Date: 2018/8/14 0014
 */
namespace app\models;

use yii;
use yii\db\ActiveRecord;

class Robot extends ActiveRecord
{
    public static function tableName()
    {
        return 't_robot_common';
    }

    public function rules()
    {
        return [
            [['player_id','nickname','img_url','ip','dizhu','xiedai','init_yuanbao'],'required'],
            ['player_id','unique'],
            ['player_id','match','pattern'=>'/^\d{8}/'],
            [['dizhu','dangqian'],'match','pattern'=>'/^\d+/'],
            ['dangqian','default','value'=>0],
            [['nickname','img_url'],'string'],
            //['ip','ip'],
            [['created_time','updated_time'],'default','value'=>date('Y-m-d H:i:s',time())],
            ['status','default','value'=>0]
        ];
    }

    /**
     * 加入机器人redis中
     * @param $id
     */
    public function insertRedis($id)
    {
        $row = Robot::findOne($id);
        $row = $row->attributes;

        $redis = Yii::$app->redis_2;
        $data = [
            'oid' => $row['id'],
            'nickname' => $row['nickname'],
            'photoUrl' => $row['img_url'],
            'ip' => $row['ip'],
            'character' => 1,//普通性格
            'gold' => $row['xiedai']
        ];
        $redis->lpush(Yii::$app->params['redisKeys']['robot_common_info'],json_encode($data));
    }

    /**
     * 全部机器人存入redis
     */
    public function updateRedis()
    {
        $rows = Yii::$app->db->createCommand('SELECT * FROM '.self::tableName().' WHERE status = 1 ORDER BY id')->queryAll();
        $redis = Yii::$app->redis_2;
        $redis->ltrim(Yii::$app->params['redisKeys']['robot_common_info'],1,0);
        //$db = Yii::$app->db;
        //$d = date('Ymd', time());
        //$record_person_table = "mdwl_activity.t_game_record_person_log" . $d;
        foreach ($rows as $val) {
            //$current = $db->createCommand("select player_gold_new from {$record_person_table} where player_id = {$val['player_id']} order by id desc")->queryScalar();
            $data = [
                'oid' => $val['id'],
                'id' => $val['player_id'],
                'name' => $val['nickname'],
                'photoUrl' => $val['img_url'],
                'ip' => $val['ip'],
                'character' => 1,//普通性格
                'gold' => $val['xiedai']
            ];
            $redis->lpush(Yii::$app->params['redisKeys']['robot_common_info'],json_encode($data));
        }
    }

    /**
     * 更新机器人时，更新user_info的redis信息
     */
    public function updateUserRedis($data)
    {
        $redis = Yii::$app->redis_1;
        $info = $redis->hget(Yii::$app->params['redisKeys']['user_info'],$data['player_id']);
        $info = json_decode($info,true);
        $info['weixinNickname'] = $data['nickname'];
        $info['headImg'] = $data['img_url'];
        $info['ip'] = $data['ip'];
        $redis->hset(Yii::$app->params['redisKeys']['user_info'],$data['player_id'],json_encode($info,JSON_UNESCAPED_UNICODE));
    }

    /**
     * 写入机器人初始元宝
     */
    public function saveRobotInitYuanbao($info)
    {
        $db = Yii::$app->db;
        //$d = date('Ymd', strtotime($info['created_time']));
        $robot_table = 'oss.t_robot_common';
        //$recharge_table = 'player_log.t_lobby_player_log__' . $d;
        $data_time = strtotime($info['created_time']);
        while ($data_time <= time()) {
            $recharge_table = 'player_log.t_lobby_player_log__' . date('Ymd', $data_time);
            $count = $db->createcommand("select `count` from {$recharge_table} where player_id like '%{$info['player_id']}' order by create_time asc")->queryScalar();
            if ($count) {
                $db->createcommand()->update($robot_table,['init_yuanbao'=>$count],"player_id = {$info['player_id']}")->execute();
                break;
            } else {
                $data_time += 86400;
            }
        }
    }
}