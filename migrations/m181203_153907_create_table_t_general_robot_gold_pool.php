<?php

use yii\db\Migration;

/**
 * Class m181203_153907_create_table_t_general_robot_gold_pool
 */
class m181203_153907_create_table_t_general_robot_gold_pool extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_general_robot_gold_pool', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'gid' => $this->integer()->comment('游戏ID'),
            'now_gold_pool' => $this->integer()->comment('奖池额度'),
            'total_gold_pool' => $this->integer()->comment('奖池总额度'),
            'up_limit' => $this->string()->comment('警戒上限'),
            'down_limit' => $this->string()->comment('警戒下限'),
            'character_id' => $this->integer()->comment('下限时更改机器人性格'),
            'create_time' => $this->dateTime()->comment('创建时间'),
            'uid' => $this->integer()->comment('创建人'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_general_robot_gold_pool');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_153907_create_table_t_general_robot_gold_pool cannot be reverted.\n";

        return false;
    }
    */
}
