<?php
/**
 * User: jw
 * Date: 2018/7/23
 */
namespace app\models;

use yii;
use yii\db\ActiveRecord;
use yii\db\Query;

class VipRechargeLog extends CommonModel
{
    public static function tableName()
    {
        return 't_vip_recharge_log';
    }

    public function rules()
    {
        return [
            [['player_id', 'amount','out_amount','operate_user','status','create_time','update_time'], 'required'],
        ];
    }

    public function getData($con,$fields='*',$type=1,$limit=10,$page=1,$group='',$order=''){
        return $this->commonSelect(self::tableName(),$con,$fields='*',$type,$limit,$page,$group,$order);
    }

    /**
     * 分页获取个人的直兑订单记录
     *
     * @param $page
     * @param $limit
     * @param $where -查询条件
     * @return array
     */
    public function getAllLogByPage($limit,$page,$where=[],$field='create_time',$orderType='desc'){
        $list = (new Query())
            ->select("*")
            ->from(self::tableName())
            ->where($where)
            ->orderBy($field." ".$orderType)
            ->limit($limit)
            ->offset(($page-1) * $limit)
            ->all();

        return $list;
    }

    /**
     * 获取页数
     *
     */
    public function getRecordCount($where){
        $count = (new Query())
            ->select("*")
            ->from(self::tableName())
            ->where($where)
            ->count();

        return $count;

    }

    /**
     * vip充值汇总
     *
     */
    public function getSummarizing($where=''){
        $summarizing = (new Query())
            ->select('sum(amount) as sum')
            ->from(self::tableName())
            ->where($where)
            ->scalar();

        return $summarizing;
    }

    /**
     * 新加充值记录
     * @param $data
     */
    public function createRecord($data){
        $db = Yii::$app->db;
        $info = $db->createCommand()->insert('oss.t_vip_recharge_log', [
                        'player_id' => $data['player_id'],
                        'order_id'  =>$data['order_id'],
                        'amount' => $data['amount'],
                        'operate_user' => $data['operate_user'],
                        'out_amount' => $data['out_amount'],
                        'status' => $data['status'],
                        'create_time' => $data['create_time'],
                    ])->execute();
        if($info){
            return true;
        }

        return false;
    }

    public function updateRecord($id,$status){
        return Yii::$app->db->createCommand("update t_vip_recharge_log set status = {$status} where id = {$id}")->execute();
    }

}