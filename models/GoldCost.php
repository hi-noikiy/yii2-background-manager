<?php
/**
 * User: SeaReef
 * Date: 2018/6/20 14:51
 */
namespace app\models;

use yii\db\ActiveRecord;

class GoldCost extends ActiveRecord
{
    public static function tableName()
    {
        return 't_gold_cost';
    }

    public function addOrder()
    {

    }
}