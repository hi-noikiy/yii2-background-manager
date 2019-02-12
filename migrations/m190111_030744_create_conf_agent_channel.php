<?php

use yii\db\Migration;

/**
 * Class m190111_030744_create_conf_agent_channel
 */
class m190111_030744_create_conf_agent_channel extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('conf_agent_channel', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'channel_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('渠道ID'),
            'agent_id' => $this->integer()->unsigned()->notNull()->defaultValue(999)->comment('代理ID'),
            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(1)->comment('状态、1正常、0关闭'),
            'create_time' => $this->dateTime()->notNull()->defaultValue('1000-01-01 00:00:00.000000')->comment('创建时间'),
            'update_time' => $this->dateTime()->notNull()->defaultValue('1000-01-01 00:00:00.000000')->comment('更新时间'),
            'UNIQUE KEY `channel_id_ukey` (`channel_id`)',
        ]);

        $d = date('Y-m-d H:i:s');
        $this->batchInsert('conf_agent_channel', [
            'channel_id',
            'agent_id',
            'status',
            'create_time',
            'update_time',
        ], [
            ['10001', '30011608', 1, "{$d}", "{$d}"],
            ['10002', '999', 1, "{$d}", "{$d}"],
            ['10003', '30011607', 1, "{$d}", "{$d}"],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('conf_agent_channel');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190111_030744_create_conf_agent_channel cannot be reverted.\n";

        return false;
    }
    */
}
