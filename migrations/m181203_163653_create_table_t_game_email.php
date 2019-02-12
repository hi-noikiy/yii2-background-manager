<?php

use yii\db\Migration;

/**
 * Class m181203_163653_create_table_t_game_email
 */
class m181203_163653_create_table_t_game_email extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_game_email', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'email_code' => $this->char(10)->notNull()->comment('邮件编码'),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181203_163653_create_table_t_game_email cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_163653_create_table_t_game_email cannot be reverted.\n";

        return false;
    }
    */
}
