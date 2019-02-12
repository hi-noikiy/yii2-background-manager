<?php

use yii\db\Migration;

/**
 * Class m181203_071257_create_table_t_lunbo
 */
class m181203_071257_create_table_t_lunbo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_lunbo', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'img_url' => $this->string(100)->notNull()->comment('图片地址'),
            'jump_type' => $this->tinyInteger()->unsigned()->notNull()->comment('跳转方式、1外部跳转、2内部跳转、3webview'),
            'jump_url' => $this->string(100)->notNull()->comment('跳转地址'),
            'info' => $this->string(30)->notNull()->comment('图片说明'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_lunbo');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_071257_create_table_t_lunbo cannot be reverted.\n";

        return false;
    }
    */
}
