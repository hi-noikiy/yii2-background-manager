<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/9/7
 * Time: 14:05
 */

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Query;

class GoldBusinessPlayer extends ActiveRecord
{
    public static function tableName()
    {
        return 't_gold_business_player';
    }

    /**
     * 获取所有数据
     *
     * @return array
     */
    public function getAll(){
        $tableName = self::tableName();
        return Yii::$app->db->createCommand('SELECT * FROM '.$tableName)->queryAll();
    }

    /**
     * 获取指定字段的数据
     *
     * @param $field
     * @return array
     */
    public function getFields($field){
        $tableName = self::tableName();
        return Yii::$app->db->createCommand('SELECT '.$field.' FROM '.$tableName)->queryAll();
    }

    /**
     * 获取统计数据
     *
     * @param $date
     * @return array
     */
    public function getStatistics($date1,$date2,$limit,$page){
        $where = "sdate >= '".date('Ymd',strtotime($date1))."' AND sdate < '".date('Ymd',strtotime($date2))."'";

        $rows = (new Query())
            ->select('*')
            ->from( 'mdwl_activity.t_oper_goban_statis')
            ->where($where)
            ->limit($limit)
            ->orderBy('sdate')
            ->offset(($page - 1) * $limit)
            ->all();

        $rowsCount = (new Query())
            ->select('*')
            ->from( 'mdwl_activity.t_oper_goban_statis')
            ->where($where)
            ->count();

        $data['rows'] = $rows;
        $data['rowsCount'] = $rowsCount;

        return $data;
    }

    /**
     * 获取五子棋战绩详情
     *
     * @param $tableName
     * @param $page
     * @param $limit
     * @return array
     */
    public function getGobangDetail($tableName,$where,$page,$limit){
        $rows = (new Query())
            ->select('*')
            ->from('mdwl_activity.'.$tableName)
            ->where($where)
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();

        return $rows;
    }

    /**
     * 获取记录条数
     *
     * @param $tableName
     * @param $where
     * @return int|string
     */
    public function getCount($tableName,$where){
        $count = (new Query())
            ->select('*')
            ->from('mdwl_activity.'.$tableName)
            ->where($where)
            ->count();

        return $count;
    }

    /**
     * 修改币商信息
     *
     */
    public function editData($id,$data){
        $model = self::findOne(['BID' => $id]);
        foreach ($data as $k => $v) {
            $model -> $k = $v;
        }
        $res = $model -> save();

        return $res;
    }

    /**
     * 删除
     *
     */
    public function del($id){
        $model = self::findOne(['BID' => $id]);

        return $model ? $model->delete() : false;
    }
}