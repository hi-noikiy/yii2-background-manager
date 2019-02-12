<?php

use yii\db\Migration;

/**
 * Class m181202_094710_create_table_player_report
 */
class m181202_094710_create_table_player_report extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('player_report', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'playerid' => $this->integer()->unsigned()->notNull()->comment('玩家ID、举报人'),
            'be_report' => $this->integer()->unsigned()->notNull()->comment('被举报人'),
            'tableid' => $this->integer()->notNull()->comment('当前桌号'),
            'gid' => $this->integer()->unsigned()->notNull()->comment('游戏ID'),
            'option1' => $this->tinyInteger()->notNull()->comment('举报1、外挂嫌疑'),
            'option2' => $this->tinyInteger()->notNull()->comment('举报2、合伙作弊'),
            'option3' => $this->tinyInteger()->notNull()->comment('举报3、言语辱骂/地域歧视'),
            'option4' => $this->tinyInteger()->notNull()->comment('举报4、恶意刷屏'),
            'option5' => $this->string(100)->notNull()->defaultValue('')->comment('文字举报'),
            'create_time' => $this->dateTime()->notNull()->comment('举报时间'),
            'mobile' => $this->string(15)->comment('手机号'),
            'qq' => $this->string(15)->comment('qq号'),
            'wechat' => $this->string(15)->comment('微信号'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('player_report');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_094710_create_table_player_report cannot be reverted.\n";

        return false;
    }
    */
}
