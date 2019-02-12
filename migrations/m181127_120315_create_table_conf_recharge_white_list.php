<?php

use yii\db\Migration;

/**
 * Class m181127_120315_create_table_conf_recharge_white_list
 */
class m181127_120315_create_table_conf_recharge_white_list extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('conf_recharge_white_list', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'player_id' => $this->integer()->unsigned()->notNull()->comment('玩家ID'),
            'player_name' => $this->string(20)->notNull()->comment('玩家昵称'),
            'create_time' => $this->dateTime()->notNull()->comment('录入时间'),
            'consume' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('历史消耗'),
            'is_agent' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('是否代理'),
            'under_consume' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('伞下消耗'),
            'status' => $this->tinyInteger()->unsigned()->notNull()->comment('状态、0关闭、1开启'),
            'regist_time' => $this->dateTime()->notNull()->comment('创角时间'),
            'UNIQUE KEY `player_id_ukey` (`player_id`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('conf_recharge_white_list');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181127_120315_create_table_conf_recharge_white_list cannot be reverted.\n";

        return false;
    }
    */
}
