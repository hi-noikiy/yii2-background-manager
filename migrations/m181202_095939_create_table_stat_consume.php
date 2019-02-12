<?php

use yii\db\Migration;

/**
 * Class m181202_095939_create_table_stat_consume
 */
class m181202_095939_create_table_stat_consume extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stat_consume', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'stat_date' => $this->date()->notNull()->comment('统计日期'),
            'channel_id' => $this->string()->notNull()->comment('渠道ID'),
            'gid' => $this->string(20)->notNull()->comment('游戏ID'),
            'level' => $this->string(10)->notNull()->comment('台费等级'),
            'active' => $this->integer()->unsigned()->notNull()->comment('活跃人数'),
            'active_count' => $this->integer()->unsigned()->notNull()->comment('活跃人次'),
            'consume' => $this->integer()->unsigned()->notNull()->comment('消耗'),
            'prop' => $this->float(10,2)->unsigned()->notNull()->comment('消耗占比'),
            'ring_ratio' => $this->float(10,2)->unsigned()->notNull()->comment('消耗环比'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('stat_consume');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_095939_create_table_stat_consume cannot be reverted.\n";

        return false;
    }
    */
}
