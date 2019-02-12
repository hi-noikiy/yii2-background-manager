<?php
/**
 * User: SeaReef
 * Date: 2018/7/9 13:59
 *
 * 代理关系模型
 */
namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Query;

class PlayerMember extends CommonModel
{
    public static function tableName()
    {
        return 't_player_member';
    }

    /**
     * 获取玩家上级id
     */
    public static function getFatherId($player_id)
    {
//        return self::find()->select('PLAYER_INDEX')->where(['MEMBER_INDEX' => $player_id])->scalar();
        return self::find()->select('parent_id')->where(['player_id' => $player_id])->scalar();
    }

    /**
     * 获取祖父id
     */
    public static function getGfatherId($player_id)
    {
        if ($player_id == 999) {
            return 0;
        }
        $father = self::getFatherId($player_id);
        return self::getFatherId($father);
    }

    /**
     * 获取祖祖父id
     */
    public static function getGgfatherId($player_id)
    {
        if ($player_id == 999) {
            return 0;
        }
        $gfather = self::getGfatherId($player_id);
        return self::getFatherId($gfather);
    }

    /*
     * 根据上级id获取下级
     *
     */
    public function getDataByCon($con,$fields="*",$type=1){
        return $this->commonSelect(self::tableName(),$con,$fields,$type);
    }

    /*
     * 根据上级id分页获取下级
     *
     */
    public function getDataByConByPage($con,$page,$limit,$fields="*"){
        $data = (new Query())
            ->select($fields)
            ->from(self::tableName())
            ->where($con)
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();

        return $data;
    }

    /**
     * 获取下级数量
     *
     */
    public function getLowerNum($playerId){
        $num = (new Query())
            ->select("count(*)")
            ->from(self::tableName())
            ->where(['parent_id'=>$playerId])
            ->scalar();

        return $num;
    }

    /**
     * 获取顶级id
     */
    public function getTopId($playerId){
        $topId = $playerId;
        $isTue = true;
        while($isTue){
            $agentId = (new Query())
                ->select("parent_id")
                ->from(self::tableName())
                ->where(['player_id'=>$topId])
                ->scalar();

            if($agentId){
                if($agentId == '999'){
                    $isTue = false;
                }else{
                    $topId = $agentId;
                }
            }else{
                $isTue = false;
            }
        }

        return $topId;
    }

    public function getDateLowerList($playerId,$date){
        $tomorrow = date("Y-m-d",strtotime($date)+86400);
        $db = \Yii::$app->db;
        $lowerList = $db->createCommand("select player_id from t_player_member where parent_id={$playerId} AND bind_time > '{$date}' AND bind_time < '{$tomorrow}'")->queryAll();

        return $lowerList;
    }
}