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

class IncomeDetails extends CommonModel
{
    public static function tableName()
    {
        return 't_income_details';
    }


    /**
     * 获取获取所有 父级 id的 父级 返利元宝
     *
     * 获取获取所有 祖父级 id的 祖父级 返利元宝
     *
     * $type 类型（1 父级返利-1级返利 ， 2 祖父级返利-2级返利）
     */
    public function getRebateGold($con,$type){
        if($type == 1){
            $sumType = "father_num";
            $groupFildes = "father_id";
        }elseif($type == 2){
            $sumType = "gfather_num";
            $groupFildes = "gfather_id";
        }else{
            return false;
        }
        $data = (new Query())
            ->select('sum('.$sumType.') as num')
            ->from(self::tableName())
            ->where($con)
            ->groupBy($groupFildes)
            ->one();

        return $data['num'];
    }

    public function getInfo($con,$fields = "",$type=1){
        return $this->commonSelect(self::tableName(),$con,$fields,$type);
    }



}