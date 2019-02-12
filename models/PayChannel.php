<?php
/**
 * User: jw
 * Date: 2018/8/28 0028
 */
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class PayChannel extends ActiveRecord
{
    public static function tableName()
    {
        return 'pay_channel';
    }

    public function rules()
    {
        return [
            [['channel_code','appid','appkey','remark'],'safe']
        ];
    }
}