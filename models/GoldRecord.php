<?php
/**
 * User: SeaReef
 * Date: 2018/7/9 11:57
 *
 * 台费表模型
 */
namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class GoldRecord extends ActiveRecord
{
    /**
     * 代币类型、1、元宝
     */
    const GOLD = 1;

    const ORDER_FINISHED = 1;

    const ORDER_UNFINISHED = 0;

    public static function tableName()
    {
        return $table_name = 't_gold_record__' . date('Ymd', time());
   }

    /**
     * 添加台费
     */
    public function addRecord($data, $gid)
    {
        if (array_key_exists('channel', $data))
            $this->channel_id = $data['channel'] ? : 1;
        elseif(array_key_exists("channel_id", $data)){
            $this->channel_id = $data['channel_id'] ? : 1;
        }
        $this->gid = $data['gid'];
        $this->player_id = $data['player_id'];
        $this->order_id = $data['order_id'];
        $this->num = $data['spend_gold'];
        $this->type = '1';
        $this->level = $data['level'];
        $this->status = 0;
        $this->create_time = strtotime($data['time']);
        $this->update_time = time();

        $this->save();
    }
}