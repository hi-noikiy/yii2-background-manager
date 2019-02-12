<?php

use yii\db\Migration;

/**
 * Class m181202_031306_create_table_t_daili_player
 */
class m181202_031306_create_table_t_daili_player extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_daili_player', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'player_id' => $this->integer()->unsigned()->notNull()->comment('玩家ID'),
            'name' => $this->string(50)->notNull()->defaultValue('')->comment('玩家昵称'),
            'tel' => $this->string(20)->notNull()->defaultValue('')->comment('电话'),
            'address' => $this->string(80)->notNull()->defaultValue('')->comment('地址'),
            'sex' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(1)->comment('性别、1男、2女、0未知'),
            'age' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('年龄'),
            'true_name' => $this->string(30)->notNull()->defaultValue('')->comment('真实姓名'),
            'type' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('代理类型'),
            'daili_level' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('代理等级'),
            'parent_index' => $this->integer()->unsigned()->notNull()->comment('上级代理ID'),
            'member_num' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('伞下玩家数量'),
            'open_num' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('可开通代理数量'),
            'create_time' => $this->dateTime()->notNull()->comment('创建时间'),
            'update_time' => $this->dateTime()->notNull()->comment('更新时间'),
            'bind_time' => $this->dateTime()->notNull()->defaultValue('1000-01-01 00:00:00')->comment('绑定时间'),
            'pay_back_gold' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('可提现元宝'),
            'all_pay_back_gold' => $this->decimal(10,2)->unsigned()->notNull()->defaultValue(0.00)->comment('历史总元宝'),
            'forzen_money' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('冻结金额'),
            'follow' => $this->string(255)->notNull()->defaultValue('')->comment('跟进人'),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('状态、0取消、1正常'),
            'create_type' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(1)->comment('1后台开通、2代理开通'),
            'last_login_ip' => $this->string(20)->notNull()->defaultValue(0)->comment('最后登录IP'),
            'last_login_time' => $this->dateTime()->notNull()->defaultValue('1000-01-01 00:00:00')->comment('最后登录时间'),
            'UNIQUE KEY `player_id_ukey`(`player_id`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_daili_player');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_031306_create_table_t_daili_player cannot be reverted.\n";

        return false;
    }
    */
}
