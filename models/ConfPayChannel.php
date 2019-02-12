<?php
/**
 * User: SeaReef
 * Date: 2018/9/26 20:50
 *
 * 支付渠道信息
 */
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class ConfPayChannel extends ActiveRecord
{
    public static function tableName()
    {
        return 'conf_pay_channel';
    }

    /**
     * 根据appid获取渠道信息
     */
    public static function getInfoById($appid)
    {
        return self::find()
            ->select('*')
            ->where(['appid' => $appid])
            ->one();
    }
}