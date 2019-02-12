<?php

use yii\db\Migration;

/**
 * Class m181203_163933_create_table_auto_config
 */
class m181203_163933_create_table_auto_config extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('auto_config');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_163933_create_table_auto_config cannot be reverted.\n";

        return false;
    }
    */
}
