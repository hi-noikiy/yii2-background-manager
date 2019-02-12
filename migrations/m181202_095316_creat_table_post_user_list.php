<?php

use yii\db\Migration;

/**
 * Class m181202_095316_creat_table_post_user_list
 */
class m181202_095316_creat_table_post_user_list extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('post_user_list', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'player_index' => $this->integer(),
            'sign' => $this->tinyInteger()->comment('1白名单、2黑名单'),
            'created_time' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('post_user_list');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_095316_creat_table_post_user_list cannot be reverted.\n";

        return false;
    }
    */
}
