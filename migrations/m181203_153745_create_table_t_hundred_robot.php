<?php

use yii\db\Migration;

/**
 * Class m181203_153745_create_table_t_hundred_robot
 */
class m181203_153745_create_table_t_hundred_robot extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_hundred_robot', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'gid' => $this->integer(),
            'player_id' => $this->integer(),
            'nickname' => $this->string(20),
            'img_url' => $this->string(200),
            'ip' => $this->string(50),
            'created_time' => $this->dateTime(),
            'updated_time' => $this->dateTime(),
            'is_system' => $this->tinyInteger()->comment('是否系统庄'),
            'sex' => $this->tinyInteger(),
            'game_nums' => $this->integer(),
            'win_nums' => $this->integer(),
            'lose_nums' => $this->integer(),
            'win_percent' => $this->string(10),
            'state' => $this->tinyInteger()->defaultValue(1),
            'instruc' => $this->tinyInteger()->defaultValue(0),
            'init_yuanbao' => $this->integer()->comment('庄每局所携带的最大元宝数'),
            'yuanbao_range' => $this->string(20)->comment('闲家所携带的元宝最小最大的数量区间'),
            'UNIQUE INDEX `player_id`(`player_id`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_hundred_robot');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_153745_create_table_t_hundred_robot cannot be reverted.\n";

        return false;
    }
    */
}
