<?php

use yii\db\Migration;

/**
 * Class m181203_121752_create_table_log_game_record
 */
class m181203_121752_create_table_log_game_record extends Migration
{
    const INIT_COUNT = 365;

    const GAME_RECORD = <<<STR
CREATE TABLE `log_game_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` varchar(20) NOT NULL COMMENT '渠道ID',
  `gid` mediumint(8) unsigned NOT NULL COMMENT '游戏ID',
  `table_id` int(10) unsigned NOT NULL COMMENT '桌号',
  `dizhu` int(10) unsigned NOT NULL COMMENT 'd底注',
  `player_num` tinyint(4) NOT NULL COMMENT '玩家数量',
  `start_time` datetime NOT NULL COMMENT '开始时间',
  `end_time` datetime NOT NULL COMMENT '结束时间',
  `operation_logs` varchar(20000) NOT NULL COMMENT '操作记录',
  `player_method` varchar(500) NOT NULL COMMENT '牌桌信息',
  `created_time` INT unsigned NOT NULL COMMENT '创建时间',
  `updated_time` datetime DEFAULT NULL,
  PRIMARY KEY `id_create_time` (`id`, `created_time`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
PARTITION BY RANGE (created_time) (
    {{PARTITION}}
    PARTITION pmax VALUES LESS THAN (MAXVALUE)
);
STR;


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $db = Yii::$app->db;

        $partition_str = '';
        for ($i = 0; $i < self::INIT_COUNT; $i++) {
            $date = date('Ymd', time() + ($i * 86400));
            $timestamp = strtotime(date('Y-m-d 00:00:00', time() + (($i + 1) * 86400)));
//            var_dump($date, $timestamp);
            $partition_str .= "PARTITION p{$date} VALUES LESS THAN ({$timestamp}) ENGINE = InnoDB,";
        }
        $sql = str_replace("{{PARTITION}}", $partition_str, self::GAME_RECORD);

        $info = $db->createCommand($sql)->execute();
        var_dump($info);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_game_record');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_121752_create_table_log_game_record cannot be reverted.\n";

        return false;
    }
    */
}
