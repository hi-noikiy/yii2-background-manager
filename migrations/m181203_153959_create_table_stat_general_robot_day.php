<?php

use yii\db\Migration;

/**
 * Class m181203_153959_create_table_stat_general_robot_day
 */
class m181203_153959_create_table_stat_general_robot_day extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stat_general_robot_day', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'gid' => $this->integer()->comment('游戏ID'),
            'date' => $this->dateTime()->comment('统计日期'),
            'character' => $this->string(500)->comment('性格统计'),
            'player_num' => $this->integer()->comment('陪玩人数'),
            'init_gold' => $this->integer()->comment('初始奖池额度'),
            'curr_gold' => $this->integer()->comment('结算奖池'),
            'cost_gold' => $this->integer()->comment('元宝消耗'),
            'borrow_gold' => $this->integer()->comment('借贷额度'),
            'borrow_count' => $this->integer()->comment('借贷次数'),
            'game_count' => $this->integer()->comment('游戏总场次'),
            'win_count' => $this->integer()->comment('赢场次'),
            'lose_count' => $this->integer()->comment('输场次'),
            'win_percent' => $this->string()->comment('输赢比'),
            'robot_num' => $this->integer()->comment('机器人数量'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('stat_general_robot_day');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_153959_create_table_stat_general_robot_day cannot be reverted.\n";

        return false;
    }
    */
}
