<?php

use yii\db\Migration;

/**
 * Class m181210_155416_create_table_alarm_gold
 */
class m181210_155416_create_table_alarm_gold extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_alarm_gold', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'order_gold' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('充值元宝数'),
            'vip_gold' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('VIP充值元宝数'),
            'system_gold' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('系统增发'),
            'hongbao' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('红包元宝'),
            'activity' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('活动赠送'),
            'consume' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('元宝消耗'),
            'tixian' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('提现消耗'),
            'yuji' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('淤积'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_alarm_gold');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181210_155416_create_table_alarm_gold cannot be reverted.\n";

        return false;
    }
    */
}
