<?php

use yii\db\Migration;

/**
 * Class m181202_035528_create_table_t_player
 */
class m181202_035528_create_table_t_player extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_player', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'player_id' => $this->integer()->unsigned()->notNull()->comment('玩家ID'),
            'openid' => $this->string(40)->notNull()->defaultValue('')->comment('微信OPENID'),
            'nickname' => $this->string(200)->notNull()->defaultValue('')->comment('玩家昵称'),
            'machine_code' => $this->string(200)->notNull()->defaultValue('')->comment('机器码'),
            'head_img' => $this->string(200)->notNull()->defaultValue('')->comment('头像地址'),
            'phone_num' => $this->string(20)->notNull()->defaultValue(0)->comment('手机号'),
            'reg_time' => $this->dateTime()->notNull()->defaultValue('1000-01-01 00:00:00')->comment('注册时间'),
            'last_login_time' => $this->dateTime()->notNull()->defaultValue('1000-01-01 00:00:00')->comment('最后登录时间'),
            'ip' => $this->string(20)->notNull()->defaultValue(0)->comment('IP地址'),
            'sex' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('性别、1男、2女、0未知'),
            'province' => $this->string(20)->notNull()->defaultValue('')->comment('省份'),
            'city' => $this->string(20)->notNull()->defaultValue('')->comment('城市'),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('状态'),
            'auth_time' => $this->dateTime()->notNull()->defaultValue('1000-01-01 00:00:00')->comment('手机认证时间'),
            'UNIQUE KEY `player_id_ukey` (`player_id`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_player');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_035528_create_table_t_player cannot be reverted.\n";

        return false;
    }
    */
}
