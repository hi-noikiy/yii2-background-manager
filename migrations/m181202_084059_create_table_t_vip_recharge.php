<?php

use yii\db\Migration;

/**
 * Class m181202_084059_create_table_t_vip_recharge
 */
class m181202_084059_create_table_t_vip_recharge extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_vip_recharge', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'nickname' => $this->string(30)->notNull()->defaultValue('')->comment('昵称'),
            'type' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('账户类型、1支付宝、2银行卡'),
            'number' => $this->string(50)->notNull()->defaultValue(0)->comment('账号'),
            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('0不可用、1可用'),
            'create_time' => $this->dateTime()->comment('添加时间'),
            'update_time' => $this->dateTime()->comment('更新时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_vip_recharge');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_084059_create_table_t_vip_recharge cannot be reverted.\n";

        return false;
    }
    */
}
