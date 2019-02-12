<?php
/**
 * User: jw
 * Date: 2018/8/10 0010
 */
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Activity extends ActiveRecord
{
    /**
     * 活动有效
     */
    const ACTIVITY_EFFECTIVE = 1;

    /**
     * 活动失效
     */
    const ACTIVITY_INVALID = 0;


    public static function tableName()
    {
        return 'conf_activity';
    }

    public function rules()
    {
        return [
            [['sort','title_url','img_url',
                'content_img',
                'jump_url',
                'activity_name'
                ],'safe'],
            ['title','string','max'=>'8'],
            [['start_time','end_time'],'date','format'=> 'yyyy-MM-dd HH:mm:ss'],
            [['goods_id','goods_num','jump_type','status'],'integer'],
        ];
    }

    public function getJump($data,$father_id)
    {
        //global $category;
        $arr = array();
        foreach($data as $key => $val){
            //对每个分类进行循环。
            if($data[$key]['father_id'] == $father_id){ //如果有子类
                $val['child'] = $this->getJump($data,$val['id']); //调用函数，传入参数，继续查询下级
                $arr[] = $val; //组合数组
            }
        }
        return $arr;
    }

    /**
     * @params $activity_id、活动id
     */
    public static function isValid($activity_id)
    {
        $time = date('Y-m-d H:i:s', time());

        return self::find()
            ->select('*')
            ->where(['and', "id = '{$activity_id}'", "start_time < '{$time}'", "end_time > '{$time}'", "status =" . self::ACTIVITY_EFFECTIVE])
            ->asArray()
            ->one();
    }
}