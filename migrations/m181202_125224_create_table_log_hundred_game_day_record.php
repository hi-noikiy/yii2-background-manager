<?php

use yii\db\Migration;

/**
 * Class m181202_125224_create_table_log_hundred_game_day_record
 */
class m181202_125224_create_table_log_hundred_game_day_record extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_hundred_game_day_record', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'gid' => $this->string(20)->notNull()->defaultValue('')->comment('游戏ID'),
            'date' => $this->dateTime()->comment('时间'),
            'game_count' => $this->integer()->notNull()->defaultValue(0)->comment('游戏对局数'),
            'gold_pool' => $this->integer()->notNull()->defaultValue(0)->comment('奖池金币数'),
            'income_gold' => $this->integer()->notNull()->defaultValue(0)->comment('金币回收总数'),
            'service_fee' => $this->integer()->notNull()->defaultValue(0)->comment('服务费总收入'),
            'shun_men' => $this->integer()->notNull()->defaultValue(0)->comment('顺门投注总数'),
            'tian_men' => $this->integer()->notNull()->defaultValue(0)->comment('天门投注总数'),
            'di_men' => $this->integer()->notNull()->defaultValue(0)->comment('地门投注总数'),
            'player_num' => $this->integer()->notNull()->defaultValue(0)->comment('玩家人数'),
            'zhuang_num' => $this->integer()->notNull()->defaultValue(0)->comment('上庄人数'),
            'zhuang_count' => $this->integer()->notNull()->defaultValue(0)->comment('上庄次数'),
            'total_lose' => $this->integer()->notNull()->defaultValue(0)->comment('真实玩家输钱数'),
            'total_win' => $this->integer()->notNull()->defaultValue(0)->comment('真实玩家赢钱数'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_hundred_game_day_record');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_125224_create_table_log_hundred_game_day_record cannot be reverted.\n";

        return false;
    }
    */
}
