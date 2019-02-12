<?php

use yii\db\Migration;

/**
 * Class m181202_083533_create_table_t_vip_recharge_log
 */
class m181202_083533_create_table_t_vip_recharge_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_vip_recharge_log', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'player_id' => $this->integer()->unsigned()->notNull()->comment('玩家ID'),
            'amount' => $this->integer()->notNull()->defaultValue(0)->comment('充值金额'),
            'operate_user' => $this->string(50)->notNull()->defaultValue('')->comment('操作人'),
            'out_amount' => $this->integer()->notNull()->defaultValue(0)->comment('输入金额'),
            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('订单状态、1充值成功、2充值失败'),
            'create_time' => $this->dateTime()->comment('创建时间'),
            'update_time' => $this->dateTime()->comment('更新时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_vip_recharge_log');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_083533_create_table_t_vip_recharge_log cannot be reverted.\n";

        return false;
    }
    */
}
