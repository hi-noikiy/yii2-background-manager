<?php
/**
 * User: jw
 * Date: 2018/7/26 0026
 */
namespace app\models;

use yii;
use yii\db\ActiveRecord;

class GameEmailLog extends ActiveRecord
{
    //对game_email操作类型
    public $email_add = 1;
    public $email_update = 2;
    public $email_delete = 3;

    public static function tableName()
    {
        return 'log_game_email';
    }

    public function rules()
    {
        return [
            ['email_id','required'],
            [['old_content','new_content'],'string']
        ];
    }

    /**
     * 插入邮件修改记录
     * @param $operate
     * @param $email_id
     * @param $old_info
     * @param $new_info
     * @return bool
     */
    public function LogInfo($operate,$email_id,$old_info = '',$new_info = '')
    {
        $account = Yii::$app->user->getId()?Yii::$app->user->getId():'';
        if ($operate == $this->email_delete) {
            $data['account'] = $account;
            $data['email_id'] = $email_id;
            $data['old_content'] = '';
            $data['new_content'] = '';
            $data['operate_type'] = $operate;
            $data['content_type'] = 0;
            $data['time'] = date('Y-m-d H:i:s',time());
            $records[] = $data;

        } else if ($operate == $this->email_update) {
            foreach ($old_info as $key=>$value) {
                $data = [];
                if ($value != $new_info[$key]) {
                    $data['account'] = $account;
                    $data['email_id'] = $email_id;
                    $data['old_content'] = $value;
                    $data['new_content'] = $new_info[$key];
                    $data['operate_type'] = $operate;
                    switch ($key) {
                        case 'send_time':
                            $data['content_type'] = 1;
                            break;
                        case 'pop_time'://弹出框截止时间
                            $data['content_type'] = 2;
                            break;
                        case 'receive_player'://发送对象
                            $data['content_type'] = 3;
                            break;
                        case 'title':
                            $data['content_type'] = 4;
                            break;
                        case 'attachment'://附件
                            $data['content_type'] = 5;
                            break;
                        case 'content':
                            $data['content_type'] = 6;
                            break;
                        /*case 'is_pop':
                            $data['content_type'] = 7;
                            break;
                        case 'send_type':
                            $data['content_type'] = 8;
                            break;
                        default:
                            $data['content_type'] = 0;
                            break;*/

                    }
                    $data['time'] = date('Y-m-d H:i:s',time());
                    if (!isset($data['content_type'])){
                        $data = [];
                    }
                }
                if ($data) {
                    $records[] = $data;
                }
            }
            if (!isset($records)) {
                return false;
            }
        }
        $result = Yii::$app->db->createCommand()->batchInsert(GameEmailLog::tableName(),['account','email_id','old_content','new_content','operate_type','content_type','time'],$records)->execute();
        return $result?true:false;

    }
}