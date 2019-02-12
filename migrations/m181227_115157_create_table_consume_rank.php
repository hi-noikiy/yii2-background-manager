<?php

use yii\db\Migration;

/**
 * Class m181227_115157_create_table_consume_rank
 */
class m181227_115157_create_table_consume_rank extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_consume_rank', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'top_id' => $this->string(10)->notNull()->defaultValue('')->comment('顶级ID'),
            'top_name' => $this->string(20)->notNull()->defaultValue('')->comment('顶级昵称'),
            'parent_id' => $this->string(10)->notNull()->defaultValue('')->comment('上级ID'),
            'parent_name' => $this->string(20)->notNull()->defaultValue('')->comment('上级昵称'),
            'player_id' => $this->string(10)->notNull()->defaultValue('')->comment('玩家ID'),
            'player_name' => $this->string(20)->notNull()->defaultValue('')->comment('玩家昵称'),
            'consume' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0)->comment('当日消耗'),
            'recharge' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0)->comment('当日充值'),
            'duihuan' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0)->comment('当日兑换'),
            'sz' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0)->comment('三张'),
            'br_ttz' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0)->comment('百人推筒子'),
            'ps' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0)->comment('拼十'),
            'ttz' => $this->decimal(10, 2)->unsigned()->notNull()->defaultValue(0)->comment('推筒子消耗'),
            'regist' => $this->dateTime()->notNull()->comment('注册时间'),
            'stat_date' => $this->date()->notNull()->defaultValue('1000-01-01')->comment('统计时间'),
            'UNIQUE KEY `player_stat_ukey` (`player_id`, `stat_date`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_consume_rank');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181227_115157_create_table_consume_rank cannot be reverted.\n";

        return false;
    }
    */
}
