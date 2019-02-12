<?php
namespace app\models;

use yii;
use yii\db\ActiveRecord;
use yii\db\Query;

class Index extends ActiveRecord
{
    /**
     * 首页在线玩家统计
     */
    public function convertOnlinePlayer($start,$end = '',$channel_id=1)
    {
        if (!$end) {
            $where = 'channel_id='.$channel_id.' and unix_timestamp(stat_time) >='.$start;
        } else {
            $where = 'channel_id='.$channel_id.' and unix_timestamp(stat_time) >='.$start.' and unix_timestamp(stat_time) <='.$end;
        }
        return (new Query())
            ->select("DATE_FORMAT(stat_time,'%H:%i') as `time`,num")
            ->from('t_real_online')
            ->where($where)
            ->orderBy('stat_time')
            ->all();

    }
}