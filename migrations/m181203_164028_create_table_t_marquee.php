<?php

use yii\db\Migration;

/**
 * Class m181203_164028_create_table_t_marquee
 */
class m181203_164028_create_table_t_marquee extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
//        $this->createTable('t_marquee', [
//            'id' => \yii\db\Schema::TYPE_UPK,
//            'account' => $this->integer()->notNull()->comment(''),
//            ''
//        ]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_marquee');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_164028_create_table_t_marquee cannot be reverted.\n";

        return false;
    }
    */
}
