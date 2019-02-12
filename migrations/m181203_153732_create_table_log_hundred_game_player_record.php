<?php

use yii\db\Migration;

/**
 * Class m181203_153732_create_table_log_hundred_game_player_record
 */
class m181203_153732_create_table_log_hundred_game_player_record extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_hundred_game_player_record', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'gid' => $this->integer(),
            'record_id' => $this->integer()->comment('对局记录id'),
            'robot_type' => $this->tinyInteger()->comment('玩家类型0为人，1、2机器人'),
            'player_id' => $this->integer(),
            'zhuang' => $this->tinyInteger()->comment('是否是庄家'),
            'win_num' => $this->integer()->comment('玩家输赢数'),
            'date' => $this->dateTime()->comment('牌局结束时间'),
            'created_time' => $this->dateTime(),
            'updated_time' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_hundred_game_player_record');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_153732_create_table_log_hundred_game_player_record cannot be reverted.\n";

        return false;
    }
    */
}
