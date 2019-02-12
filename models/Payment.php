<?php
/**
 * User: SeaReef
 * Date: 2018/9/26 19:38
 *
 * 支付渠道模型
 */
namespace app\models;

use app\common\Tool;
use Yii;
use yii\db\ActiveRecord;

class Payment extends ActiveRecord
{
    public static function tableName()
    {
        return 'conf_payment';
    }

    /**
     * 获取支付渠道信息
     */
    public static function getPayChannel($pay_type, $uid)
    {
//        检测充值方式白名单
        $res = Tool::checkPaymentWhite($uid);

        if ($res) {
            $payment_id = self::find()
                ->where(['pay_name' => $pay_type])
                ->asArray()
                ->one();
        } else {
            $payment_id = self::find()
                ->where(['pay_name' => $pay_type, 'status' => 1])
                ->asArray()
                ->one();
        }

        if ($payment_id) {
            $pay_channel = self::getDb()->createCommand("SELECT * FROM conf_payment_channel WHERE payment = '{$payment_id['id']}' AND master = 1")->queryOne();
            if ($pay_channel) {
                return self::getDb()->createCommand("SELECT * FROM conf_pay_channel WHERE id = '{$pay_channel['pay_channel']}'")->queryOne();
            }
        }
    }

    /**
     * 轮询算法获取渠道信息
     */
    public function getPayChannelByPolling($pay_type, $uid)
    {

    }
}