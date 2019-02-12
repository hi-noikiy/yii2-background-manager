<?php
/**
 * User: SeaReef
 * Date: 2018/7/17 10:09
 *
 * 返利控制器
 */
namespace app\commands;

use app\models\PlayerMember;
use Yii;
use yii\console\Controller;

class RebateController extends Controller
{
    /**
     * 配置返利比例
     */
    const FATHER_EXTRACT = 0.35;

    const GFATHER_EXTRACT = 0.1;

    const GGFATHER_EXTRACT = 0.05;

    /**
     * 元宝比例
     */
    const GOLD_RATE = 1.1;

    const REBATE_RATION = 'rebate_ratio';

    /**
     * 计算每个人的台费返利
     * 所有截止时间之前的返利全部轮询
     *
     * 不进行事务操作增加mysql负担、
     * 所有合理不能成功执行的sql操作、单独输入到错误日志中、手动执行
     */
    public function actionTableFee($end_time = 0)
    {
        $db = Yii::$app->db;

        $end_time = $end_time ? strtotime($end_time) : time();
        $table_name = 't_gold_record__' . date('Ymd', time());

        $data = $db->createCommand($sql = "SELECT * FROM `{$table_name}` WHERE create_time <= '{$end_time}' AND type = 1 AND status = 0")->queryAll();

        if (!empty($data)) {
            $father_extract = self::FATHER_EXTRACT;
            $gfather_extract = self::GFATHER_EXTRACT;
            $ggfather_extract = self::GGFATHER_EXTRACT;

            $father_num = $gfather_num = $ggfather_num = 0;

            foreach ($data as $v) {
//                开启事务保证更新数据正确
                $transaction = $db->beginTransaction();
                try {
                    //            更改上三级的返利信息、记录每一笔的返利详情、修改台费表的状态值
                    if (!($v['father_id'] == 999 || $v['father_id'] == 0)) {
                        $father_num = round(($v['num'] * $father_extract) / self::GOLD_RATE, 2);
                        $res = $db->createCommand($sql = "UPDATE t_daili_player SET pay_back_gold = pay_back_gold + '{$father_num}', all_pay_back_gold = all_pay_back_gold + '{$father_num}' WHERE player_id = '{$v['father_id']}'")->execute();
                        if (!$res) {
                            $transaction->rollBack();
                            file_put_contents('/tmp/rebate_table_fee.log', print_r([$sql, date('Y-m-d H:i:s')], 1) . "\r\n", FILE_APPEND);
                        }
                    }
                    if (!($v['gfather_id'] == 999 || $v['gfather_id'] == 0)) {
                        $gfather_num = round(($v['num'] * $gfather_extract) / self::GOLD_RATE, 2);
                        $res = $db->createCommand($sql1 = "UPDATE t_daili_player SET pay_back_gold = pay_back_gold + '{$gfather_num}', all_pay_back_gold = all_pay_back_gold + '{$gfather_num}' WHERE player_id = '{$v['gfather_id']}'")->execute();
                        if (!$res) {
                            $transaction->rollBack();
                            file_put_contents('/tmp/rebate_table_fee.log', print_r([$sql1, date('Y-m-d H:i:s')], 1) . "\r\n", FILE_APPEND);
                        }
                    }
                    if (!($v['ggfather_id'] == 999 || $v['ggfather_id'] == 0)) {
                        $ggfather_num = round(($v['num'] * $ggfather_extract) / self::GOLD_RATE, 2);
                        $res = $db->createCommand($sql2 = "UPDATE t_daili_player SET pay_back_gold = pay_back_gold + '{$ggfather_num}', all_pay_back_gold = all_pay_back_gold + '{$ggfather_num}' WHERE player_id = '{$v['ggfather_id']}'")->execute();
                        if (!$res) {
                            $transaction->rollBack();
                            file_put_contents('/tmp/rebate_table_fee.log', print_r([$sql2, date('Y-m-d H:i:s')], 1) . "\r\n", FILE_APPEND);
                        }
                    }

                    $res = $db->createCommand($sql3 = "INSERT INTO t_income_details VALUES(NULL, 1, '{$v['gid']}', '{$v['player_id']}', '{$v['father_id']}', '{$v['gfather_id']}', '{$v['ggfather_id']}', '{$v['num']}', '{$father_num}', '{$gfather_num}', '{$ggfather_num}', '{$end_time}')")->execute();

                    if (!$res) {
                        $transaction->rollBack();
                        file_put_contents('/tmp/rebate_table_fee.log', print_r([$sql3, date('Y-m-d H:i:s')], 1) . "\r\n", FILE_APPEND);
                    }

                    $res = $db->createCommand($sql4 = "UPDATE `{$table_name}` SET status = 1, update_time = '{$end_time}' WHERE id = '{$v['id']}'")->execute();
                    if (!$res) {
                        $transaction->rollBack();
                        file_put_contents('/tmp/rebate_table_fee.log', print_r([$sql4, date('Y-m-d H:i:s')], 1) . "\r\n", FILE_APPEND);
                    }

                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }
    }

    /**
     * 定期生成台费订单
     */
    public function actionGenerateGoldRecord($t = 1)
    {
        $t = time() + 86400 * $t;
        $table_name = 't_gold_record__' . date('Ymd', $t);
//        echo $table_name;
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $table_name . ' LIKE t_gold_record';
        $db = Yii::$app->db;
        $db->createCommand($sql)->execute();
    }

    /**
     * 生成台费订单
     */
    public function actionInitGoldRecord()
    {
        $db = Yii::$app->db;
        $db->createCommand(self::TABLE_GOLD_RECORD)->execute();
    }

    const TABLE_GOLD_RECORD = <<<STR
CREATE TABLE IF NOT EXISTS t_gold_record (
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `channel_id` VARCHAR(20) NOT NULL COMMENT '渠道ID',
    `gid` MEDIUMINT UNSIGNED NOT NULL COMMENT '游戏ID',
    `player_id` INT UNSIGNED NOT NULL COMMENT '玩家ID',
    `father_id` INT UNSIGNED NOT NULL COMMENT '父级ID',
    `gfather_id` int(10) unsigned NOT NULL COMMENT '祖父级ID',
    `ggfather_id` int(10) unsigned NOT NULL COMMENT '曾祖父级ID',
    `order_id` varchar(55) NOT NULL DEFAULT '0' COMMENT '唯一订单号（时间戳+桌号+uid）',
    `num` smallint unsigned NOT NULL DEFAULT '0' COMMENT '台费金额',
    `type` tinyint NOT NULL DEFAULT '0' COMMENT '类型/1元宝/9返利结余',
    `level` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '台费等级',
    `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '0未处理/1已处理',
    `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    UNIQUE KEY `order_id_key` (`order_id`)
)ENGINE=INNODB CHARSET=UTF8
STR;
}
