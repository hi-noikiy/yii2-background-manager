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

class OperUserDay extends ActiveRecord
{
    public static function tableName()
    {
        return 't_oper_user_day';
    }

    public function getAll($con,$page,$limit,$fields="*"){
        $data = (new Query())
            ->select($fields)
            ->from(self::tableName())
            ->where($con)
            ->limit($limit)
            ->orderBy('create_time desc')
            ->offset(($page - 1) * $limit)
            ->all();

        return $data;
    }

    public function getAllCount($con){
        $count = (new Query())
            ->select("id")
            ->from(self::tableName())
            ->where($con)
            ->count();

        return $count;
    }




}