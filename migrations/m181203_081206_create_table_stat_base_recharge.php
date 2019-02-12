<?php

use yii\db\Migration;

/**
 * Class m181203_081206_create_table_stat_base_recharge
 */
class m181203_081206_create_table_stat_base_recharge extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stat_base_recharge', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'channel_id' => $this->string(20)->notNull()->defaultValue(1)->comment('渠道ID'),
            'stat_date' => $this->date()->notNull()->comment('统计日期'),
            'pay_user' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('日付费用户数'),
            'pay_count' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('日付费次数'),
            'new_pay_user' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('日新付费用户数'),
            'new_pay_count' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('日新付费次数'),
            'amt' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('日充值'),
            'all_amt' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('总充值'),
            'UNIQUE KEY `channel_stat_date_ukey` (`stat_date`, `channel_id`)'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('stat_base_recharge');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_081206_create_table_stat_base_recharge cannot be reverted.\n";

        return false;
    }
    */
}
