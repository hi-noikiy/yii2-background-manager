<?php
/**
 * User: jw
 * Date: 2018/7/26 0026
 */
namespace app\models;

use yii;
use yii\db\ActiveRecord;
use yii\base\Curl;

class GameEmail extends ActiveRecord
{
    public static function tableName()
    {
        return 't_game_email';
    }
    public function rules()
    {
        return [
            [['send_type', 'title','send_time','is_pop'], 'required'],
            [['send_type', 'is_pop','is_verify'], 'integer'],
            ['title', 'string', 'max'=>10],
            [['created_time', 'updated_time'], 'default', 'value'=>date('Y-m-d H:i:s', time()), 'message'=>'{attribute} is error'],
            ['content', 'string', 'max'=>200, 'message'=>'{attribute} is error'],
            ['attachment', 'safe', 'message'=>'{attribute} is error'],
            [['send_status', 'status'], 'default', 'value'=>1, 'message'=>'{attribute} is error'],
            ['phone', 'default', 'value'=>'15910284120'],
            ['send_time', 'compare', 'compareValue'=>time(),'operator'=>'>', 'message'=>'{attribute} is error'],
           // ['pop_time', 'date','format'=>'yyyy-MM-dd HH:mm:ss', 'message'=>'{attribute} is error'],
            [['receive_player','pop_time'],'safe'],
        ];
    }

    /**
     * 发送邮件信息给游戏服务器
     * @param id 邮件id
     * @param play 弹出开始和结束 0开始，1结束
     */
    public function sendEmail($id,$is_play)
    {
        $db = Yii::$app->db;
        $info = $db->createCommand("SELECT * FROM t_game_email WHERE id=".$id)->queryOne();
        if ($info) {
            if ($info['send_type'] == 1) {//全服
                $target = '';
            } else {
                $target = $info['receive_player'];
            }
            $response = $db->createCommand()->update('send_mail.t_bulletin_mail',['mail_status'=>$is_play],'mail_id ='.$info['email_code'])->execute();
            $curl = new Curl();
            $data = json_encode([
                'userId' => $target,
                'mailId' => $info['email_code'],
                'title' => $info['title'],
                'content' => $info['content'],
                'type' => $info['is_pop'] == 1?2:1,
                'sendTime' => strtotime($info['send_time']),
                'goods' => $info['attachment'],
                'isOpen' => $is_play,//公告开始暂停：0开始，1暂停
                'endTime' => strtotime($info['pop_time']),
                'sender' => 'admin',
            ]);
            $response = $curl->setGetParams([
                'msg' => $data,
            ])->get(Yii::$app->params['email_url']);

            Yii::info("邮件播放暂停服务器返回：".json_encode($response));

            if ($response) {
                $db->createCommand("UPDATE t_game_email SET play_pause = ".$is_play.",updated_time = '".date('Y-m-d H:i:s',time())."' WHERE id = ".$info['id'])->execute();
                return true;
            }
            return false;
        }

    }

}