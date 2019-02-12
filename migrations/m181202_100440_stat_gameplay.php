<?php

use yii\db\Migration;

/**
 * Class m181202_100440_stat_gameplay
 */
class m181202_100440_stat_gameplay extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stat_gameplay', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'stat_date' => $this->dateTime()->notNull()->comment('统计日期'),
            'channel_id' => $this->string(20)->notNull()->comment('渠道ID'),
            'game_id' => $this->string(20)->notNull()->comment('游戏ID'),
            'player_number' => $this->integer()->unsigned()->notNull()->comment('参与人数'),
            'player_times' => $this->integer()->unsigned()->notNull()->comment('参与人次'),
            'consume' => $this->float(10, 2)->notNull()->comment('消耗'),
            'ratio_number' => $this->float(10, 2)->notNull()->comment('环比人数'),
            'ratio_times' => $this->float(10, 2)->notNull()->comment('环比人次'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('stat_gameplay');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_100440_stat_gameplay cannot be reverted.\n";

        return false;
    }
    */
}
