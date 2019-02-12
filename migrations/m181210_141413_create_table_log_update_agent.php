<?php

use yii\db\Migration;

/**
 * Class m181210_141413_create_table_log_update_agent
 */
class m181210_141413_create_table_log_update_agent extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_update_agent', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'parent_id' => $this->integer()->unsigned()->notNull()->comment('上级ID'),
            'player_id' => $this->integer()->unsigned()->notNull()->comment('代理ID'),
            'rebate' => $this->decimal(10, 2)->notNull()->defaultValue(0)->comment('返利元宝数'),
            'rebate_week' => $this->date()->notNull()->comment('返利周日期'),
            'create_time' => $this->dateTime()->notNull()->comment('返利时间'),
            'UNIQUE KEY `parent_player_rebate_week_ukey` (`parent_id`, `player_id`, `rebate_week`)'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_update_agent');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181210_141413_create_table_log_update_agent cannot be reverted.\n";

        return false;
    }
    */
}
