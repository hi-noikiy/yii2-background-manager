<?php

use yii\db\Migration;

/**
 * Class m181208_103141_create_table_stat_base_activity
 */
class m181208_103141_create_table_stat_base_activity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stat_base_activity', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'channel_id' => $this->string(20)->notNull()->defaultValue(1)->comment('渠道ID'),
            'stat_date' => $this->date()->notNull()->comment('统计日期'),
            'shouchong' => $this->bigInteger()->notNull()->defaultValue(0)->comment('首冲'),
            'xinshou' => $this->bigInteger()->notNull()->defaultValue(0)->comment('新手赠送'),
            'hongbao' => $this->bigInteger()->notNull()->defaultValue(0)->comment('红包'),
            'total' => $this->bigInteger()->notNull()->defaultValue(0)->comment('总活动'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('stat_base_activity');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181208_103141_create_table_stat_base_activity cannot be reverted.\n";

        return false;
    }
    */
}
