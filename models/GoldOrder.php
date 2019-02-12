<?php
/**
 * User: jw
 * Date: 2018/8/27 0027
 */

namespace app\models;

use yii\db\ActiveRecord;

class GoldOrder extends ActiveRecord
{
    public static function tableName()
    {
        return 'lobby_daili.t_order';
    }
}