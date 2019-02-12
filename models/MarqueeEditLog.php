<?php
/**
 * User: jw
 * Date: 2018/8/3 0003
<<<<<<< Updated upstream
 */

namespace app\models;

use Yii;
use yii\base\Model;


class MarqueeEditLog extends Model
{
    public $operate_edit = 1;
    public $operate_del = 2;
    public $operate_play = 3;
    public $operate_pause = 4;
    //修改内容类型
    public $edit_content = 1;
    public $edit_startTime = 2;
    public $edit_endTime = 3;
    public $edit_intervalTime = 4;
    public $edit_isNotice = 5;

    public static function tableName()
    {
        return 'log_edit_marquee';
    }

    public function rules()
    {
        return [
            [['account','marquee_id','operate_type','status','is_play'],'integer'],
            [['created_time','updated_time'],'default','value'=>date('yyyy-MM-dd HH:mm:ss',time())],
            [['content'],'string'],
        ];
    }

    public function addEditLog($marquee_id,$operate,$old_info = '',$new_info = '')
    {
        $info = [];
        if ($operate == $this->operate_edit) {
            $data = [];
            foreach ($old_info as $key=>$value) {
                if (($new_info[$key] != $value) && $key != 'updated_time') {
                    $data[$key] = [
                        'before'=>$value,
                        'after'=>$new_info[$key]
                    ];
                }
            }
            if ($data) {
                $info['content'] = json_encode($data,JSON_UNESCAPED_UNICODE);
            } else {
                return false;
            }
        }
        $info['account'] = 1;//Yii::$app->user->getId();
        $info['marquee_id'] = $marquee_id;
        $info['operate_type'] = $operate;
        $info['status'] = 1;
        $info['created_time'] = date('Y-m-d H:i:s',time());
        $info['updated_time'] = date('Y-m-d H:i:s',time());
        //var_dump($info);exit;
        Yii::$app->db->createCommand()->insert(self::tableName(),$info)->execute();
    }
}
