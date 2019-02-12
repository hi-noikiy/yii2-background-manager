<?php
/**
 * User: SeaReef
 * Date: 2018/9/25 23:53
 *
 * 订单模型
 */
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

class Order extends ActiveRecord
{
    /**
     * 订单已完成
     */
    const ORDER_FINISHED = 1;

    /**
     * 订单未完成
     */
    const ORDER_UNDONE = 0;

    public static function tableName()
    {
        return 't_order';
    }

    public function rules()
    {
        return [
            [['channel_id', 'order_id', 'player_id', 'nickname', 'player_create', 'goods_id', 'goods_type', 'goods_num', 'goods_price', 'pay_channel', 'pay_type', 'pay_terminal', 'status', 'create_time'], 'required']
        ];
    }

    /**
     * 生成唯一订单号
     */
    public static function generateOrderNum()
    {
        return date('YmdHis'). str_pad(mt_rand(1,99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * 插入临时订单、添加模型验证操作
     */
    public function addOrder($data)
    {
        $this->attributes = $data;
        return $this->save();
    }

    /**
     * 更新订单状态
     */
    public static function updateOrderStatus($status, $id)
    {
        $db = Yii::$app->db;
        $sql = "UPDATE t_order SET status = {$status} WHERE id = {$id}";
        return $db->createCommand($sql)->execute();
    }

    public function getOrderInfo($orderId){
        return self::findOne(['order_id'=>$orderId])->toArray();
    }
}
