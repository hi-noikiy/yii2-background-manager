<?php

use yii\db\Migration;

/**
 * Class m181127_124604_cretae_table_log_user_activity
 */
class m181127_124604_cretae_table_log_user_activity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_user_activity', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'player_id' => $this->integer()->unsigned()->notNull()->comment('玩家ID'),
            'activity_id' => $this->integer()->unsigned()->notNull()->comment('活动ID'),
            'operate_type' => $this->tinyInteger()->unsigned()->notNull()->comment('操作类型、1领取、2点击'),
            'is_operate' => $this->tinyInteger()->unsigned()->notNull()->comment('是否操作、1已操作'),
            'operate_count' => $this->integer()->unsigned()->notNull()->defaultValue(1)->comment('操作次数'),
            'operate_time' => $this->dateTime()->notNull()->comment('操作时间'),
            'last_operate' => $this->dateTime()->notNull()->comment('最后操作时间'),
            'UNIQUE KEY `player_operate_key` (`player_id`, `activity_id`, `operate_type`)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_user_activity');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181127_124604_cretae_table_log_user_activity cannot be reverted.\n";

        return false;
    }
    */
}
