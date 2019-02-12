<?php
/**
 * User: SeaReef
 * Date: 2018/7/9 10:11
 *
 * 初始化数据库操作
 */
namespace app\commands;

use Yii;

class InitController extends CommonController
{
    /**
     * 初始化分区表的天数
     */
    const INIT_COUNT = 365;

    /**
     * 初始化台费表、分为365天
     */
    public function actionGoldRecord()
    {
        $this->setTime();
        $db = Yii::$app->db;

        $partition_str = '';
        for ($i = 0; $i < self::INIT_COUNT; $i++) {
            $date = date('Ymd', time() + ($i * 86400));
            $timestamp = strtotime(date('Y-m-d 00:00:00', time() + (($i + 1) * 86400)));
//            var_dump($date, $timestamp);
            $partition_str .= "PARTITION p{$date} VALUES LESS THAN ({$timestamp}) ENGINE = InnoDB,";
        }
        $sql = str_replace("{{PARTITION}}", $partition_str, self::GOLD_RECORD_SQL);

        $info = $db->createCommand($sql)->execute();
        var_dump($info);
    }

    /**
     * 初始化返利表
     */
    public function actionIncomeDetails()
    {
        $this->setTime();
        $db = Yii::$app->db;

        $partition_str = '';
        for ($i = 0; $i < self::INIT_COUNT; $i++) {
            $date = date('Ymd', time() + ($i * 86400));
            $timestamp = strtotime(date('Y-m-d 00:00:00', time() + (($i + 1) * 86400)));
//            var_dump($date, $timestamp);
            $partition_str .= "PARTITION p{$date} VALUES LESS THAN ({$timestamp}) ENGINE = InnoDB,";
        }
        $sql = str_replace("{{PARTITION}}", $partition_str, self::INCOME_DETAILS);

        $info = $db->createCommand($sql)->execute();
        var_dump($info);
    }

