<?php
/**
 * User: SeaReef
 * Date: 2018/7/11 10:10
 */
namespace app\models;

use yii\db\ActiveRecord;

class Login extends ActiveRecord
{
    public static function tableName()
    {
        return 'log_login';
    }
}