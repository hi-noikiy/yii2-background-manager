<?php

use yii\db\Migration;

/**
 * Class m181202_131152_create_table_log_operation
 */
class m181202_131152_create_table_log_operation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_operation', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'username' => $this->string(20)->notNull()->comment('账号名'),
            'op_type' => $this->tinyInteger()->unsigned()->notNull()->comment('操作类型'),
            'op_time' => $this->dateTime()->notNull()->comment('操作时间'),
            'op_content' => $this->string(500)->notNull()->comment('操作内容'),
            'op_player_id' => $this->integer()->notNull()->defaultValue(0)->comment('被操作人ID'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_operation');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_131152_create_table_log_operation cannot be reverted.\n";

        return false;
    }
    */
}
