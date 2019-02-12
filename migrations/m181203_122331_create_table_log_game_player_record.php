<?php

use yii\db\Migration;

/**
 * Class m181203_122331_create_table_log_game_player_record
 */
class m181203_122331_create_table_log_game_player_record extends Migration
{
    const INIT_COUNT = 365;

    const GAME_PLAYER_RECORD = <<<STR
CREATE TABLE `log_game_player_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` int(10) unsigned NOT NULL COMMENT '一场游戏记录的关联的id',
  `player_id` int(10) unsigned NOT NULL COMMENT '玩家id',
  `nickname` varchar(50) NOT NULL COMMENT '昵称',
  `mengxin` tinyint(3) unsigned DEFAULT NULL COMMENT '是否萌新',
  `player_card` varchar(30) NOT NULL COMMENT '玩家牌型',
  `gold_new` int(10) unsigned NOT NULL COMMENT '场后元宝',
  `gold_old` int(10) unsigned NOT NULL COMMENT '场前元宝',
  `operate` varchar(500) NOT NULL COMMENT '玩家操作',
  `portrait` varchar(100) DEFAULT NULL COMMENT '玩家头像',
  `table_pos` tinyint(4) NOT NULL COMMENT '玩家牌桌位置',
  `win_gold` int(10) NOT NULL COMMENT '元宝输赢',
  `created_time` INT unsigned NOT NULL COMMENT '创建时间',
  `updated_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY `id_create_time` (`id`, `created_time`)
) ENGINE=InnoDB AUTO_INCREMENT=435928 DEFAULT CHARSET=utf8
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
        $sql = str_replace("{{PARTITION}}", $partition_str, self::GAME_PLAYER_RECORD);

        $info = $db->createCommand($sql)->execute();
        var_dump($info);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('log_game_player_record');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181203_122331_create_table_log_game_player_record cannot be reverted.\n";

        return false;
    }
    */
}
