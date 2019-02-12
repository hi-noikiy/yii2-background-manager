<?php

use yii\db\Migration;

/**
 * Class m181202_085444_create_table_t_real_online
 */
class m181202_085444_create_table_t_real_online extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_real_online', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'channel_id' => $this->string(20)->notNull()->comment('渠道ID'),
            'gid' => $this->integer()->unsigned()->notNull()->comment('游戏ID'),
            'num' => $this->integer()->unsigned()->notNull()->comment('在线人数'),
            'stat_time' => $this->dateTime()->notNull()->comment('统计时间'),
            'UNIQUE KEY `stat_time_key` (`stat_time`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_real_online');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_085444_create_table_t_real_online cannot be reverted.\n";

        return false;
    }
    */
}
