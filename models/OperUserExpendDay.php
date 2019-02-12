<?php
/**
 * User: SeaReef
 * Date: 2018/7/12 21:25
 *
 * 代理控制器
 */
namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Query;

class OperUserExpendDay extends ActiveRecord
{
    public static function tableName()
    {
        return 't_oper_user_expend_day';
    }

    /**
     * 获取玩家消耗
     *
     * @param $con
     * @return array|bool
     */
    public function getDataByPlayerId($con=array(),$type = 1){
        $group = "PLAYER_INDEX";
        if($type != 1){
            $group = '';
        }
        $data = (new Query())
            ->select("sum(NUM) as num")
            ->from(self::tableName())
            ->where($con)
            ->groupBy($group)
            ->one();

        return $data['num'];
    }

}