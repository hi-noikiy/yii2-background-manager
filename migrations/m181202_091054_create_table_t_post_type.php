<?php

use yii\db\Migration;

/**
 * Class m181202_091054_create_table_t_post_type
 */
class m181202_091054_create_table_t_post_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_post_type', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'type_id' => $this->integer()->unsigned()->notNull()->defaultValue(1)->comment('消息类型'),
            'type_name' => $this->string(20)->notNull()->defaultValue('')->comment('类型名称'),
            'inter' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('时间间隔'),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('状态'),
        ]);

        $this->batchInsert('t_post_type', [
            'type_id',
            'type_name',
            'inter',
            'status',
        ], [
            ['101', 'GM消息(后台)', 20, 1],
            ['201', '用户发送消息', 5, 1],
            ['301', '系统消息(游戏)', 20, 1],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_post_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_091054_create_table_t_post_type cannot be reverted.\n";

        return false;
    }
    */
}
