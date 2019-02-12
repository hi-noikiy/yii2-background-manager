<?php

use yii\db\Migration;

/**
 * Class m181202_121541_create_table_stat_mengxin
 */
class m181202_121541_create_table_stat_mengxin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stat_mengxin', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'stat_date' => $this->date()->unsigned()->notNull()->comment('统计日期'),
            'user_all' => $this->integer()->unsigned()->notNull()->comment('新用户总数'),
            'play_all' => $this->integer()->unsigned()->notNull()->comment('玩总场次'),
            'play_accord' => $this->integer()->unsigned()->notNull()->comment('满足赢5场人数'),
            'win_count' => $this->integer()->unsigned()->notNull()->comment('赢场数'),
            'win_sum' => $this->integer()->unsigned()->notNull()->comment('赢金额'),
            'lose_count' => $this->integer()->unsigned()->notNull()->comment('输场数'),
            'lose_sum' => $this->integer()->unsigned()->notNull()->comment('输金额'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('stat_mengxin');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_121541_create_table_stat_mengxin cannot be reverted.\n";

        return false;
    }
    */
}
