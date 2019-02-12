<?php

use yii\db\Migration;

/**
 * Class m181225_083443_create_table_stat_player_ltv
 */
class m181225_083443_create_table_stat_player_ltv extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stat_base_ltv', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'channel_id' => $this->integer()->unsigned()->notNull()->comment('渠道ID'),
            'stat_date' => $this->date()->notNull()->comment('统计日期'),
            'regist' => $this->integer()->unsigned()->notNull()->comment('新增注册'),
            'ltv' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('LTV'),
            'c_0' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('当日消耗'),
            'c_0_avg' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('当日前消耗平均值'),
            'c_1' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('1日消耗'),
            'c_1_avg' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('1日前消耗和平均值'),
            'c_2' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('2日消耗'),
            'c_2_avg' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('2日前消耗和平均值'),
            'c_3' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('3日消耗'),
            'c_3_avg' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('3日前消耗和平均值'),
            'c_4' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('4日消耗'),
            'c_4_avg' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('4日前消耗和平均值'),
            'c_5' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('5日消耗'),
            'c_5_avg' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('5日前消耗和平均值'),
            'c_6' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('6日消耗'),
            'c_6_avg' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('6日前消耗和平均值'),
            'c_7' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('7日消耗'),
            'c_7_avg' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('7日前消耗和平均值'),
            'c_8' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('8日消耗'),
            'c_8_avg' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('8日前消耗和平均值'),
            'c_9' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('9日消耗'),
            'c_9_avg' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('9日前消耗和平均值'),
            'c_10' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('10日消耗'),
            'c_10_avg' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('10日前消耗和平均值'),
            'c_14' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('14日消耗'),
            'c_14_avg' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('14日前消耗和平均值'),
            'c_30' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('30日消耗'),
            'c_30_avg' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('30日前消耗和平均值'),
            'c_60' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0.00)->comment('60日消耗'),
            'c_60_avg' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('60日前消耗和平均值'),
            'UNIQUE KEY `channel_stat_ukey` (`channel_id`, `stat_date`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('stat_player_ltv');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181225_083443_create_table_stat_player_ltv cannot be reverted.\n";

        return false;
    }
    */
}
