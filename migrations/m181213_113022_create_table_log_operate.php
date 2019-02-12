<?php

use yii\db\Migration;

/**
 * Class m181213_113022_create_table_log_operate
 */
class m181213_113022_create_table_log_operate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_operate', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'op_user' => $this->string(20)->notNull()->comment('操作者'),
            'op_history' => $this->string(20)->notNull()->comment('操作前'),
            'op_content' => $this->string(200)->notNull()->comment('操作内容'),
            'op_time' => $this->dateTime()->notNull()->comment('操作时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_operate');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181213_113022_create_table_log_operate cannot be reverted.\n";

        return false;
    }
    */
}
