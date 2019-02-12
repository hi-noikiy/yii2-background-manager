<?php

use yii\db\Migration;

/**
 * Class m181129_041131_create_table_log_rebate
 */
class m181129_041131_create_table_log_rebate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_rebate', [
            'id' => \yii\db\Schema::TYPE_UBIGPK,
            'parent_id' => $this->integer()->unsigned()->notNull()->comment('上级ID'),
            'player_id' => $this->integer()->unsigned()->notNull()->comment('玩家ID'),
            'consume' => $this->integer()->unsigned()->notNull()->comment('玩家消耗'),
            'ratio' => $this->decimal(10,2)->unsigned()->notNull()->comment('返利比例'),
            'rebate' => $this->decimal(10,2)->unsigned()->notNull()->comment('返利金额'),
            'type' => $this->tinyInteger()->unsigned()->notNull()->comment('返利类型、1直属返利、2代理返利'),
            'rebate_week' => $this->date()->notNull()->comment('返利的周'),
            'create_time' => $this->dateTime()->notNull()->comment('返利时间'),
            'is_agent' => $this->tinyInteger()->unsigned()->notNull()->comment('是否代理'),
            'UNIQUE KEY `union_ukey` (`parent_id`, `player_id`, `type`, `rebate_week`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_rebate');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181129_041131_create_table_log_rebate cannot be reverted.\n";

        return false;
    }
    */
}
