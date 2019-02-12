<?php

use yii\db\Migration;

/**
 * Class m181202_091605_create_table_t_pay_order
 */
class m181202_091605_create_table_t_pay_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_pay_order', [
            'ID' => \yii\db\Schema::TYPE_UPK,
            'ORDER' => $this->string()->notNull(),
            'PLAYER_INDEX' => $this->string()->notNull(),
            'WX_MP' => $this->string()->notNull()->defaultValue(0),
            'WX_OPENID' => $this->string(55)->notNull()->defaultValue(''),
            'BANK_ACCOUNT' => $this->string()->notNull(),
            'TRUE_NAME' => $this->string()->notNull(),
            'PAY_MONEY' => $this->integer()->notNull()->comment('支付金额'),
            'PAY_FEE' => $this->integer()->notNull()->comment('手续费'),
            'PAY_STATUS' => $this->integer()->notNull()->defaultValue(0)->comment('提现状态'),
            'API_CODE' => $this->string()->notNull()->defaultValue(''),
            'API_DESC' => $this->string()->notNull()->defaultValue(''),
            'CREATE_TIME' => $this->integer()->notNull()->defaultValue(0),
            'UPDATE_TIME' => $this->integer()->notNull()->defaultValue(0),
            'REMARK' => $this->string()->notNull()->defaultValue(''),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_pay_order');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_091605_create_table_t_pay_order cannot be reverted.\n";

        return false;
    }
    */
}
