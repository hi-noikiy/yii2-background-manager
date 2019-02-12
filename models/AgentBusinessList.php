<?php
/**
 * User: SeaReef
 * Date: 2018/7/12 21:25
 *
 * 代理控制器
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

class AgentBusinessList extends CommonModel
{
    public static function tableName()
    {
        return 'agent_business_list';
    }

    /**
     * 根据条件获取数据
     *
     * @param $con
     * @param string $fields
     * @param int $type $type 查询类型 1查询所有 2查询一条 3标量查询
     * @return bool|mixed
     */
    public function getDataByCon($con, $fields = "*", $type = 1,$limit=10,$page=1,$group='')
    {
        return $this->commonSelect(self::tableName(), $con, $fields, $type,$limit,$page,$group);
    }

    public function getDataByPage($where,$limit,$page){
        $data = (new Query())
            ->select('*')
            ->from(self::tableName())
            ->where($where)
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();

        return $data;
    }

    public function getDataCount($where,$channelUnderList){
        return (new Query())
            ->select('id')
            ->from(self::tableName())
            ->where($where)
            ->andFilterWhere(['in','agent_id',$channelUnderList])
            ->count();
    }

    public function getData($con,$fields,$limit,$page,$orderType,$field,$channel_under_list){
        $data = (new Query())
            ->select($fields)
            ->from(self::tableName())
            ->where($con)
            ->andFilterWhere(['in','agent_id',$channel_under_list])
            ->orderBy($field." ".$orderType)
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();

        return $data;
    }

    public function updateData($playerId,$date,$data){
        $model = self::findOne(['agent_id'=>$playerId,'stat_date'=>$date]);
        if($model){
            foreach ($data as $key=>$val){
                $model->$key = $val;
            }

            if($model->save()){
                return true;
            }
        }

        return false;
    }

}