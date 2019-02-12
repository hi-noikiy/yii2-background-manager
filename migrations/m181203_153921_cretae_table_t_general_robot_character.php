<?php

use yii\db\Migration;

/**
 * Class m181203_153921_cretae_table_t_general_robot_character
 */
class m181203_153921_cretae_table_t_general_robot_character extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('t_general_robot_character', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'commont' => $this->string(20)->comment('性格名称'),
            'timeInterval' => $this->string(20)->comment('操作时间间隔'),
            'setoutTime' => $this->string(20)->comment('准备等待时间范围'),
            'leaveTableTime' => $this->string(20)->comment('离桌随机时间'),
            'leaveTableProp' => $this->integer()->comment('离桌概率百分比'),
            'leaveTableMaxGameNum' => $this->string(20)->comment('离桌随机时间'),
            'sendTime' => $this->string(20)->comment('表情 ，忍耐 等 时间间隔的范围'),
            'emojiProp' => $this->integer()->comment('表情发送率'),
            'textProp' => $this->integer()->comment('文本发送率'),
            'waitTime' => $this->integer()->comment('忍耐触发点'),
            'canWaitProp' => $this->integer()->comment('忍耐短信概率'),
            'downLine' => $this->integer()->comment('输钱下限'),
            'upWinProp' => $this->integer()->comment('达到下限后提升的胜率'),
            'upLine' => $this->integer()->comment('赢钱上限'),
            'downWinProp' => $this->integer()->comment('达到上限后下降的胜率'),
            'seePoker' => $this->string(200)->comment('看牌'),
            'openPokerTime' => $this->string(200)->comment('开牌'),
            'disPoker' => $this->string(200)->comment('弃牌'),
            'followBet' => $this->string(200)->comment('跟注'),
            'addBet' => $this->string(200)->comment('加注'),
            'pkPoker' => $this->string(200)->comment('比牌'),
            'qiangzhuang' => $this->string(200)->comment('抢庄'),
            'yafen' => $this->string(200)->comment('叫分'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('t_general_robot_character');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_153921_cretae_table_t_general_robot_character cannot be reverted.\n";

        return false;
    }
    */
}
