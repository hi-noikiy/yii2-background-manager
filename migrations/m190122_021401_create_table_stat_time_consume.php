<?php

use yii\db\Migration;

/**
 * Class m190122_021401_create_table_stat_time_consume
 */
class m190122_021401_create_table_stat_time_consume extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stat_time_consume', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'channel_id' => $this->string(20)->notNull()->defaultValue(1)->comment('渠道ID'),
            'gid' => $this->integer()->unsigned()->notNull()->comment('游戏ID'),
            'stat_time' => $this->dateTime()->notNull()->comment('统计时间'),
            ''
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190122_021401_create_table_stat_time_consume cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190122_021401_create_table_stat_time_consume cannot be reverted.\n";

        return false;
    }
    */
}
