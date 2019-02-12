<?php

use yii\db\Migration;

/**
 * Class m181225_063813_create_table_stat_agent_add
 */
class m181225_063813_create_table_stat_agent_add extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stat_agent_add', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'player_id' => $this->string(10)->notNull()->comment('代理ID'),
            'player_nickname' => $this->string(30)->notNull()->comment('代理昵称'),
            'parent_id' => $this->string(10)->notNull()->comment('上级代理ID'),
            'parent_nickname' => $this->string(30)->notNull()->comment('上级代理昵称'),
            'top_id' => $this->string(10)->notNull()->comment('顶级代理ID'),
            'top_nickname' => $this->string(30)->notNull()->comment('顶级代理昵称'),
            'add_user' => $this->integer()->unsigned()->notNull()->comment('新增玩家'),
            'add_agent' => $this->integer()->unsigned()->notNull()->comment('新增代理'),
            'consume' => $this->decimal(10, 2)->unsigned()->notNull()->comment('直属业绩'),
            'new_consume' => $this->decimal(10, 2)->unsigned()->notNull()->comment('新增直属业绩'),
            'stat_date' => $this->date()->notNull()->comment('统计日期'),
            'UNIQUE KEY `player_stat_ukey` (`player_id`, `stat_date`)'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('stat_agent_add');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181225_063813_create_table_stat_agent_add cannot be reverted.\n";

        return false;
    }
    */
}
