<?php
/**
 * User: jw
 * Date: 2018/8/10 0010
 */
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Broadcast extends ActiveRecord
{
    public static function tableName()
    {
        return 't_lunbo';
    }

    public function rules()
    {
        return [
            [['img_url','jump_url','jump_type','info'],'safe']
        ];
    }
}