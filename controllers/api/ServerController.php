<?php
/**
 * User: SeaReef
 * Date: 2018/6/20 13:44
 *
 * 服务器上报接口
 */
namespace app\controllers\api;

use app\controllers\BaseController;
use app\common\Code;
use app\models\GoldRecord;
use app\models\Marquee;
use app\models\PlayerMember;
use Yii;
use yii\base\Curl;
use yii\web\Controller;

class ServerController extends BaseController
{
    public $enableCsrfValidation = false;

    public function init()
    {
        file_put_contents('/tmp/server_gold_record.log', print_r([$_POST, date('Y-m-d H:i:s', time())], 1), FILE_APPEND);
    }

    /**
     * 记录台费记录
     */
    /*
    public function actionGoldRecord()
    {
        $post_data = Yii::$app->request->post('msg');
        $gid = Yii::$app->request->get('gid');
        if (!$post_data) {
            Yii::info("参数不合法！");
            $this->AjaxReturn("",101,'参数不合法');
        }
        $post_data = str_replace('\\"', '"', $post_data);
        $data = json_decode($post_data, 1);

        //数据添加到台费表
        $transaction = GoldRecord::getDb()->beginTransaction();
//        foreach ($data as $v) {
        try {
            $m = new GoldRecord();
            if (!$m->addRecord($data, $gid)) {
                Yii::info('记录台费失败,订单不合法');
                $this->AjaxReturn("",102,'记录台费失败,订单不合法');
            }
        } catch(\Exception $e) {
            Yii::info('记录台费失败！');
            $this->AjaxReturn("",103,'记录台费失败'.$e);
        }
//        }
        $transaction->commit();

        return $this->AjaxReturn("",0,'success');
    }
    */

    /**
     * 接收台费上报
     * 添加一个ip白名单
     * 查询一下唯一订单问题
     */
    public function actionGoldRecord()
    {
        Yii::info(print_r($_POST, 1), '台费上报');

        $request = Yii::$app->request;
        $gid = $request->post('gid');
        $msg = $request->post('msg');

//        验证子游戏
        if (!in_array($gid, array_keys(Yii::$app->params['games']))) {
            $this->writeResult(Code::CODE_GID_NOT_ALLOW);
        }

        if (!IS_TEST) {
//            上报白名单
            $ip = Yii::$app->request->userIP;
            if (!in_array($ip, Yii::$app->params['server_whitelist'])) {
                $this->writeResult(Code::CODE_IP_NOT_ALLOW);
            }

        }

        $data = json_decode($msg, 1);
        $m = new GoldRecord();
        foreach ($data as $v) {
            $res = $m->addRecord($v, $gid);
            if (!$res) {
                Yii::warning(print_r($v, 1), '记录台费失败');
            }
        }
    }

    /**
     * 子游戏在线时长
     */
    public function actionSubOnline()
    {

    }