    /**
     * 初始化 牌桌战绩 记录表、分为365天
     *
     */
    public function actionGameRecord()
    {
        $this->setTime();
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
     * 初始化 玩家战绩 记录表、分为365天
     *
     */
    public function actionGamePlayerRecord()
    {
        $this->setTime();
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
     * 2019-01-24、新增百人场战绩分区表
     * 初始化百人场战绩玩家表
     */
    public function actionHundredPlayer()
    {
        $this->setTime();
        $db = Yii::$app->db;

        $partition_str = '';
        for ($i = -60; $i < self::INIT_COUNT; $i++) {
            $date = date('Ymd', time() + ($i * 86400));
            $timestamp = date('Y-m-d', time() + (($i + 1) * 86400));
//            var_dump($date, $timestamp);
            $partition_str .= "PARTITION p{$date} VALUES LESS THAN ('{$timestamp}') ENGINE = InnoDB,";
        }
        $sql = str_replace("{{PARTITION}}", $partition_str, self::GAME_HUNDRED_PLAYER);

        $info = $db->createCommand($sql)->execute();
        var_dump($info);
    }

    /**
     * 2019-01-24、新增百人场战绩分区表
     */
    public function actionHundredPlayerRecord()
    {
        $this->setTime();
        $db = Yii::$app->db;

        $partition_str = '';
        for ($i = -60; $i < self::INIT_COUNT; $i++) {
            $date = date('Ymd', time() + ($i * 86400));
            $timestamp = date('Y-m-d', time() + (($i + 1) * 86400));
//            var_dump($date, $timestamp);
            $partition_str .= "PARTITION p{$date} VALUES LESS THAN ('{$timestamp}') ENGINE = InnoDB,";
        }
        $sql = str_replace("{{PARTITION}}", $partition_str, self::GAME_HUNDRED_PLAYER_RECORD);

        $info = $db->createCommand($sql)->execute();
        var_dump($info);
    }


    /***************************************************分割线*************************************************/
//    玩家日志表
    const GAME_HUNDRED_PLAYER = <<<STR
CREATE TABLE `log_hundred_game_record444` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) DEFAULT NULL COMMENT '游戏id',
  `date` datetime DEFAULT NULL COMMENT '时间',
  `gold_pool` int(11) DEFAULT NULL COMMENT '当前奖池金币数',
  `income_gold` int(11) DEFAULT NULL COMMENT '金币回收总数',
  `player_id` int(11) DEFAULT NULL COMMENT '庄家id',
  `take_gold` int(11) DEFAULT NULL COMMENT '携带金币',
  `poker_str_1` varchar(10) DEFAULT NULL COMMENT '庄家牌型',
  `win_2` tinyint(1) DEFAULT NULL COMMENT '顺门输赢',
  `win_num_2` int(11) DEFAULT NULL COMMENT '顺门下注额',
  `poker_str_2` varchar(10) DEFAULT NULL COMMENT '顺门牌型',
  `win_3` tinyint(1) DEFAULT NULL COMMENT '天门输赢',
  `win_num_3` int(11) DEFAULT NULL COMMENT '天门下注额',
  `poker_str_3` varchar(10) DEFAULT NULL COMMENT '天门牌型',
  `win_4` tinyint(1) DEFAULT NULL COMMENT '地门输赢',
  `win_num_4` int(11) DEFAULT NULL COMMENT '地门下注额',
  `poker_str_4` varchar(10) DEFAULT NULL COMMENT '地门牌型',
  `robot_change_gold` int(11) DEFAULT NULL COMMENT '机器人输赢金币',
  `service_fee` int(11) DEFAULT NULL COMMENT '玩家服务费',
  `created_time` datetime NOT NULL DEFAULT '1000-01-01 00:00:00' COMMENT '创建时间',
  `updated_time` datetime NOT NULL DEFAULT '1000-01-01 00:00:00' COMMENT '更新时间',
  `playerWinNum` int(11) DEFAULT NULL COMMENT '真实玩家输钱数',
  `playerLoseNum` int(11) DEFAULT NULL COMMENT '真实玩家输钱数',
  PRIMARY KEY (`id`, `created_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
PARTITION BY RANGE COLUMNS (created_time) (
    {{PARTITION}}
    PARTITION pmax VALUES LESS THAN ('9999-12-31')
);
STR;

//    玩家战绩表
    const GAME_HUNDRED_PLAYER_RECORD = <<<STR
CREATE TABLE `log_hundred_game_player_record444` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT,
  `gid` int(11) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL COMMENT '对局记录id',
  `robot_type` tinyint(3) DEFAULT NULL COMMENT '玩家类型0为人，1、2机器人',
  `player_id` int(11) DEFAULT NULL,
  `zhuang` tinyint(3) DEFAULT NULL COMMENT '是否是庄家',
  `win_num` int(11) DEFAULT NULL COMMENT '玩家输赢数',
  `date` datetime DEFAULT NULL COMMENT '牌局结束时间',
  `created_time` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `updated_time` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY `id_create_pkey`(`id`, `created_time`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8
PARTITION BY RANGE COLUMNS (created_time) (
    {{PARTITION}}
    PARTITION pmax VALUES LESS THAN ('9999-12-31')
);
STR;


    /**
     * 台费表
     * mysql的单键存储的是local模式、如果分区表使用了复合主键、不能保证单键是唯一性、
     */
    const GOLD_RECORD_SQL = <<<STR
CREATE TABLE `t_gold_record` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT,
    `channel_id` VARCHAR(20) NOT NULL COMMENT '渠道ID',
    `gid` MEDIUMINT UNSIGNED NOT NULL COMMENT '游戏ID',
    `player_id` int unsigned NOT NULL COMMENT '玩家ID',
    `father_id` int unsigned NOT NULL COMMENT '父级ID',
    `gfather_id` int unsigned NOT NULL COMMENT '祖父级ID',
    `ggfather_id` int unsigned NOT NULL COMMENT '曾祖父级ID',
    `order_id` varchar(55) NOT NULL DEFAULT '0' COMMENT '唯一订单号（时间戳+桌号+uid）',
    `num` smallint unsigned NOT NULL DEFAULT '0' COMMENT '台费金额',
    `type` tinyint NOT NULL DEFAULT '0' COMMENT '类型/1元宝/9返利结余',
    `level` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '台费等级',
    `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0未处理/1已处理',
    `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    KEY `player_id_key` (`player_id`),
    KEY `father_id_key` (`father_id`),
    KEY `gfather_id_key` (`gfather_id`),
    KEY `ggfather_id_key` (`ggfather_id`),
    KEY `order_id_key` (`order_id`),
    PRIMARY KEY `id_create_time` (`id`, `create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
PARTITION BY RANGE (create_time) (
    {{PARTITION}}
    PARTITION pmax VALUES LESS THAN (MAXVALUE)
);
STR;


    /**
     * 返利详情表
     */
    const INCOME_DETAILS = <<<STR
CREATE TABLE `t_income_details` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT,
    `player_id` INT UNSIGNED NOT NULL COMMENT '玩家ID',
    `father_id` int unsigned NOT NULL COMMENT '父级ID',
    `gfather_id` int unsigned NOT NULL COMMENT '祖父级ID',
    `ggfather_id` int unsigned NOT NULL COMMENT '曾祖父级ID',
    `father_num` FLOAT(10,2) unsigned NOT NULL COMMENT '父级返利元宝',
    `gfather_num` FLOAT(10,2) unsigned NOT NULL COMMENT '祖父级返利元宝',
    `ggfather_num` FLOAT(10,2) unsigned NOT NULL COMMENT '曾祖父级返利元宝',
    `create_time` INT unsigned NOT NULL COMMENT '创建时间',
    PRIMARY KEY `id_create_time` (`id`, `create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
PARTITION BY RANGE (create_time) (
    {{PARTITION}}
    PARTITION pmax VALUES LESS THAN (MAXVALUE)
);
STR;

    /**
     * 牌桌战绩记录表
     * mysql的单键存储的是local模式、如果分区表使用了复合主键、不能保证单键是唯一性、
     */
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
) ENGINE=InnoDB AUTO_INCREMENT=160637 DEFAULT CHARSET=utf8
PARTITION BY RANGE (created_time) (
    {{PARTITION}}
    PARTITION pmax VALUES LESS THAN (MAXVALUE)
);
STR;

    /**
     * 玩家战绩记录表
     * mysql的单键存储的是local模式、如果分区表使用了复合主键、不能保证单键是唯一性、
     */
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
--   `created_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_time` INT unsigned NOT NULL COMMENT '创建时间',
  `updated_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY `id_create_time` (`id`, `created_time`)
) ENGINE=InnoDB AUTO_INCREMENT=435928 DEFAULT CHARSET=utf8
PARTITION BY RANGE (created_time) (
    {{PARTITION}}
    PARTITION pmax VALUES LESS THAN (MAXVALUE)
);

STR;

}
