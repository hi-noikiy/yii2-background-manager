<?php

use yii\db\Migration;

/**
 * Class m181202_071903_create_table_t_exchange_record
 */
class m181202_071903_create_table_t_exchange_record extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_exchange_record', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'order_id' => $this->string(200)->notNull()->defaultValue('')->comment('直兑订单ID'),
            'channel_id' => $this->string(50)->defaultValue('')->comment('渠道订单ID'),
            'player_id' => $this->integer()->unsigned()->defaultValue(0)->comment('玩家ID'),
            'type' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('直兑类型、1支付宝、2银行卡'),
            'code' => $this->string(100)->notNull()->defaultValue('')->comment('直兑账号'),
            'amount' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('直兑金额'),
            'service_charge' => $this->decimal(10,2)->notNull()->defaultValue(0.00)->comment('手续费'),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('订单状态、0初始状态、1成功、2直兑中、3、直兑失败'),
            'return_receipt' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('直兑失败、回执玩家金币状态、0无异常、1回执成功、2回执失败'),
            'create_time' => $this->dateTime()->notNull()->defaultValue('1000-01-01 00:00:00')->comment('订单创建时间'),
            'finish_time' => $this->dateTime()->notNull()->defaultValue('1000-01-01 00:00:00')->comment('订单完成时间'),
            'memo' => $this->string(200)->notNull()->defaultValue('')->comment('备注'),
            'share_url' => $this->string()->notNull()->defaultValue('')->comment('微信直兑分享地址'),
            'redis_status' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('是否已经存入redis、0否、1是'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_exchange_record');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_071903_create_table_t_exchange_record cannot be reverted.\n";

        return false;
    }
    */
}
