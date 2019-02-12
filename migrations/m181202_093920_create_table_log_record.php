<?php

use yii\db\Migration;

/**
 * Class m181202_093920_create_table_log_record
 */
class m181202_093920_create_table_log_record extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_record', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'channel_id' => $this->string(20)->notNull()->comment('渠道ID'),
            'gid' => $this->integer()->unsigned()->notNull()->comment('游戏ID'),
            'table_id' => $this->integer()->unsigned()->notNull()->comment('牌桌ID'),
            'dizhu' => $this->integer()->unsigned()->notNull()->comment('底注'),
            'start_time' => $this->dateTime()->notNull()->comment('开始时间'),
            'end_time' => $this->dateTime()->notNull()->comment('结束时间'),
            'table_info' => $this->string(500)->notNull()->comment('桌内参数'),
            'player_id' => $this->integer()->unsigned()->notNull()->comment('玩家ID'),
            'mengxin' => $this->tinyInteger()->unsigned()->notNull()->comment('是否萌新'),
            'nickname' => $this->string(20)->notNull()->comment('昵称'),
            'card' => $this->string(30)->notNull()->comment('牌型'),
            'gold_new' => $this->integer()->unsigned()->notNull()->comment('开局前元宝'),
            'gold_old' => $this->integer()->unsigned()->notNull()->comment('开局后元宝'),
            'win_gold' => $this->integer()->unsigned()->notNull()->comment('输赢'),
            'operator' => $this->string(500)->notNull()->comment('对局内操作'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_record');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_093920_create_table_log_record cannot be reverted.\n";

        return false;
    }
    */
}
