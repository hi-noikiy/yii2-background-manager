<?php

use yii\db\Migration;

/**
 * Class m181206_133735_create_table_stat_base_consume
 */
class m181206_133735_create_table_stat_base_consume extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stat_base_consume', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'channel_id' => $this->string(20)->notNull()->defaultValue(1)->comment('渠道ID'),
            'stat_date' => $this->date()->notNull()->comment('统计日期'),
            'consume' => $this->bigInteger()->notNull()->defaultValue(0)->comment('消耗'),
            'fillup' => $this->bigInteger()->notNull()->defaultValue(0)->comment('淤积'),
            'tixian' => $this->decimal(10, 2)->notNull()->defaultValue(0.00)->comment('玩家提现'),
            'daili_tixian' => $this->decimal(10, 2)->notNull()->defaultValue(0.00)->comment('代理提现'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('stat_base_consume');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181206_133735_create_table_stat_base_consume cannot be reverted.\n";

        return false;
    }
    */
}
