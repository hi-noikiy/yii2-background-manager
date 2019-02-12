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

class PayUrlConfig extends ActiveRecord
{
    public static function tableName()
    {
        return 't_pay_url_config';
    }

    public function getAll(){
        $data = (new Query())
            ->select('*')
            ->from('t_pay_url_config')
            ->all();
        return $data;
    }

    public function getIsUseWay($type){
        $data = (new Query())
            ->select('id')
            ->from('t_pay_url_config')
            ->where(['is_use'=>1,'short_name'=>$type])
            ->one();

        return $data;
    }

    public function updateData($id,$data){
        $model = self::findOne(['id'=>$id]);
        foreach ($data as $k => $v) {
            $model -> $k = $v;
        }

        $res = $model -> save();

        return $res;

    }

    public function updateDataByCon($con,$data){
        $model = self::findAll($con);
        foreach ($model as $key=>$val){
            foreach ($data as $k => $v) {
                $val -> $k = $v;
            }
            $res = $val -> save();
        }
        return true;
    }

}