<?php
/**
 * User: jw
 * Date: 2018/8/10 0010
 */
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class GameJump extends ActiveRecord
{
    public static function tableName()
    {
        return 'conf_gamejump';
    }
}