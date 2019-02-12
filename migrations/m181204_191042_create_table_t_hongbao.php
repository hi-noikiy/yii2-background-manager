<?php

use yii\db\Migration;

/**
 * Class m181204_191042_create_table_t_hongbao
 */
class m181204_191042_create_table_t_hongbao extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_hongbao', [
            'id' => \yii\db\Schema::TYPE_BIGPK,
            'rank' => $this->integer()->notNull()->comment('1:一等奖  2:二等奖  3:三等奖  4:幸运奖'),
            'uid' => $this->integer()->notNull()->comment('获奖者UID'),
            'gold' => $this->bigInteger()->notNull(),
            'create_time' => $this->timestamp()->notNull(),
            'times' => $this->integer()->notNull()->comment('发奖次数'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_hongbao');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181204_191042_create_table_t_hongbao cannot be reverted.\n";

        return false;
    }
    */
}
