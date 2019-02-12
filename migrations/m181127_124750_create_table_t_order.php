<?php

use yii\db\Migration;

/**
 * Class m181127_124750_create_table_t_order
 */
class m181127_124750_create_table_t_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_order', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'channel_id' => $this->string(20)->notNull()->comment('渠道ID'),
            'order_id' => $this->string(30)->notNull()->comment('订单ID'),
            'channel_oid' => $this->string(30)->notNull()->defaultValue('')->comment('渠道订单号'),
            'player_id' => $this->integer()->unsigned()->notNull()->comment('玩家ID'),
            'nickname' => $this->string(30)->notNull()->comment('玩家昵称'),
            'player_create' => $this->dateTime()->notNull()->comment('玩家创角时间'),
            'goods_id' => $this->integer()->unsigned()->notNull()->comment('商品ID'),
            'goods_type' => $this->tinyInteger()->unsigned()->notNull()->comment('商品类型'),
            'goods_num' => $this->integer()->unsigned()->notNull()->comment('商品数量'),
            'goods_price' => $this->decimal(10,2)->unsigned()->notNull()->comment('商品价格'),
            'pay_channel' => $this->string(20)->notNull()->comment('支付渠道'),
            'pay_type' => $this->string(20)->notNull()->comment('支付方式、alipay/wechat/unionpay'),
            'pay_terminal' => $this->string(20)->notNull()->comment('支付机型、ios/android'),
            'status' => $this->tinyInteger()->unsigned()->notNull()->comment('状态'),
            'create_time' => $this->dateTime()->notNull()->comment('下单时间'),
            'pay_time' => $this->dateTime()->notNull()->defaultValue('1000-01-01 00:00:00')->comment('渠道通知时间'),
            'finish_time' => $this->dateTime()->notNull()->defaultValue('1000-01-01 00:00:00')->comment('完成时间'),
            'UNIQUE KEY `order_id_ukey`(`order_id`)',
            'KEY `create_time_key` (`create_time`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_order');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181127_124750_create_table_t_order cannot be reverted.\n";

        return false;
    }
    */
}
