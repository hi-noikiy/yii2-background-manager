<?php
/**
 * User: SeaReef
 * Date: 2018/9/18 17:22
 *
 * 分享扫码绑定接口、接收老平台跳转过来的参数
 */

namespace app\controllers\api;

use app\common\Code;
use app\common\Common;
use app\common\DailiCalc;
use app\controllers\BaseController;
use app\models\LogDownload;
use Yii;
use yii\base\Curl;
use yii\db\Query;
use yii\helpers\Url;
use callmez\wechat\sdk\Wechat;
use callmez\wechat\sdk\MpWechat;

class ShareController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * 分享地址、判断不同的浏览器类型
     */

    private $share_url = 'https://share-pk.601yx.com/api/share/down';

    /**
     * 微信授权code
     */
    private $code = '';

    /**
     * 微信代理接口access_token
     */
    private $Daili_Access_Token = 'daili_access_token';

    /**
     * 代理后台微信号 u_id与openid对应关系
     */
    private $dailiRelation = 'weixin_daili_relation';


    /**
     * 调取接口的access_token地址
     */
    const ACCESS_TOKEN = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';

    /**
     * 绑定成功模板消息id
     */
    const DIND_TEMPLATE_MESSAGE = '77-oo_YSZ-QVdGQNo_g8NraoJLlBXKGCU4-XyQVVk3Y';

    /**
     * 模板消息跳转地址
     */
    const TEMPLATE_URL = 'https://oss.601yx.com/wechat/index';

    /**
     * 老平台添加跳转地址、跳转到现有的扫码分享页面
     */
    public function actionT1()
    {
        $this->redirect('http://oss.100.com/api/share/t2?player_id=456789');
    }

    public function actionT2()
    {
        echo 't2';
    }

    /**
     * 扫码分享暗绑
     *
     * @params uid
     * @params gid
     * @params channel_id
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '512M');

        $request = Yii::$app->request;
        $uid = $request->get('uid');
        $gid = $request->get('gid', 1114112);
        $channel_id = $request->get('channel_id', 1);
        $this->code = Yii::$app->request->get('code', '');
	Yii::info('扫码绑定:代理id--'.$uid.',扫码人unionid:'.$this->getUnionid());
        if ($uid && $gid && $channel_id) {
            $db = Yii::$app->db;

//            发码人是代理
            $send_player = (new Query)->select('*')->from('t_daili_player')->where(['player_id' => $uid])->one();

            if (isset($send_player['player_id']) && $send_player['player_id']) {

                file_put_contents('/tmp/wx.log',PHP_EOL.date('Y-m-d H:i:s',time()).'是代理：'.PHP_EOL.print_r($send_player,1).PHP_EOL,FILE_APPEND);

                $session = Yii::$app->session;
                $session->set('wx_mp', 'qxmj_mp');
                $session->set('share_uuid', $this->getUnionid());
		file_put_contents('/tmp/wx.log',PHP_EOL.date('Y-m-d H:i:s',time()).'share_uuid：'.PHP_EOL.$session->get('share_uuid').PHP_EOL,FILE_APPEND);
                $scan_code = ['uid' => $uid, 'time' => date('Y-m-d H:i:s'), 'wxinfo' => $session->get('wx_user_info')];

                file_put_contents('/tmp/scan_code.log', print_r([$session->get('wx_user_info'), $uid,], 1), FILE_APPEND);

                $user = $session->get('wx_user_info');
                $player_id = (new Query())
                    ->select('u_id')
                    ->from('login_db.t_lobby_player')
                    ->where(['weixin_union_id' => $user['unionid']])
                    ->scalar();
                Yii::$app->redis->hset($this->dailiRelation, $player_id, json_encode(['player_id' => $player_id, 'union_id' => $user['unionid'], 'openid' => $user['openid']], JSON_UNESCAPED_UNICODE));

                $scan_code = json_encode($scan_code);

                //登录表查询玩家是否存在
                $info = (new Query)->select('*')->from('login_db.t_lobby_player')->where(['weixin_union_id' => $session->get('share_uuid')])->one();
                $player_index = Yii::$app->redis_2->hget('reserved_id_key', $session->get('share_uuid'));
                if (isset($info['u_id']) && $info['u_id']) {//已存在玩家

                    file_put_contents('/tmp/wx.log',PHP_EOL.date('Y-m-d H:i:s',time()).'当前扫码玩家已经存在游戏中，查询是否已绑定上级',FILE_APPEND);

                    //查看当前用户是否有上级
                    $binded = (new Query)->select('parent_id')->from('t_player_member')->where(['player_id' => $info['u_id']])->scalar();
                    if ($binded) {//已有上级
                        file_put_contents('/tmp/wx.log',PHP_EOL.date('Y-m-d H:i:s',time()).'已有上级',FILE_APPEND);
                        return $this->redirect($this->share_url);

                    }
                    $bind_member = $info['u_id'];

                } else if ($player_index) {//redis中存在
                    file_put_contents('/tmp/wx.log',PHP_EOL.date('Y-m-d H:i:s',time()).'登录表中没有，redis中存在，查看是否存在上级',FILE_APPEND);

                    $binded = (new Query)->select('parent_id')->from('t_player_member')->where(['player_id' => $player_index])->scalar();
                    if ($binded) {//已绑定
                        return $this->redirect($this->share_url);

                    }
                    $bind_member = $player_index;
                } else {//不存在的玩家
                    file_put_contents('/tmp/wx.log',PHP_EOL.date('Y-m-d H:i:s',time()).'当前玩家没有PLAYER_INDEX ,进行获取ID操作',FILE_APPEND);
                    $id = Yii::$app->redis_1->incr('user_id_index');
                    $user_list = require 'userId.php';
                    $scan_player_id = $user_list[$id];
                    if (!$scan_player_id) {
//                        file_put_contents('/tmp/wx.log',PHP_EOL.date('Y-m-d H:i:s',time()).'生成player_id失败',FILE_APPEND);
                        return $this->redirect($this->share_url);
                    }
                    Yii::$app->redis_2->hset('reserved_id_key', $session->get('share_uuid'), $scan_player_id);
                    Yii::$app->redis_2->hset('reserved_key_id', $scan_player_id, $session->get('share_uuid'));
                    $bind_member = $scan_player_id;
                }

                //玩家id绑定操作
                $save_info = [
                    'parent_id' => $uid,
                    'player_id' => $bind_member,
                    'bind_time' => date('Y-m-d H:i:s', time()),
                ];
                file_put_contents('/tmp/wx.log',PHP_EOL.date('Y-m-d H:i:s',time()).'开始绑定',FILE_APPEND);

                $do_bind = $db->createCommand()->insert('t_player_member', $save_info)->execute();
                if ($do_bind) {
file_put_contents('/tmp/wx.log',PHP_EOL.date('Y-m-d H:i:s',time()).'绑定成功',FILE_APPEND);
                    DailiCalc::bindDaili($uid, $bind_member);
                    $player_openid = Yii::$app->redis->hget($this->dailiRelation, $uid);
                    file_put_contents('/tmp/wx.log',PHP_EOL.date('Y-m-d H:i:s',time()).'绑定人redis信息1'.$player_openid.PHP_EOL,FILE_APPEND);
                    if ($player_openid) {
                        $player_openid = json_decode($player_openid, 1);
                        file_put_contents('/tmp/wx.log',PHP_EOL.date('Y-m-d H:i:s',time()).'绑定人redis信息2'.print_r($player_openid,1).PHP_EOL,FILE_APPEND);
                        //发送模板消息
                        $send_msg = '恭喜您，玩家：' . $session->get('wx_user_info')['nickname'] . ' 玩家ID：' . $bind_member . ' 已成功扫码绑定了您，成为您的下级玩家，在代理后台->我的玩家列表里可以找到他，请及时引导玩家登录进行游戏喔！';
                        $this->sendTemplateMessage($player_openid['openid'], $send_msg, $bind_member);
                    }

                } else {
                    file_put_contents('/tmp/wx.log',PHP_EOL.date('Y-m-d H:i:s',time()).'绑定失败',FILE_APPEND);
                }
                Yii::$app->redis->hset('share_scan_info', $session->get('share_uuid'), $scan_code);
                return $this->redirect($this->share_url);
            } else {//非代理
                return $this->redirect($this->share_url);
            }
        } else {
            $this->writeResult(Code::CODE_ERROR);
            die();
        }
    }

    /**
     * 分享二维码
     */
    public function actionQrcode()
    {
        include './../vendor/phpqrcode/phpqrcode.php';
        $code = new \QRcode();

        $uid = \Yii::$app->request->get('uid');
        $gid = \Yii::$app->request->get('gid');

        if (!$uid || !$gid) {
            echo "ID非法操作！";
            exit;
        }
        //二维码的存放地址；
        $qrname = 'img/qrcode/qrcode_' . $uid . '.png';
        $has = is_file($qrname);

        if (!$has) { //文件不存在的情况下生成一个二维码

            $local = 'http://' . $_SERVER['HTTP_HOST'];
            $value = $local . Url::to(['api/share/index', 'gid' => $gid, 'uid' => $uid]);
            $errorCorrectionLevel = 'L';//容错级别
            $matrixPointSize = 6;//生成图片大小
            //生成二维码图片
            $code::png($value, $qrname, $errorCorrectionLevel, $matrixPointSize, 2);
        }

        $logo = 'static/mobile/agent/images/wecart-bg.png';
        $QR = $qrname;//已经生成的原始二维码图

        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 3;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, 323, 790, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        //$user = $this->userInfo($uid);
        //imagecopyresampled($QR, $user['img'], 20,70,0,0,$logo_qr_width, $logo_qr_height, 20, 20);

        //输出图片
        Header("Content-type: image/png");
        ImagePng($QR);
//        ob_clean();
        exit;
    }

    /**
     * 下载页面
     * @param type 1-短信
     * @return string
     */
    public function actionDown()
    {
//        $type = 0;
//        if (isset($_REQUEST['type'])) {
//            $type = $_REQUEST['type'];
//        }
//        $ip = Common::getIp();

        return $this->renderPartial('down');//, ['ip' => $ip, 'type' => $type]
    }

    /**
     * 苹果下载地址
     */
    public function actionIos()
    {
//        $request = $this->checkRequestWay(0);
//        $id = 0;
//        if (isset($request['ip']) && isset($request['type'])) {
//            $data['ip'] = $request['ip'];
//            $data['source_type'] = $request['type'];
//            $data['termail'] = 'ios';
//            $data['create_time'] = date('Y-m-d H:i:s');
//            $data['op_type'] = 1;
//
//            $logModel = new LogDownload();
//            $logModel->updateRecord($data);
//
//            $id = Yii::$app->db->getLastInsertID();
//        }

        return $this->renderPartial('ios');//, ['id' => $id]
    }

    /**
     * 安卓下载地址
     */
    public function actionAndroid()
    {
//        $request = $this->checkRequestWay(0);
//        $id = 0;
//        if (isset($request['ip']) && isset($request['type'])) {
//            $data['ip'] = $request['ip'];
//            $data['type'] = $request['type'];
//            $data['termail'] = 'android';
//            $data['createTime'] = date('Y-m-d H:i:s');
//
//            $logModel = new LogDownload();
//            $logModel->updateRecord($data);
//
//            $id = $logModel->attributes['id'];
//        }
        return $this->renderPartial('android');//, ['id' => $id]
    }

    private function getUnionid()
    {
        $session = Yii::$app->session;
// file_put_contents('/tmp/wx.log', PHP_EOL.date("Y-m-d H:i:s") .PHP_EOL. "union:" . $session->get('unionid'). '\n', FILE_APPEND);
        if ($session->get('unionid')) {
            return $session->get('unionid');
        }
        $this->getOpenid();
        return $session->get('unionid');
    }

    private function getOpenid()
    {
        $wechat = Yii::$app->wechat;
        $session = Yii::$app->session;
//        file_put_contents('/tmp/wx.log', PHP_EOL.date("Y-m-d H:i:s") . "referer:".PHP_EOL . print_r(Yii::$app->request->get(),1) . '\n', FILE_APPEND);
//        file_put_contents('/tmp/wx.log', PHP_EOL.date("Y-m-d H:i:s") . "code:".PHP_EOL .$this->code . '\n', FILE_APPEND);
        if (!$this->code) {
            $url = $this->GetCurUrl();
//            file_put_contents('/tmp/wx.log', date("Y-m-d H:i:s") . "--" . $url . '\n', FILE_APPEND);
//	    file_put_contents('/tmp/wx.log', PHP_EOL.date("Y-m-d H:i:s") .PHP_EOL. "redirect_url:" .'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $wechat->appId . '&redirect_uri=' . urlencode($url) . '&response_type=code&scop
//e=snsapi_userinfo&state=123#wechat_redirect' .PHP_EOL, FILE_APPEND);
            //获取code
            header('Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $wechat->appId . '&redirect_uri=' . urlencode($url) . '&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect');
            exit;
        }
//	file_put_contents('/tmp/wx.log', PHP_EOL.date("Y-m-d H:i:s") .PHP_EOL. "code1:".$this->code .PHP_EOL, FILE_APPEND);
        $result = (new Curl())->get(Wechat::WECHAT_BASE_URL . Wechat::WECHAT_OAUTH2_ACCESS_TOKEN_URL . 'appid=' . $wechat->appId . '&secret=' . $wechat->appSecret . '&code=' . $this->code . '&grant_type=authorization_code');
        $result = json_decode($result, 1);
//        Yii::info("dddddddddddddddddddddddddddddd");
//        Yii::info($this->code);
//	Yii::info($result);
//file_put_contents('/tmp/wx.log', PHP_EOL.date("Y-m-d H:i:s") . "access_token:".PHP_EOL . print_r($result,1) . '\n', FILE_APPEND);
        $session->set('openid', $result['openid']);
        $session->set('unionid', $result['unionid']);
        $session->set('access_token', $result['access_token']);
        $session->set('wx_user_info', Yii::$app->wechat->getSnsMemberInfo($result['openid'], $result['access_token']));
        return $session->get('openid');
    }

    // php获取当前访问的完整url地址
    private function GetCurUrl()
    {
        $url = 'http://';
        if (isset ($_SERVER ['HTTPS']) && $_SERVER ['HTTPS'] == 'on') {
            $url = 'https://';
        }
//    if ($_SERVER ['SERVER_PORT'] != '80') {
        //    $url .= $_SERVER ['HTTP_HOST'] . ':' . $_SERVER ['SERVER_PORT'] . $_SERVER ['REQUEST_URI'];
        //  } else {
        $url .= $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
        //  }
        // 兼容后面的参数组装
        if (stripos($url, '?') === false) {
            $url .= '?t=' . time();
        }
        return $url;
    }

    /**
     * 获取接口access_token
     */
    public function getAccessToken()
    {
        $wechat = Yii::$app->wechat;
        $access_token = Yii::$app->redis->hget($this->Daili_Access_Token, 'access_token');
        $expires_time = Yii::$app->redis->hget($this->Daili_Access_Token, 'expires_time');
        if (($access_token && (time() - $expires_time) > 6000) || !$access_token) {
            $result = (new Curl())->get(self::ACCESS_TOKEN . '&appid=' . $wechat->appId . '&secret=' . $wechat->appSecret);
            $result = json_decode($result, 1);
            //$result = $wechat->requestAccessToken();
            if (isset($result['access_token'])) {
                Yii::$app->redis->hset($this->Daili_Access_Token, 'access_token', $result['access_token']);
                Yii::$app->redis->hset($this->Daili_Access_Token, 'expires_time', time());
                $access_token = $result['access_token'];
            } else {
//                file_put_contents('/tmp/wx.log',date('Y-m-d H:i:s',time()).'\n'.'-获取微信接口access_token失败'.PHP_EOL,FILE_APPEND);
                return false;
            }

//            file_put_contents('/tmp/wx.log',date('Y-m-d H:i:s',time()).'-获取微信接口access_token:'.$result['access_token'].PHP_EOL,FILE_APPEND);
        }

        return $access_token;
    }

    /**
     * 发送模板消息
     */
    public function sendTemplateMessage($openid, $data, $bind_member)
    {
        $data = [
            "touser" => $openid,
            "template_id" => self::DIND_TEMPLATE_MESSAGE,
            "url" => self::TEMPLATE_URL,
            "data" => [
                "first" => [
                    "value" => $data,
                    "color" => "#173177"
                ],
                "keyword1" => [
                    "value" => $bind_member
                    //"color"=>"#173177"
                ],
                "keyword2" => [
                    "value" => date('Y-m-d H:i')
                    //"color"=>"#173177"
                ]
            ]
        ];
        $result = (new Curl())->setRawPostData(json_encode($data, JSON_UNESCAPED_UNICODE))->post(Wechat::WECHAT_BASE_URL . MpWechat::WECHAT_TEMPLATE_MESSAGE_SEND_PREFIX . '?access_token=' . $this->getAccessToken());
//        file_put_contents('/tmp/wx.log',date('Y-m-d H:i:s',time()).'模板消息结果：'.PHP_EOL.print_r($result,1).PHP_EOL,FILE_APPEND);

        return isset($result['msgid']) ? $result['msgid'] : false;
    }

    public function actionDownloadPage()
    {
        $request = $this->checkRequestWay(0);
        $downUrl = Yii::$app->params['down_url'];

        if (isset($request['id']) && isset($request['type'])) {
            $type = $request['type'];
            $id = $request['id'];

            $logModel = new LogDownload();
            $saveData['op_type'] = 2;
            $logModel->updateRecord($saveData, $id);

            //跳转下载地址
            if ($type == 'ios') {
                header('Location: '.$downUrl['ios_down_url']);
                exit;
            } elseif ($type == 'android') {
                header('Location: '.$downUrl['android_down_url']);
                exit;
            }else{
                echo "<script>alert('数据错误！');history.go(-1);</script>";
                exit;
            }
        }

    }
}
