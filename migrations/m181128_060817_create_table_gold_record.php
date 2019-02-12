<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m181128_060817_create_table_gold_record
 */
class m181128_060817_create_table_gold_record extends Migration
{
    const CRETAE_COUNT = 365;

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        for ($i = 0; $i < self::CRETAE_COUNT; $i++) {
            $d = date('Ymd', time() + ($i * 86400));

            $this->createTable('t_gold_record__' . $d, [
                'id' => Schema::TYPE_UBIGPK,
                'channel_id' => $this->string(20)->notNull()->defaultValue(1)->comment('渠道ID'),
                'gid' => $this->integer()->unsigned()->notNull()->comment('游戏ID'),
                'player_id' => $this->integer()->unsigned()->notNull()->comment('玩家ID'),
                'order_id' => $this->string(55)->notNull()->comment('唯一订单号'),
                'num' => $this->integer()->unsigned()->notNull()->comment('台费元宝数'),
                'type' => $this->tinyInteger(1)->unsigned()->notNull()->defaultValue(1)->comment('代币类型：1元宝'),
                'level' => $this->smallInteger(6)->unsigned()->notNull()->comment('台费等级'),
                'status' => $this->tinyInteger(1)->unsigned()->notNull()->defaultValue(0)->comment('处理结果：0未处理、1已处理'),
                'create_time' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
                'update_time' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('修改时间'),
                'UNIQUE KEY `order_id_ukey` (`order_id`)',
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        for ($i = 0; $i < self::CRETAE_COUNT; $i++) {
            $data = $d = date('Ymd', time() + ($i * 86400));

            $this->dropTable('t_gold_record__' . $d);
        }
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181128_060817_create_table_gold_record cannot be reverted.\n";

        return false;
    }
    */
}
