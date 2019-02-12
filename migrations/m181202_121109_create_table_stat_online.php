<?php

use yii\db\Migration;

/**
 * Class m181202_121109_create_table_stat_online
 */
class m181202_121109_create_table_stat_online extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stat_online', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'channel_id' => $this->string(20)->notNull()->comment('渠道ID'),
            'stat_date' => $this->date()->notNull()->comment('统计日期'),
            'max_online' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('最高在线'),
            'avg_online' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('平均在线'),
            'max_time' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('最大在线时长'),
            'avg_time' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('平均在线时长'),
            'UNIQUE KEY `channel_stat_date_key` (`channel_id`, `stat_date`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('stat_online');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_121109_create_table_stat_online cannot be reverted.\n";

        return false;
    }
    */
}
