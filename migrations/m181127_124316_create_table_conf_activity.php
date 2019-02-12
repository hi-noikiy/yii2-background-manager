<?php

use yii\db\Migration;

/**
 * Class m181127_124316_create_table_conf_activity
 */
class m181127_124316_create_table_conf_activity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('conf_activity', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'sort' => $this->integer()->unsigned()->notNull()->comment('排序'),
            'start_time' => $this->dateTime()->notNull()->comment('活动开始时间'),
            'end_time' => $this->dateTime()->notNull()->comment('活动结束时间'),
            'title' => $this->string(200)->notNull()->comment('活动标题'),
            'title_url' => $this->string(200)->notNull()->comment('标题图片地址'),
            'goods_id' => $this->string(200)->notNull()->comment('物品ID'),
            'goods_num' => $this->string(200)->notNull()->comment('物品数量'),
            'img_url' => $this->string(100)->notNull()->comment('图片地址'),
            'jump_type' => $this->tinyInteger()->unsigned()->comment('跳转类型'),
            'jump_url' => $this->string(200)->notNull()->comment('跳转地址'),
            'activity_name' => $this->string(200)->notNull()->comment('活动唯一标示'),
            'status' => $this->tinyInteger()->unsigned()->notNull()->comment('状态、1开启、2关闭'),
            'type' => $this->tinyInteger()->unsigned()->notNull()->comment('活动类型、1普通活动、2特殊活动'),
        ]);

        $this->batchInsert('conf_activity', [
            'sort',
            'start_time',
            'end_time',
            'title',
            'title_url',
            'goods_id',
            'goods_num',
            'img_url',
            'jump_type',
            'jump_url',
            'activity_name',
            'status',
            'type',
        ], [
            [1, '2018-11-26 16:39:31', '2019-10-10 00:00:00', '首冲礼包', 'https://oss.601yx.com/uploads/activity/title2.png', 7, 1000, 'https://oss.601yx.com/uploads/activity/content3.png', 2, '4_0', 'shouchong', 1, 1],
            [2, '2018-11-26 16:39:31', '2019-10-10 00:00:00', '新手礼包', 'https://oss.601yx.com/uploads/activity/title1.png', 6, 500, 'https://oss.601yx.com/uploads/activity/content1.png', 2, '4_0', 'xinshou', 1, 0],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('conf_activity');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181127_124316_create_table_conf_activity cannot be reverted.\n";

        return false;
    }
    */
}
