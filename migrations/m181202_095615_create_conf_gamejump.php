<?php

use yii\db\Migration;

/**
 * Class m181202_095615_create_conf_gamejump
 */
class m181202_095615_create_conf_gamejump extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('conf_gamejump', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'father_id' => $this->tinyInteger()->unsigned()->notNull()->comment('父级ID'),
            'jump_id' => $this->string(50)->notNull()->comment('跳转ID'),
            'remark' => $this->string(50)->notNull()->comment('描述'),
        ]);

        $this->batchInsert('conf_gamejump', [
            'father_id',
            'jump_id',
            'remark',
        ], [
            [0, '1', '跳转子游戏'],
            [0, '2', '跳转个人信息'],
            [0, '3', '跳转客服'],
            [0, '4', '跳转商城'],
            [0, '5', '跳转分享'],
            [0, '6', '跳转排行榜'],
            [0, '7', '跳转邮件'],
            [0, '8', '跳转活动'],
            [0, '9', '跳转保险箱'],
            [0, '10', '跳转战绩'],
            [0, '11', '跳转更多'],
            [0, '12', '跳转加入房间'],
            [0, '13', '跳转发送广播'],
            [0, '14', '跳转安全验证'],

            [1, '524804', '五子棋'],
            [1, '524804', '山西麻将'],
            [1, '524804', '扎金花'],
            [1, '524804', '内蒙麻将'],
            [1, '524804', '打大a'],
            [1, '524804', '跑胡子'],
            [1, '524804', '拼十'],
            [1, '524804', '跑得快'],
            [1, '524804', '三公'],
            [1, '524804', '一元匹配场'],
            [1, '524804', '推筒子'],
            [1, '524804', '新炸金花'],
            [1, '524804', '填大坑'],
            [1, '524804', '新拼十'],
            [1, '524804', '百人推筒子'],
            [1, '524804', '拼十'],
            [1, '524804', '跑得快'],
            [1, '524804', '三公'],

            [2, '0', '个人信息主界面'],

            [3, '0', '客服主界面'],

            [4, '0', '商城主界面'],
            [4, '1', '10元充值'],
            [4, '2', '50元充值'],
            [4, '3', '100元充值'],
            [4, '4', '300元充值'],
            [4, '5', '500元充值'],
            [4, '6', '1000元充值'],

            [5, '0', '分享主界面'],
            [5, '1', '分享微信好友'],
            [5, '2', '分享朋友圈'],

            [6, '0', '排行榜主界面'],

            [7, '0', '邮件主界面'],

            [8, '0', '活动主界面'],
            [8, '1', '新手礼包'],
            [8, '2', '首冲礼包'],
            [8, '3', '每日活动'],

            [9, '0', '保险箱主界面'],

            [10, '0', '战绩主界面'],

            [11, '0', '更多主界面'],

            [12, '0', '加入房间主界面'],

            [13, '0', '发送广播主界面'],

            [14, '0', '安全验证主界面'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('conf_gamejump');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181202_095615_create_conf_gamejump cannot be reverted.\n";

        return false;
    }
    */
}