    /**
     * 用户跑马灯消息写入
     */
    public function actionAddUserPm(){
        $msg = $_REQUEST;
        if(!$msg || empty($msg) || !isset($msg['msg'])){
            return $this->AjaxReturn('',101,'参数不合法'.json_encode($msg));
        }else{
            $msg = json_decode($msg['msg'],true);
        }
        //校验参数项
        if(empty($msg['content']) || empty($msg['createrId'])){
            return $this->AjaxReturn('',10,'缺少必要参数');
        }
        $GamePostModel = new Marquee();
        $data = array();//保存在数据库中的数据
        //查看改用户是否在黑白名单中，白名单count = 0黑名单直接返回结果
        $player_index = $msg['createrId'];
        $user_sign = $GamePostModel -> getUserType($player_index);
        if($user_sign != false){
            if($user_sign == 1){
                //白名单
                $data['cost'] = 0;
                $data['white_sign'] = 1;
            }else if($user_sign == 2){
                //黑名单
                return $this->AjaxReturn('',8,'黑名单禁止发送');
            }
        }else{
            //读取每条跑马灯的收费
            $count = $GamePostModel -> getPmdDeduct();
            $data['cost'] = $count;
            $data['white_sign'] = 0;

            //operateType=1增加，2减少，count:操作数量 userId用户id
            $present_data = 'msg={"sourceType":5,"propsType":"3","count":"'.$count.'","operateType":"2","gameId":"1114112","userId":"'.$player_index.'"}';
            $present_url  = \Yii::$app->params['recharge_Url'];
            $curl = new Curl();
            $result = $curl->CURL_METHOD($present_url,$present_data);

            $info = json_decode($result,true);
            if(empty($info)){
                return $this->AjaxReturn('',9,'扣费用没有结果返回,请求地址--:'.$present_url."请求参数：：".$present_data);
            }else if($info['code'] != 0){
                return $this->AjaxReturn('',$info['code'],$info['describe']);
            }
        }
        //扣费成功，存入数据库，给服务器发消息
        $data['create_uid'] = $msg['createrId'];
        $data['content'] = $msg['content'];
        $pmId = $GamePostModel -> insertUserPost($data);
        $resInfo = $GamePostModel -> PostServer($pmId);

        return $this->AjaxReturn($data, 0,'success');
    }


    /**
     * 用户登录获取可播放的跑马灯列表
     * @return [type] [description]
     */
    public function actionLoginPmList(){
        $player_index = $_REQUEST['userId'];
        if(empty($player_index)){
            return $this->AjaxReturn('',2,'没有接收到用户id');
        }

        $model = new Marquee();
        $cost = $model -> getPmdDeduct();
        $last_time = $model -> getUserLastTime($player_index);
        $data = $model -> getNowList();
        if(empty($data)){
            $this->AjaxReturn('',1,'empty');exit;
        }
        $res = array();
        $interval = $model -> getInterval(101);
        $show_time = $model -> getPmdShowtime();
        if($data){
            foreach ($data as $k => $v) {
                //TODO:数据对接
                $res[$k] = array(
                    "id" => $v['id'],
                    "content" => $v['content'],
                    "createTime" => strtotime($v['created_time']),
                    "createrId" => $v['account'],
                    "deduct" => $v['cost'],
                    "endTime" => strtotime($v['end_time']),
                    "interval" => $interval,
                    "noticeSign" => $v['is_notice'],
                    "startTime" =>  strtotime($v['start_time']),
                    "status" => $v['status'],
                    "type" => $v['type'],
                    "updateTime" => strtotime($v['updated_time']),
                    "showTime" => $show_time,
                );
            }
        }
        //$res['lastTime'] = $last_time;
        $resdata['content'] = $res;
        $resdata['lastTime'] = strtotime($last_time) ?: 0;
        $resdata['cost'] = intval($cost);
        return $this->AjaxReturn($resdata,0,'success');

    }

    public function actionGoldRecord2()
    {
        file_put_contents('/tmp/lang11111.log', print_r([$_POST], 1), FILE_APPEND);
        $db = Yii::$app->db;
        $gid = $_POST['gid'];
        $msg = $_POST['msg'];
        if (!in_array($gid, array_keys(Yii::$app->params['games']))) {
            $this->writeResult(self::CODE_ERROR);
        }

        $data = json_decode($msg, 1);
//        兼容新平台
//        $transaction = GoldRecord::getDb()->beginTransaction();
//var_dump($data);
        foreach ($data as $v) {
            var_dump($v);
//            try {
            $m = new GoldRecord();
            if (!$info = $m->addRecord($v, $gid)) {
                echo 'lang';
                Yii::info('记录台费失败,订单不合法');
            }
            var_dump($info);
//            } catch (\Exception $e) {
//                Yii::info('记录台费失败');
//            }
        }
//        $transaction->commit();

//        $this->writeResult(self::CODE_OK);
    }
}
