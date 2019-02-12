<?php
/**
 * User: SeaReef
 * Date: 2018/6/12 10:51
 */
namespace app\models;

use yii\db\ActiveRecord;

class RechargeGive extends ActiveRecord
{
    public static function tableName()
    {
        return 't_recharge_give';
    }
}