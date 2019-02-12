<?php
/**
 * User: SeaReef
 * Date: 2018/7/4 11:34
 */
namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Query;

class LobbyPlayer extends CommonModel
{
    public static function tableName()
    {
        return 't_lobby_player';
    }

    public static function getDb()
    {
        return Yii::$app->login_db;
    }

    /**
     * 机器人更新信息
     * @param $data
     * @param $id uid
     * @return int
     * @throws \yii\db\Exception
     */
    public function updateRobotInfo($data,$id)
    {
        $result = self::getDb()->createCommand()->update(self::tableName(),$data,'u_id='.$id)->execute();
        return $result;
    }

    /**
     * 验证订单玩家信息
     */
    public static function checkUser($uid)
    {
        return self::find()
            ->select(['nickname' => 'weixin_nickname', 'player_id' => 'u_id', 'reg_time'])
            ->where(['u_id' => $uid])
            ->asArray()
            ->one();
    }

    /**
     * 获取玩家信息
     * @param $player_id -玩家ID
     * @return mixed
     */
    public function getPlayer($player_id,$fields='*')
    {
        $tableName = self::tableName();
        return Yii::$app->login_db->createCommand("SELECT ".$fields." FROM login_db.".$tableName." WHERE u_id=".$player_id)
            ->queryOne();
    }

    /**
     * 根据条件获取用户信息
     *
     * @param $con
     * @param string $fields
     * @param int $type 查询类型 1查询所有 2查询一条 3标量查询
     * @return bool|mixed
     */
    public function getPlayerInfo($con,$fields='*',$type=1)
    {
        return $this->commonSelect('login_db.'.self::tableName(),$con,$fields,$type);
    }

    /**
     * 获取玩家昵称
     */
    public static function getNickname($player_id)
    {
        return self::find()->select('weixin_nickname')->from('login_db.t_lobby_player')->where(['u_id' => $player_id])->scalar();
    }
}