<?php

use yii\db\Migration;

/**
 * Class m181203_113933_create_table_t_oper_user_expend_day
 */
class m181203_113933_create_table_t_oper_user_expend_day extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_oper_user_expend_day', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'player_index' => $this->integer()->unsigned()->notNull()->comment('用户ID'),
            'num' => $this->integer()->unsigned()->notNull()->comment('玩家当日消耗'),
            'day' => $this->date()->notNull()->comment('统计日期'),
            'KEY `player_index_key` (`player_index`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_oper_user_expend_day');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_113933_create_table_t_oper_user_expend_day cannot be reverted.\n";

        return false;
    }
    */
}
