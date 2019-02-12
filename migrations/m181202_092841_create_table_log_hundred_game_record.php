<?php

use yii\db\Migration;

/**
 * Class m181202_092841_create_table_log_hundred_game_record
 */
class m181202_092841_create_table_log_hundred_game_record extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_hundred_game_record', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'gid' => $this->integer()->unsigned()->notNull()->comment('游戏ID'),
            'date' => $this->dateTime()->comment('时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_hundred_game_record');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_092841_create_table_log_hundred_game_record cannot be reverted.\n";

        return false;
    }
    */
}
