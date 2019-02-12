<?php
/**
 * User: jw
 * Date: 2018/7/23
 */
namespace app\models;

use yii;
use yii\db\ActiveRecord;

class ServiceRecharge extends ActiveRecord
{
    public static function tableName()
    {
        return 't_service_recharge_log';
    }

    public function rules()
    {
        return [
            [['player_id','gold_num','gold_type','use_type','gid'], 'required'],
            [['player_id', 'gold_num', 'gold_type', 'use_type','gid'], 'integer'],
            ['money_num','double','min' => 0],
            ['time','safe'],
            ['content', 'string'],
        ];
    }

    /**
     * 获取玩家信息
     * @param $player_id 玩家ID
     * @return mixed
     */
    public function getPlayer($player_id)
    {
        return Yii::$app->login_db->createCommand('select * from login_db.t_lobby_player where u_id = :u_id')
        ->bindValue(':u_id',$player_id)
        ->queryOne();
    }
}