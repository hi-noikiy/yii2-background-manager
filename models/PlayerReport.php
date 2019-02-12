<?php
/**
 * User: SeaReef
 * Date: 2018/8/21 16:22
 *
 * 玩家表
 */
namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Query;

class PlayerReport extends ActiveRecord
{
    public static function tableName()
    {
        return 'player_report';
    }

    /**
     * 分页获取所有数据
     *
     * @param $con
     * @param $page
     * @param $limit
     * @return array
     */
    public function getAll($con,$page,$limit,$field='create_time',$orderType='desc',$filterWhere){
        $rows = (new Query())
            ->select('*')
            ->from('player_report')
            ->where($con)
            ->andFilterWhere($filterWhere)
            ->orderBy($field.' '.$orderType)
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->all();

        return $rows;
    }

    /**
     * 获取所有符合条件的数量
     *
     * @param $con
     * @return int|string
     */
    public function getCount($con,$filterWhere){
        $count = (new Query())
            ->select('*')
            ->from('player_report')
            ->where($con)
            ->andFilterWhere($filterWhere)
            ->count();

        return $count;
    }

    /**
     * 获取单条记录
     *
     * @param $con
     * @return array|bool
     */
    public function getOne($con,$fields){
        $player = (new Query())
            ->select($fields)
            ->from('player_report')
            ->where($con)
            ->one();

        return $player;
    }

    /**
     * 获取举报次数
     *
     * @param $con
     * @param $fields
     * @return array
     */
    public function getReportTime($con,$fields){
        $reportCount = (new Query())
            ->select($fields)
            ->from('player_report')
            ->where($con)
            ->one();

        return $reportCount;
    }
}