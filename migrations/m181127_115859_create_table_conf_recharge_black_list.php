<?php

use yii\db\Migration;

/**
 * Class m181127_115859_create_table_conf_recharge_black_list
 */
class m181127_115859_create_table_conf_recharge_black_list extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('conf_recharge_black_list', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'player_id' => $this->integer()->unsigned()->notNull()->comment('玩家ID'),
            'create_time' => $this->dateTime()->notNull()->comment('创建时间'),
            'status' => $this->tinyInteger()->unsigned()->notNull()->comment('状态、0关闭、1开启'),
            'UNIQUE KEY `player_id_ukey` (`player_id`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('conf_recharge_black_list');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181127_115859_create_table_conf_recharge_black_list cannot be reverted.\n";

        return false;
    }
    */
}
