<?php

use yii\db\Migration;

/**
 * Class m181203_153943_create_table_t_general_robot
 */
class m181203_153943_create_table_t_general_robot extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_general_robot', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'player_id' => $this->integer(),
            'nickname' => $this->string(50),
            'img_url' => $this->string(100)->comment('用户头像'),
            'ip' => $this->string(50)->comment('ip'),
            'character_id' => $this->tinyInteger()->comment('机器人性格id'),
            'bet' => $this->integer()->notNull()->defaultValue(0)->comment('最高底注'),
            'now_coin' => $this->integer()->notNull()->defaultValue(0)->comment('当前元宝'),
            'take_coin' => $this->integer()->notNull()->defaultValue(0)->comment('携带元宝'),
            'borrow_num' => $this->integer()->notNull()->defaultValue(0)->comment('借贷次数'),
            'borrow_limit' => $this->integer()->notNull()->defaultValue(0)->comment('借贷额度'),
            'game_num' => $this->integer()->notNull()->defaultValue(0)->comment('游戏场次'),
            'win_num' => $this->integer()->notNull()->defaultValue(0)->comment('赢场次'),
            'lose_num' => $this->integer()->comment('输场次'),
            'win_percent' => $this->float(10, 2)->notNull()->defaultValue(0)->comment('输赢比例'),
            'gid' => $this->integer()->comment('游戏id'),
            'latitude' => $this->string(50),
            'longitude' => $this->string(50),
            'UNIQUE INDEX `player_id`(`player_id`)'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_general_robot');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_153943_create_table_t_general_robot cannot be reverted.\n";

        return false;
    }
    */
}
