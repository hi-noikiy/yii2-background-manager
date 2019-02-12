<?php

use yii\db\Migration;

/**
 * Class m181202_093111_create_table_log_mengxin_alert
 */
class m181202_093111_create_table_log_mengxin_alert extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_mengxin_alert', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'gid' => $this->integer()->unsigned()->notNull()->comment('游戏ID'),
            'player_id' => $this->integer()->unsigned()->notNull()->comment('玩家ID'),
            'stat_date' => $this->dateTime()->notNull()->comment('统计时间'),
            'trigger_count' => $this->integer()->unsigned()->notNull()->comment('触发次数'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_mengxin_alert');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_093111_create_table_log_mengxin_alert cannot be reverted.\n";

        return false;
    }
    */
}
