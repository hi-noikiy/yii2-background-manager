<?php
/**
 * User: SeaReef
 * Date: 2018/6/12 10:14
 */
namespace app\models;

use yii\db\ActiveRecord;

class RechargeConf extends ActiveRecord
{
    public static function tableName()
    {
        return 'conf_recharge';
    }

    public static function checkGoods($goods_id)
    {
        return self::find()
            ->select('*')
            ->where(['id' => $goods_id])
            ->asArray()
            ->one();
    }


}