<?php

use yii\db\Migration;

/**
 * Class m181202_034457_create_table_t_player_member
 */
class m181202_034457_create_table_t_player_member extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_player_member', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'parent_id' => $this->integer()->unsigned()->notNull()->comment('上级ID'),
            'player_id' => $this->integer()->unsigned()->notNull()->comment('玩家ID'),
            'bind_time' => $this->dateTime()->notNull()->comment('绑定时间'),
            'UNIQUE KEY `player_id_ukey` (`player_id`)',
            'KEY `parent_key` (`parent_id`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_player_member');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_034457_create_table_t_player_member cannot be reverted.\n";

        return false;
    }
    */
}
