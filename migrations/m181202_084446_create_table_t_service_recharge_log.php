<?php

use yii\db\Migration;

/**
 * Class m181202_084446_create_table_t_service_recharge_log
 */
class m181202_084446_create_table_t_service_recharge_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_service_recharge_log', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'player_id' => $this->integer()->unsigned()->notNull()->comment('玩家ID'),
            'gold_type' => $this->tinyInteger()->unsigned()->notNull()->comment('充值类型、3元宝'),
            'player_name' => $this->string(30)->notNull()->comment('玩家名称'),
            'gold_num' => $this->integer()->notNull()->comment('充值数量'),
            'money_num' => $this->double(10,2)->notNull()->defaultValue('0.00')->comment('玩家充值的金额'),
            'use_by' => $this->string(30)->notNull()->comment('操作者'),
            'use_type' => $this->tinyInteger()->unsigned()->notNull()->comment('操作类型、1增加、2减少'),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('充值状态、1成功、2失败、3异常'),
            'gid' => $this->integer()->unsigned()->notNull()->comment('游戏ID'),
            'time' => $this->dateTime()->comment('时间'),
            'content' => $this->string(20)->comment('内容'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_service_recharge_log');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_084446_create_table_t_service_recharge_log cannot be reverted.\n";

        return false;
    }
    */
}
