<?php
/**
 * User: jw
 * Date: 2018/8/10 0010
 */
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class ActivityEdit extends ActiveRecord
{
    public static function tableName()
    {
        return 'log_edit_activity';
    }

    public function rules()
    {
        return [

        ];
    }

    /**
     * 保存活动操作记录
     */
    public function saveActivityEditLog($id,$old,$new,$type)
    {

        $db = Yii::$app->db;
        $uid = Yii::$app->user->getId()?Yii::$app->user->getId():1;
        if ($type == 1) {//创建
            $db->createCommand()->insert(self::tableName(),['aid'=>$id,'uid'=>$uid,'type'=>$type,'created_time'=>date('Y-m-d H:i:s',time())])->execute();
        } else {//修改
            foreach ($new as $key => $val) {
                foreach ($old as $k => $v) {
                    $content = '';
                    if (($key == $k) && $val != $v) {
                        switch ($k) {
                            case 'title':
                                $content = '标签名称';
                                break;
                            case 'start_time':
                                $content = '开始时间';
                                break;
                            case 'end_time':
                                $content = '结束时间';
                                break;
                            case 'sort':
                                $content = '排序';
                                break;
                            case 'title_url':
                                $content = '标签图片';
                                break;
                            case 'goods_id':
                                $content = '奖励';
                                break;
                            case 'goods_num':
                                $content = '奖励数量';
                                break;
                            case 'img_url':
                                $content = '内容图片';
                                break;
                            case 'jump_type':
                                $content = '跳转方式';
                                break;
                            case 'activity_name':
                                $content = '活动名称';
                                break;
                        }
                        if ($content) {
                            $db->createCommand()->insert(self::tableName(), ['aid' => $id, 'uid' => $uid ? $uid : 1, 'type' => $type, 'content' => $content, 'created_time' => date('Y-m-d H:i:s', time())])->execute();
                        }
                    }
                }
            }
        }
    }
}