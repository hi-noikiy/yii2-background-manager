<?php

use yii\db\Migration;

/**
 * Class m181127_114502_create_table_conf_payment
 */
class m181127_114502_create_table_conf_payment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('conf_payment', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'pay_name' => $this->string(20)->notNull()->comment('支付方式'),
            'pull_type' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('拉起方式、1分享公众号、2H5浏览器'),
            'status' => $this->tinyInteger()->unsigned()->notNull()->comment('状态、0关闭、1开启'),
            'create_time' => $this->dateTime()->notNull()->comment('创建时间'),
            'update_time' => $this->dateTime()->notNull()->comment('最后修改时间'),
            'remark' => $this->string(30)->notNull()->comment('说明'),
            'UNIQUE KEY `pay_name_ukey` (`pay_name`)'
        ]);

        $t = date('Y-m-d H:i:s');
        $this->batchInsert('conf_payment', [
            'id',
            'pay_name',
            'pull_type',
            'status',
            'create_time',
            'update_time',
            'remark',
        ], [
            [null, 'wechat', 2, 1, $t, $t, '微信'],
            [null, 'alipay', 2, 1, $t, $t, '支付宝'],
            [null, 'unionpay', 2, 1, $t, $t, '银联'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('conf_payment');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181127_114502_create_table_conf_payment cannot be reverted.\n";

        return false;
    }
    */
}
