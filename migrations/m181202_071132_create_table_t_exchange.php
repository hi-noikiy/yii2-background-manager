<?php

use yii\db\Migration;

/**
 * Class m181202_071132_create_table_t_exchange
 */
class m181202_071132_create_table_t_exchange extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_exchange', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'player_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('玩家ID'),
            'name' => $this->string(50)->notNull()->defaultValue('')->comment('玩家姓名'),
            'type' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('账号类型、1支付宝、2银行卡、3微信'),
            'code' => $this->string(100)->notNull()->defaultValue('')->comment('账号'),
            'bank_code' => $this->string(100)->notNull()->defaultValue('')->comment('银行卡编号'),
            'code_location' => $this->string(100)->notNull()->defaultValue('')->comment('银行卡归属地'),
            'code_type' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('银行卡类型、0储蓄卡、1信用卡'),
            'account_name' => $this->string(100)->notNull()->defaultValue('')->comment('账号名称、支付宝或银行卡名称'),
            'create_time' => $this->dateTime()->notNull()->defaultValue('1000-01-01 00:00:00')->comment('绑定时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_exchange');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_071132_create_table_t_exchange cannot be reverted.\n";

        return false;
    }
    */
}
