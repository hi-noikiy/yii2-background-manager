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

class StatConsume extends ActiveRecord
{
    public static function tableName()
    {
        return 'stat_consume';
    }

    public function getData($where){
        $rows = (new Query())
            ->select('*')
            ->from(self::tableName())
            ->where($where)
            ->orderBy('stat_date')
            ->all();

        return $rows;
    }

    /**
     * 根据条件获取一条信息
     *
     * @param $where
     * @return array|bool
     */
    public function getOne($where){
        $rows = (new Query())
            ->select('*')
            ->from(self::tableName())
            ->where($where)
            ->orderBy('stat_date')
            ->one();

        return $rows;
    }


}