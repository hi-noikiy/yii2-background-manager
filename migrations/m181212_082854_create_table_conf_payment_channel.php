<?php

use yii\db\Migration;

/**
 * Class m181212_082854_create_table_conf_payment_channel
 */
class m181212_082854_create_table_conf_payment_channel extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('conf_payment_channel', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'payment' => $this->tinyInteger()->unsigned()->notNull()->comment('支付方式'),
            'pay_channel' => $this->tinyInteger()->unsigned()->notNull()->comment('支付渠道'),
            'create_time' => $this->dateTime()->notNull()->comment('创建时间'),
            'master' => $this->tinyInteger()->unsigned()->notNull()->comment('主从、1master、2slave'),
            'weight' => $this->tinyInteger()->unsigned()->notNull()->comment('权重、1-10'),
        ]);

        $t = date('Y-m-d H:i:s');
        $this->batchInsert('conf_payment_channel', [
            'payment',
            'pay_channel',
            'create_time',
            'master',
            'weight',
        ], [
            [3, 1, $t, 0, 0],
            [1, 2, $t, 0, 0],
            [2, 2, $t, 0, 0],
            [3, 2, $t, 1, 10],
            [2, 3, $t, 0, 0],
            [2, 4, $t, 1, 10],
            [1, 5, $t, 1, 10],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('conf_payment_channel');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181212_082854_create_table_conf_payment_channel cannot be reverted.\n";

        return false;
    }
    */
}
