<?php
/**
 * User: SeaReef
 * Date: 2018/7/12 21:25
 *
 * 代理控制器
 */
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

class PartherStat extends ActiveRecord
{
    public static function tableName()
    {
        return 't_parther_stat';
    }

    public function getAll($con,$page,$limit,$fields="*"){
        $data = (new Query())
            ->select($fields)
            ->from(self::tableName())
            ->where($con)
            ->limit($limit)
            ->orderBy('create_time desc')
            ->groupBy('create_time')
            ->offset(($page - 1) * $limit)
            ->all();


        return $data;
    }

    public function getAllCount($con){
        $count = (new Query())
            ->select("id")
            ->from(self::tableName())
            ->where($con)
            ->groupBy('create_time')
            ->count();

        return $count;
    }

    public function getInfoByUid($con,$fields="*"){
        $data = (new Query())
            ->select($fields)
            ->from(self::tableName())
            ->where($con)
            ->one();

        return $data;
    }

    public function getAllSum($where=array()){
        $data = (new Query())
            ->select("sum(system_recharge) as system_recharge,sum(consume) as consume,sum(deposit) as deposit,sum(agent_ti) as agent_ti")
            ->from(self::tableName())
            ->where($where)
            ->one();

        return $data;
    }


}