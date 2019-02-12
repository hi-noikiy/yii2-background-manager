<?php

use yii\db\Migration;

/**
 * Class m181127_121341_create_table_conf_pay_channel
 */
class m181127_121341_create_table_conf_pay_channel extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('conf_pay_channel', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'class_code' => $this->string(20)->notNull()->comment('支付类名称'),
            'channel_code' => $this->string(30)->notNull()->comment('渠道CODE'),
            'appid' => $this->string(50)->notNull()->comment('应用ID'),
            'appkey' => $this->string(50)->notNull()->comment('应用秘钥'),
            'reserve1' => $this->integer()->notNull()->defaultValue(0)->comment('预留信息1'),
            'reserve2' => $this->integer()->notNull()->defaultValue(0)->comment('预留信息2'),
            'reserve3' => $this->string(50)->notNull()->defaultValue('')->comment('预留信息3'),
            'reserve4' => $this->string(50)->notNull()->defaultValue('')->comment('预留信息4'),
            'status' => $this->tinyInteger()->unsigned()->notNull()->comment('状态、0关闭、1开启'),
            'trade_url' => $this->string(100)->notNull()->defaultValue('')->comment('下单地址'),
            'notify_url' => $this->string(100)->notNull()->defaultValue('')->comment('服务器通知地址'),
            'return_url' => $this->string(100)->notNull()->defaultValue('')->comment('页面通知地址'),
            'launch_url' => $this->string(100)->notNull()->defaultValue('')->comment('发起支付地址'),
        ]);

        $this->batchInsert('conf_pay_channel', [
            'class_code',
            'channel_code',
            'appid',
            'appkey',
            'reserve1',
            'reserve2',
            'reserve3',
            'reserve4',
            'status',
            'trade_url',
            'notify_url',
            'return_url',
            'launch_url',
        ], [
            ['Heepay', 'heepay_1', '2117095', 'B6DA99B8214A4DA4B10DF6D0', 0, 0, '', '', 1, 'https://pay.heepay.com/Payment/Index.aspx?', 'https://pay.game0165.com/api/notify-heepay/index', 'https://pay.game0165.com/api/notify-heepay/index', 'https://pay.game0165.com/api/recharge/pay'],
            ['Heepay', 'heepay_2', '2117096', 'F7F999A17985498C9B4CB2C5', 0, 0, '', '', 1, 'https://pay.heepay.com/Payment/Index.aspx?', 'https://pay.game0165.com/api/notify-heepay/index', 'https://pay.game0165.com/api/notify-heepay/index', 'https://pay.game0165.com/api/recharge/pay'],
            ['Guangda', 'Guangda', '93ffa6e91d2847fd980101fa7631394c', 'aff9d51cd95542698a8879fc95ee6bd1', 0, 0, '', '', 1, 'https://api.zql666.cn/wapPay/doPay?', 'https://pay.game0165.com/api/notify-guangda/index', 'https://pay.game0165.com/api/notify-guangda/index', 'https://pay.game0165.com/api/recharge/pay'],
            ['Jpay', 'Jpay', '01018127907801', '040109104732PXE0CHzn', 0, 0, '', '', 1, 'https://toqfqze.sunlin1.com', 'https://pay.game0165.com/api/notify-jpay/index', 'https://pay.game0165.com/api/notify-jpay/index', 'https://pay.game0165.com/api/recharge/pay'],
            ['wechatwap', 'wechatwap', 'wxdefb3685bf94df40', '7293edb63c09a81dbc6b2f6e3aacd9fc', '1519439471', 0, '', '', 1, 'https://api.mch.weixin.qq.com/pay/unifiedorder', 'https://pay.game0165.com/api/notify-wechatwap/index', 'https://pay.game0165.com/api/check-order-result/success', 'https://pay.game0165.com/api/recharge/pay'],
            ['Jpay', 'jpay_2', '01018057625001', '040109171758RwDlhSSk', 0, 0, '', '', 1, 'http://toqlicr.sunlin1.com', 'https://pay.game0165.com/api/notify-jpay/index', 'https://pay.game0165.com/api/notify-jpay/index', 'https://pay.game0165.com/api/recharge/pay'],
            ['wechatwap', 'wechatwap', 'wx8549c01c1a00382f', '7293edb63c09a81dbc6b2f6e3aacd9fc', '1518866601', 0, '', '', 1, 'https://api.mch.weixin.qq.com/pay/unifiedorder', 'https://pay.game0165.com/api/notify-wechatwap/index', 'https://pay.game0165.com/api/check-order-result/success', 'https://pay.game0165.com/api/recharge/pay'],
            ['wechatwap', 'wechatwap', 'wx5223c60abfaaf719', '7293edb63c09a81dbc6b2f6e3aacd9fc', '1515631881', 0, '', '', 1, 'https://api.mch.weixin.qq.com/pay/unifiedorder', 'https://pay.game0165.com/api/notify-wechatwap/index', 'https://pay.game0165.com/api/check-order-result/success', 'https://pay.game0165.com/api/recharge/pay'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('conf_pay_channel');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181127_121341_create_table_conf_pay_channel cannot be reverted.\n";

        return false;
    }
    */
}
