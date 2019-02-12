<?php

use yii\db\Migration;

/**
 * Class m181203_061901_create_table_stat_base_player
 */
class m181203_061901_create_table_stat_base_player extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stat_base_player', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'channel_id' => $this->string(20)->notNull()->defaultValue(1)->comment('渠道ID'),
            'stat_date' => $this->date()->notNull()->comment('统计日期'),
            'regist' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('扫码用户'),
            'dnu' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('日新增用户数'),
            'dau' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('日新增活跃用户'),
            'all_user' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('总用户数'),
            'ru_1' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('次日留存'),
            'ru_2' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('2日留存'),
            'ru_3' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('3日留存'),
            'ru_4' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('4日留存'),
            'ru_5' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('5日留存'),
            'ru_6' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('6日留存'),
            'ru_7' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('7日留存'),
            'ru_14' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('14日留存'),
            'ru_30' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('30日留存'),
            'ru_60' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('60日留存'),
            'UNIQUE KEY `channel_stat_ukey` (`channel_id`, `stat_date`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('stat_base_player');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_061901_create_table_stat_base_player cannot be reverted.\n";

        return false;
    }
    */
}
