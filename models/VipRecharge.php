<?php
/**
 * User: moyu
 * Date: 2018/11/2
 *
 * vip充值账号表
 */
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

class VipRecharge extends ActiveRecord
{
    public static function tableName()
    {
        return 't_vip_recharge';
    }

    /**
     * 获取vip账号列表
     *
     * @param array $where
     * @return array
     */
    public function getAllVipList($limit,$page,$where=[]){
        $list = (new Query())
            ->select('*')
            ->from(self::tableName())
            ->where($where)
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();

        return $list;
    }

    /**
     * 获取vip账号数量
     *
     * @param array $where
     * @return int|string
     */
    public function getAllVipCount($where=[]){
        $count = (new Query())
            ->select('id')
            ->from(self::tableName())
            ->where($where)
            ->count();

        return $count;
    }

    public function getOne($id){
        $info = (new Query())
            ->select('*')
            ->from(self::tableName())
            ->where(['id'=>$id])
            ->one();

        return $info;
    }

    public function addVipRecharge($data){
        $model = new VipRecharge();
        foreach ($data as $k => $v) {
            $model -> $k = $v;
        }
        if ($model->save()) {
            return true;
        }

        return false;
    }

    public function updateVipRecharge($id,$data){
        $model = self::findOne(['id' => $id]);
        foreach ($data as $k => $v) {
            $model -> $k = $v;
        }
        $res = $model -> save();
        return $res;
    }

    public function removeVipRecharge($id){
        $model = self::findOne(['id' => $id]);
        $res = $model -> delete();

        return $res;
    }

}