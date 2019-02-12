<?php

use yii\db\Migration;

/**
 * Class m181202_075851_create_table_t_win_lose
 */
class m181202_075851_create_table_t_win_lose extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_win_lose', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'stat_date' => $this->date()->notNull()->comment('统计日期'),
            'game_id' => $this->integer()->unsigned()->notNull()->comment('游戏ID'),
            'player_id' => $this->integer()->unsigned()->notNull()->comment('玩家ID'),
            'player_name' => $this->string(20)->notNull()->comment('玩家昵称'),
            'current_gold' => $this->integer()->unsigned()->notNull()->comment('当前元宝'),
            'counter' => $this->integer()->unsigned()->notNull()->comment('对局数量'),
            'dizhu' => $this->integer()->unsigned()->notNull()->comment('总底住'),
            'counter_res' => $this->integer()->notNull()->comment('总输赢'),
            'win' => $this->integer()->unsigned()->notNull()->comment('赢元宝数'),
            'lose' => $this->integer()->unsigned()->notNull()->comment('输元宝数'),
            'win_count' => $this->integer()->unsigned()->notNull()->comment('赢元宝数'),
            'lose_count' => $this->integer()->unsigned()->notNull()->comment('输元宝数'),
            'rate_win_lose' => $this->float(10,2)->notNull()->comment('输赢比'),
            'gross_yield' => $this->integer()->notNull()->comment('毛收益'),
            'parent_id' => $this->integer()->unsigned()->notNull()->comment('上级ID'),
            'parent_name' => $this->string(30)->notNull()->comment('上级昵称'),
            'top_id' => $this->integer()->unsigned()->notNull()->comment('顶级ID'),
            'top_name' => $this->string(30)->notNull()->comment('顶级昵称'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_win_lose');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_075851_create_table_t_win_lose cannot be reverted.\n";

        return false;
    }
    */
}
