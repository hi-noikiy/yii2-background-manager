<?php
/**
 * User: jw
 * Date: 2018/7/31 0031
 */
namespace app\commands;

use app\models\Marquee;
use yii\base\Curl;
use yii\console\Controller;

use Yii;
use yii\db\Exception;

class EventController extends Controller
{
    /**
     * 定时发送邮件
     */
    public function actionTimingMail()
    {
        $db = Yii::$app->db;
        $t = date('Y-m-d H:i', time());
        //距离发送时间小于15分钟变为准备发送状态
        $db->createCommand()->update('t_game_email',['updated_time'=>date('Y-m-d H:i:s',time()),'send_status'=>2],'unix_timestamp(send_time) <('.strtotime($t).'+15*60) and unix_timestamp(send_time) >='.strtotime($t).' and status = 1 and send_status = 1')->execute();
        $info = $db->createCommand("SELECT * FROM t_game_email WHERE send_time <= '{$t}%' AND status = 1 AND send_status != 3")->queryAll();

        if ($info) {
            foreach ($info as $v) {
                if ($v['send_type'] == 1) {//全服
                    $target = '';
                } else {
                    $target = $v['receive_player'];
                }
                $data = json_encode([
                    'userId' => $target,
                    'mailId' => (int)$v['email_code'],
                    'title' => $v['title'],
                    'content' => $v['content'],
                    'type' => $v['is_pop'] == 1?2:1,
                    'sendTime' => strtotime($v['send_time'])?:0,
                    'goods' => $v['attachment']?:"",
                    'isOpen' => 0,//公告开始暂停：0开始，1暂停
                    'endTime' => strtotime($v['pop_time'])?:0,
                    'sender' => 'admin',
                ]);
                $url = Yii::$app->params['email_url'];
                $present_data = 'msg=' . $data;
                $curl = new Curl();
                $response = $curl->CURL_METHOD($url,$present_data);
                $return = json_decode($response);

                Yii::info("邮件服务器返回：".$response);
                if ($return->code == 0) {
                    $db->createCommand("UPDATE t_game_email SET send_status = 3,updated_time = '".date('Y-m-d H:i:s',time())."' WHERE id = ".$v['id'])->execute();
                }else{
                    $db->createCommand("UPDATE t_game_email SET send_status = 1,updated_time = '".date('Y-m-d H:i:s',time())."',send_time = '".date('Y-m-d H:i:s',time())."' WHERE id = ".$v['id'])->execute();
                }
            }

        }
    }

    /**
     * 检查跑马灯是否到播放时间
     */
    public function actionCheckPmTime(){
        $min = 1;
        $max = time();
        $redis = Yii::$app->redis_3;
        $redis_key = Yii::$app->params['redisKeys']['gm_paoma_time'];
        try{
            $list = $redis->zrangebyscore($redis_key,$min,$max);
        }catch (Exception $e){
            var_dump('连接redis失败:'.$e);
        }
        $model = new Marquee();
        if(empty($list)){
            var_dump("空!!");
            exit;
        }
        $data = [];
        foreach ($list as $value) {
            $res = $model -> PostServer($value);
            if($res){
                $res = json_decode($res,TRUE);
                if($res['code'] == 0){
                    $data[] = $value;

                    //从redis里删除这个跑马灯
                    $redis -> zrem($redis_key,$value);
                }
            }
//            else if($res == 0){
//                //从redis里删除这个跑马灯
//                $redis -> zrem($redis_key,$value);
//            }
        }
        if ($data) {//更改跑马灯发送状态
            $model->setMarqueeIsplay($data);
        }
    }
}
