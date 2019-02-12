<?php

use yii\db\Migration;

/**
 * Class m181205_065324_create_table_log_download
 */
class m181205_065324_create_table_log_download extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_download', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'ip' => $this->string(20)->notNull()->defaultValue('127.0.0.1')->comment('IP地址'),
            'source_type' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('来源类型、1手机短信'),
            'op_type' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('操作类型、1访问、2点击下载'),
            'termail' => $this->string(10)->notNull()->comment('机型、IOS/ANDROID'),
            'create_time' => $this->dateTime()->notNull()->comment('时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_download');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181205_065324_create_table_log_download cannot be reverted.\n";

        return false;
    }
    */
}
