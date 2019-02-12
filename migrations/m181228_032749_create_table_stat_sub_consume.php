<?php

use yii\db\Migration;

/**
 * Class m181228_032749_create_table_stat_sub_consume
 */
class m181228_032749_create_table_stat_sub_consume extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stat_sub_consume', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'stat_date' => $this->date()->notNull()->comment('统计日期'),
            'consume' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('总消耗'),
            'br_ttz' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('百人推筒子'),
            'sz' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('三张'),
            'ps' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('拼十'),
            'ttz' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('推筒子'),
            'gg' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('GG伞下消耗'),
            'UNIQUE KEY `stat_date_ukey` (`stat_date`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('stat_sub_consume');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181228_032749_create_table_stat_sub_consume cannot be reverted.\n";

        return false;
    }
    */
}
