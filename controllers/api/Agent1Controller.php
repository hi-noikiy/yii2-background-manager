<?php
/**
 * User: SeaReef
 * Date: 2018/10/22 14:38
 *
 * 人人代理接口
 */
namespace app\controllers\api;

use app\controllers\BaseController;
use app\controllers\WechatController;
use Yii;
use yii\db\Query;
use yii\helpers\Url;
use callmez\wechat\sdk\Wechat;
use yii\base\Curl;

class AgentController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * 申请成为高级代理条件
     */
//    已邀请人数大于等于10人
    const AGENT_COUNT = 10;

//    累计收益达到100元
    const TOTAL_COUNT = 100;

    /**
     * 提现条件
     */
//    已邀请人数大于等于6人
    const TIXIAN_COUNT = 6;

//    可提现额度大于等于30元
    const WITHDRAW = 30;

//    邀请1人获得1元
    const INVITE = 1;

    /**
     * 用于客户端展示条件
     */
//    成为高级代理提升的返利比例
    const REVENUE = '35%';

//    最高可获得金额
    const INVITE_MAX = 1;

//    分享下载地址
    public $share_url = 'https://share-pk.dropgame.cn/';

    /**
     * 短信相关配置
     */
//    预留存储验证码
    const SMS_USER_KEY = 'pk_sms_sign';

//    螺丝帽短信接口地址
    const SMS_ADDREST = 'http://sms-api.luosimao.com/v1/send.json';

//    短信加密key
    const SMS_KEY = 'api:830aa24a9af58f769beaabb21212cf4c';

//    短信消息模板
    const MSG = '您的验证码是{CODE}，请尽快完成验证，{MINUTE}分钟有效.【铁壳测试】';

//    客户端与平台加密key
    const CLIENT_KEY = 'DD9A716418E54777AC20377244FF7CD3';

//    验证码有效期、分钟
    const SMS_EXPIRE = 1;

    /**
     * 数据库句柄
     */
    private $db;

    private $redis;

    /**
     * 微信授权code
     */
    private $code = '';

    /**
     * redis键名
     */
    private $junioragent_list_key;

    private $junioragent_params_key;

    private $junioragent_relation_key;

    /**
     * 轮播图默认数据
     */
    private $simulation = [
        [
            'playerid' => '30011607',
            'withdraw' => 30,
            'weixin_nickname' => '马桶上人',
        ],
        [
            'playerid' => '30826102',
            'withdraw' => 30,
            'weixin_nickname' => '小布丁',
        ],
        [
            'playerid' => '30011608',
            'withdraw' => 30,
            'weixin_nickname' => '肖申克',
        ],
    ];

    public function init()
    {
        $this->db = Yii::$app->db;
        $this->redis = Yii::$app->redis;
    }

    /**
     * 是否是初级代理
     *
     * @params playerid 玩家ID
     * @return code、0初级代理、1初级代理、2高级代理
     */
    public function actionIsJuniorAgent($playerid)
    {
        $agent = $this->redis->zscore($junior_list_key, $playerid);

        if (empty($agent)) {    //不是初级代理
            $data['code'] = 0;
            $high = $this->db->createCommand($sql = "SELECT * FROM t_daili_player WHERE player_id = '{$playerid}'")->queryOne();

            if ($high) {   //如果是高级代理
                $data['code'] = 2;
            } else {
//                不是初级代理也不是高级代理
                $withdraw = $this->db->createCommand("SELECT tmp.playerid, tmp.withdraw, tmp2.weixin_nickname FROM
                                                      (SELECT playerid, withdraw FROM junior_withdraw ORDER BY create_time DESC LIMIT 5) AS `tmp`
                                                      LEFT JOIN
                                                      login_db.t_lobby_player AS `tmp2`
                                                      ON tmp.playerid = tmp2.u_id")->queryAll();

//                如果没有实时充值数据、返回模拟数据
                if (empty($withdraw)) {
                    $withdraw = $this->simulation;
                }

                foreach ($withdraw as $v) {
                    $recently[$v['weixin_nickname']] = $v['withdraw'];
                }
                $data['Recently'] = $recently;
            }
        } else {
            $junior = $this->db->createCommand("SELECT * FROM junior_agent WHERE playerid = '{$playerid}' AND status = 1")->queryOne();

            $data = [
                'code' => 1,
                'user' => $junior['user'],
                'today' => $junior['today'],
                'total' => $junior['total'],
                'cash_withdraw' => $junior['cash_withdrawn'],
                'user_limit' => $this->redis->hget($junior_params_key, 'agent_count') ? : self::AGENT_COUNT,
                'total_limit' => $this->redis->hget($junior_params_key, 'total_count') ? : self::TOTAL_COUNT,
                'revenue' => $this->redis->hget($junior_params_key, 'revenue') ? : self::REVENUE,
                'invite' => $this->redis->hget($junior_params_key, 'invite') ? : self::INVITE,
                'invite_max' => $this->redis->hget($junior_params_key, 'invite_max') ? : self::INVITE_MAX,
                'withdraw' => $this->redis->hget($junior_params_key, 'withdraw') ? : self::WITHDRAW,
                'tixian_count' => $this->redis->hget($junior_params_key, 'tixian_count') ? : self::TIXIAN_COUNT,
            ];
        }

        $this->writeJson($data);
    }

    /**
     * 申请初级代理
     *
     * @params playerid 玩家ID
     * @return 0/申请失败、1/申请成功
     */
    public function actionApplyJuniorAgent($playerid)
    {
        $data['code'] = 0;
        $t = time();
        $junior_list_key = Yii::$app->params['platform_redis_key']['pk_junioragent_list'];
        $junior_relation_key = Yii::$app->params['platform_redis_key']['pk_junioragent_relation'];
        $junior_params_key = Yii::$app->params['platform_redis_key']['pk_junioragent_params'];
        $info = $this->redis->zadd($junior_list_key, $t, $playerid);

//        持久化mysql
        if ($info) {
            $player_info = (new Query())->select(['weixin_union_id', 'weixin_open_id', 'weixin_nickname'])->from('login_db.t_lobby_player')->where(['u_id' => $playerid])->one();
            if ($player_info) {
                $agent = $this->db->createCommand($sql = "INSERT INTO `junior_agent` VALUE(NULL, '{$playerid}', '{$player_info['weixin_union_id']}', '{$player_info['weixin_open_id']}', '{$player_info['weixin_nickname']}', '0', '0', '0', '{$t}', 1, '0.00', '0.00', '0.00', '0.00', 0)")->execute();

//                成功添加绑定关系、失败回滚
                if ($agent) {
                    $this->redis->zadd($junior_relation_key, 999, $playerid);
                    $this->db->createCommand("INSERT INTO junior_relation VALUES(NULL, '999', '{$playerid}', '{$t}', 1)")->execute();

                    $junior = $this->db->createCommand("SELECT * FROM junior_agent WHERE playerid = '{$playerid}' AND status = 1")->queryOne();
                    $data = [
                        'code' => 1,
                        'user' => intval($junior['user']),
                        'today' => intval($junior['today']),
                        'total' => intval($junior['total']),
                        'cash_withdraw' => intval($junior['cash_withdrawn']),
                        'user_limit' => $this->redis->hget($junior_params_key, 'agent_count') ? : self::AGENT_COUNT,
                        'total_limit' => $this->redis->hget($junior_params_key, 'total_count') ? : self::TOTAL_COUNT,
                        'revenue' => $this->redis->hget($junior_params_key, 'revenue') ? : self::REVENUE,
                        'invite' => $this->redis->hget($junior_params_key, 'invite') ? : self::INVITE,
                        'invite_max' => $this->redis->hget($junior_params_key, 'invite_max') ? : self::INVITE_MAX,
                        'withdraw' => $this->redis->hget($junior_params_key, 'withdraw') ? : self::WITHDRAW,
                        'tixian_count' => $this->redis->hget($junior_params_key, 'tixian_count') ? : self::TIXIAN_COUNT,
                    ];
                } else {
                    $this->redis->zrem($junior_list_key, $playerid);
                }
            }
        }
        $data['playerid'] = $playerid;

        $this->writeJson($data);
    }

    public function actionShareQrcode($uid)
    {
        echo json_encode(['imageUrl' => Yii::$app->request->hostInfo . '/api/agent/share-qrcode1?uid=' . $uid]);
    }

    /**
     * 分享二维码
     */
    public function actionShareQrcode1()
    {
        include '../vendor/phpqrcode/phpqrcode.php';
        $code = new \QRcode();
        $uid   = \Yii::$app->request->get('uid');

        if(!$uid){
            echo "ID非法操作！";
            exit;
        }
        //二维码的存放地址；
        $qrname = 'img/junior/qrcode_'.$uid.'.png';
        $has = is_file($qrname);

        if(!$has){ //文件不存在的情况下生成一个二维码
            $local = 'http://'. $_SERVER['HTTP_HOST'];
            $value = $local.Url::to(['api/agent/bind', 'uid' => $uid]);
            $errorCorrectionLevel = 'L';//容错级别
            $matrixPointSize = 6;//生成图片大小
            //生成二维码图片
            $code::png($value, $qrname, $errorCorrectionLevel, $matrixPointSize, 2);
        }

        $logo = 'ewmlog.png';//准备好的logo图片
        $QR = $qrname;//已经生成的原始二维码图

        if ($logo !== FALSE){
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 7;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
        }

        //输出图片
        Header("Content-type: image/png");
        ob_clean();
        echo ImagePng($QR);
        exit;
    }

    /**
     * 扫码绑定
     */
    public function actionBind1()
    {
        ini_set('memory_limit', '512M');
        $g = Yii::$app->request->get();
        $uid = $g['uid'];

        $db = Yii::$app->dbTest;

        if($uid){
            $_SESSION['wx_mp'] = 'qxmj_mp';
            $_SESSION['share_uuid'] = $this->getUnionid();
//            $redis  = getredis();

            $scan_code = ['uid'=>$uid,'time'=>date('Y-m-d H:i:s'),'wxinfo'=>$_SESSION['wx_user_info']];
            $scan_code = json_encode($scan_code);
            bindlog('客户进入扫码主页'.$scan_code,'暗绑操作');

            Yii::$app->params['current_game'] = '524803';
            $info = DailiPlayer::find()->where(['PLAYER_INDEX'=>$uid])->asArray()->one();
            if($info['DAILI_ID'] ){ // 是代理
                bindlog($uid.'是代理进行redis赋值操作','暗绑操作');
                /*扫描的二维码是代理二维码后 进行绑定操作*/
                $BIND_PLAYER = $uid;

                //对用户unionid进行匹配，看是否已经存在此玩家
                $info = LobbyPlayer::find()->where(['weixin_union_id'=>$_SESSION['share_uuid']])->asArray()->one();
                // 先查询redis中是否存在已生成的ID值
                $PLAYER_INDEX = Yii::$app->redis_2->hGet('reserved_id_key',$_SESSION['share_uuid']);

                if($info['u_id'] ){ //已存在的玩家
                    bindlog($info['u_id'].'当前扫码玩家已经存在游戏中，查询是否已绑定上级','暗绑操作');
                    //查看当前用户是否已经绑定了上级
                    $binded = PlayerMember::find()->where(['MEMBER_INDEX'=>$info['u_id']])->asArray()->one();
                    if($binded['PLAYER_INDEX']){
                        //绑定了上级跳转到正常下载页面；
                        bindlog($info['u_id'].'当前玩家已有上级-跳转下载页面-结束','暗绑操作');
                        $urltype = Yii::$app->redis->get('share_down_status');
                        header('Location:'.$this->share_url.'?urltype='.$urltype.'&bindindex='.$binded['PLAYER_INDEX']);
                        exit;
                    }

                    bindlog($info['u_id'].'当前玩家没有上级-记录为MEMBER_INDEX','暗绑操作');
                    $BIND_MEMBER = $info['u_id'];

                }else if($PLAYER_INDEX){ // LOGINDB 中没有 但是redis 中存在
                    bindlog($PLAYER_INDEX.'当前扫码玩家已经存在于扫码队列中，查询是否已绑定上级','暗绑操作');
                    //查看当前用户是否已经绑定了上级
                    $binded = PlayerMember::find()->where(['MEMBER_INDEX'=>$PLAYER_INDEX])->asArray()->one();
                    if($binded['PLAYER_INDEX']){
                        //绑定了上级跳转到正常下载页面；
                        bindlog($PLAYER_INDEX.'当前玩家已有上级-跳转下载页面-结束','暗绑操作');
                        $urltype = Yii::$app->redis->get('share_down_status');
                        header('Location:'.$this->share_url.'?urltype='.$urltype);
                        exit;
                    }
                    bindlog($PLAYER_INDEX.'当前玩家没有上级-记录为MEMBER_INDEX','暗绑操作');
                    $BIND_MEMBER = $PLAYER_INDEX;

                } else { //游戏中不存在的玩家
                    // redis中不存在生成一个新的PLAYER_INDEX;
                    bindlog('当前玩家没有PLAYER_INDEX ,进行获取ID操作','暗绑操作');
                    $PLAYER_ID  = Yii::$app->redis_1->incr('user_id_index');
                    //读取配置文件进行操作;
                    $file_path  = "userId.conf";
                    $userid_str = file_get_contents($file_path);//将整个文件内容读入到一个字符串中
                    $userid_arr = explode(':',$userid_str);
                    $PLAYER_INDEX = $userid_arr[$PLAYER_ID];
                    if(!$PLAYER_INDEX){
                        bindlog('当前玩家获取游戏PLAYER_INDEX失败-跳转错误页面-结束','暗绑操作');
                        header('Location:'.$this->daili_url.'/index.php?gid=524803&r=wx/bind-error&wx_mp=qxmj_mp');
                        exit;
                    }
                    bindlog('玩家拿到PLAYER_INDEX:'.$PLAYER_INDEX.'进行DB2-redis入队操作','暗绑操作');
                    //拿到账号后 存入redis;
                    Yii::$app->redis_2->hSet('reserved_id_key',$_SESSION['share_uuid'],$PLAYER_INDEX);
                    Yii::$app->redis_2->hSet('reserved_key_id',$PLAYER_INDEX,$_SESSION['share_uuid']);
                    $BIND_MEMBER = $PLAYER_INDEX;
                    bindlog($PLAYER_INDEX.'当前玩家没有上级-记录为MEMBER_INDEX','暗绑操作');
                }

                //拿到ID后进行绑定操作
                $games = Yii::$app->params['games'];
                $save_info = ['PLAYER_INDEX'=>$BIND_PLAYER,'MEMBER_INDEX'=>$BIND_MEMBER,'BIND_TIME'=>time()];
                bindlog($BIND_MEMBER.'当前玩家执行绑定入库操作','暗绑操作');
                $br = Yii::$app->get($gid)->beginTransaction();
                foreach($games as $k => $v){
                    if($v == 1114112 ){
                        continue;
                    }
                    Yii::$app->params['current_game'] = $v;
                    $goods_model= Yii::$app->get($v);
                    //插入数据库信息
                    $dobind = $goods_model->createCommand()->insert('t_player_member', $save_info)->execute();
                    if(!$dobind){
                        //信息绑定失败;
                        bindlog($BIND_MEMBER.'当前玩家执行绑定入库操作失败-跳转失败页面-结束','暗绑操作');
                        $br->rollBack();
                        header('Location:'.$this->daili_url.'/index.php?gid=524803&r=wx/bind-error&wx_mp=qxmj_mp');
                        exit;
                    }
                }
                //绑定成功，进行页面跳转
                $br->commit();
                bindlog($BIND_MEMBER.'当前玩家暗绑操作成功，绑定上级：'.$BIND_PLAYER.'绑定操作成功！跳转下载下面-结束','暗绑操作');

                // ------- 公众号推送消息 2018-1-19 BEGIN
                // 从库中获取接收的微信号：
                $member_info = GameWxPlayer::find()->where(['PLAYER_INDEX'=>$BIND_PLAYER])->asArray()->one();
                $wx_openid = $member_info['WX_OPENID'];
                bindlog('获取微信OPENID'.$wx_openid,'暗绑操作');
                if(!empty($wx_openid))
                {
                    $temp_data = array(
                        'touser' => $wx_openid,
                        'tpl_id' => 3,
                        'data' => array(
                            'first' => '恭喜您，玩家：'.$_SESSION['wx_user_info']['nickname'].' 玩家ID：'.$BIND_MEMBER.' 已成功扫码绑定了您，成为您的下级玩家，在代理后台-我的玩家列表里可以找到他，请及时引导玩家登陆进行游戏喔！',
                            'keyword1' => $BIND_MEMBER,
                            'keyword2' => date('Y-m-d H:i', time())
                        )
                    );
                    $send_tempmsg_res = wx_push_template_message($temp_data);
                    bindlog('触发微信推送----玩家：'.$_SESSION['wx_user_info']['nickname'].' 玩家ID：'.$BIND_MEMBER, '暗绑操作');
                }
                // ------- END


                Yii::$app->params['current_game'] = $gid;
                Yii::$app->redis->hset('share_scan_info',$_SESSION['share_uuid'],$scan_code);
                $page_info['android_url'] = Url::to(['share/index','gid'=>$gid,'uid'=>$uid]);
                $page_info['ios_url']     = Url::to(['share/index','gid'=>$gid,'uid'=>$uid]);
            }else{
//                分享人已经不是高级代理、判断是否初级代理
                $share_player = Yii::$app->redis->zscore(self::JUNIOR_LIST_KEY, $uid);

                if ($share_player) {
//                    扫码人登录了游戏、没有高级代理关系、没有初级代理关系、进行绑定操作
                    $scan_uuid = $_SESSION['share_uuid'];
                    $user_info =  LobbyPlayer::find()->where(['weixin_union_id'=>$scan_uuid])->asArray()->one();
                    if ($user_info) {
                        $daili_info = DailiPlayer::find()->where(['PLAYER_INDEX' => $user_info['u_id']])->asArray()->one();
                        $junior_agent = Yii::$app->redis->zscore(self::JUNIOR_RELATION_KEY, $user_info['u_id']);

                        if (!$daili_info && !$junior_agent) {
                            $bind = 1;
                        }
                    }
                }

                if ($bind == 1) {
                    $parent = Yii::$app->redis_2->zadd(self::JUNIOR_RELATION_KEY, $uid, $user_info['u_id']);
                    if ($parent) {
                        $t = time();
                        $info =  Yii::$app->db->createCommand("INSERT INTO `junior_relation` VALUES(NULL, '{$uid}', '{$user_info['u_id']}', '{$t}', 1)")->execute();
                        $info = Yii::$app->db->createCommand("UPDATE junior_agent SET `user` = `user` + 1, `today` = `today` + 1, `total` = `total` + 1, `cash_withdrawn` = `cash_withdrawn` + 1, `reward` = `reward` + 1 WHERE playerid = '{$uid}'")->execute();
                    }
                }

                bindlog($uid.'不是代理，跳转正常下载页面','暗绑操作');
                $urltype = Yii::$app->redis->get('share_down_status');
                header('Location:'.$this->share_url.'?urltype='.$urltype);
                exit;
            }
            return $this->renderPartial('index',$page_info);
            exit;
        }else{
            echo '地址错误，请联系客服！';
            exit;
        }
    }

    /**
     * 新扫码绑定
     */
    public function actionBind()
    {
        ini_set('memory_limit', '521M');

        $junior_list_key = Yii::$app->params['platform_redis_key']['pk_junioragent_list'];
        $junior_relation_key = Yii::$app->params['platform_redis_key']['pk_junioragent_relation'];

        $request = Yii::$app->request;
        $session = Yii::$app->session;
        $uid = $request->get('uid');
        $this->code = Yii::$app->request->get('code','');

//        如果有uid
        if ($uid) {
            $send_user = $this->redis->zscore($junior_list_key, $uid);
//            发码人是人人代理
            if ($send_user) {
//                获取扫码人信息
                $session->set('share_uuid', $this->getUnionid());
                $scan_code = ['uid'=>$uid,'time'=>date('Y-m-d H:i:s'),'wxinfo'=>$session->get('wx_user_info')];
                $scan_code = json_encode($scan_code);
                $info = (new Query)->select('*')->from('login_db.t_lobby_player')->where(['weixin_union_id' => $session->get('share_uuid')])->one();
                $scan_uid = $info['u_id'];
                $scan_daili = (new Query())->select('id')->from('t_daili_player')->where(['player_id' => $scan_uid])->scalar();
                $scan_daili2 = (new Query())->select('id')->from('junior_agent')->where(['playerid' => $scan_uid])->scalar();
//                扫码人不是人人代理、不是代理
                if ($scan_daili || $scan_daili2) {
                    $t = time();
                    $this->redis->zadd($junior_relation_key, $uid, $scan_uid);
                    $this->db->createCommand("INSERT INTO junior_relation VALUES(NULL, '{$uid}', '{$scan_uid}', '{$t}', 1)")->execute();
                    $info = Yii::$app->db->createCommand("UPDATE junior_agent SET `user` = `user` + 1, `today` = `today` + 1, `total` = `total` + 1, `cash_withdrawn` = `cash_withdrawn` + 1, `reward` = `reward` + 1 WHERE playerid = '{$uid}'")->execute();
                }
//                不是代理
            }
//            没有uid
        }

        return $this->redirect($this->share_url);
    }



    private function getUnionid(){
        $session = Yii::$app->session;
        file_put_contents('/tmp/wx.log', PHP_EOL.date("Y-m-d H:i:s") .PHP_EOL. "union:" . $session->get('unionid'). '\n', FILE_APPEND);
        if($session->get('unionid')){
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
//	    file_put_contents('/tmp/wx.log', PHP_EOL.date("Y-m-d H:i:s") .PHP_EOL. "redirect_url:" .'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $wechat->appId . '&redirect_uri=' . urlencode($url) . '&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect' .PHP_EOL, FILE_APPEND);
            //获取code
            header( 'Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $wechat->appId . '&redirect_uri=' . urlencode($url) . '&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect');exit;
        }
//        file_put_contents('/tmp/wx.log', PHP_EOL.date("Y-m-d H:i:s") .PHP_EOL. "code1:".$this->code .PHP_EOL, FILE_APPEND);
        $result = (new Curl())->get(Wechat::WECHAT_BASE_URL.Wechat::WECHAT_OAUTH2_ACCESS_TOKEN_URL.'appid='.$wechat->appId.'&secret='.$wechat->appSecret.'&code='.$this->code.'&grant_type=authorization_code');
        $result = json_decode($result,1);
//        file_put_contents('/tmp/wx.log', PHP_EOL.date("Y-m-d H:i:s") . "access_token:".PHP_EOL . print_r($result,1) . '\n', FILE_APPEND);
        $session->set('openid',$result['openid']);
        $session->set('unionid',$result['unionid']);
        $session->set('access_token',$result['access_token']);
        $session->set('wx_user_info',Yii::$app->wechat->getSnsMemberInfo($result['openid'],$result['access_token']));
        return $session->get('openid');
    }

    // php获取当前访问的完整url地址
    private function GetCurUrl() {
        $url = 'http://';
        if (isset ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] == 'on') {
            $url = 'https://';
        }
//    if ($_SERVER ['SERVER_PORT'] != '80') {
        //    $url .= $_SERVER ['HTTP_HOST'] . ':' . $_SERVER ['SERVER_PORT'] . $_SERVER ['REQUEST_URI'];
        //  } else {
        $url .= $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
        //  }
        // 兼容后面的参数组装
        if (stripos ( $url, '?' ) === false) {
            $url .= '?t=' . time ();
        }
        return $url;
    }

    /**
     * 发送短信验证
     */
    public function actionSendCode($mobile, $sign, $ts)
    {
//        验证有效性
        if ($sign != md5($mobile . $ts . self::CLIENT_KEY)) {
            return $this->asJson(['code' => -1]);
        }

        $redis = Yii::$app->redis;
        $code = self::randomSign(4);

//        验证一分钟不能重复获取
        $info = $redis->set($mobile, $code);
        $redis->expire($mobile, self::SMS_EXPIRE * 60);

        $msg = self::MSG;
        $message = str_replace('{CODE}', $code, $msg);
        $message = str_replace('{MINUTE}', self::SMS_EXPIRE, $message);

        $info = $this->actionSmsSend($mobile, $message);
//        var_dump($info);
        echo $info;
    }

    /**
     * 申请高级代理：验证短信、记录玩家信息、转移高级代理信息、删除初级代理信息
     */
    public function actionCheckHigh()
    {
//        验证短信
        $get = Yii::$app->request->get();
        $username = $get['username'];
        $mobile = $get['mobile'];
        $sign = $get['sign'];
        $uid = $get['playerid'];
        $redis = Yii::$app->redis;
        $db = Yii::$app->db;

        $junior_list_key = Yii::$app->params['platform_redis_key']['pk_junioragent_list'];
        $junior_params_key = Yii::$app->params['platform_redis_key']['pk_junioragent_params'];
        $junior_relation_key = Yii::$app->params['platform_redis_key']['pk_junioragent_relation'];

        $data = [];
        file_put_contents('/tmp/check_high.log', print_r($_GET, 1), FILE_APPEND);
//        判断高级代理条件
        $agent = $db->createCommand("SELECT * FROM junior_agent WHERE playerid = '{$uid}'")->queryOne();
        $junior_params_key = Yii::$app->params['platform_redis_key'];


        if ($agent['user'] >= self::AGENT_COUNT && $agent['total'] >= self::TOTAL_COUNT) {

            $redis = Yii::$app->redis;
//          如果验证通过
            if ($redis->get($mobile) == $sign) {
//                添加到高级代理表中、复制下级的绑定关系到t_player_member中、如果下级绑定在999下、更改为绑在当前代理下、删除redis关系、更改表状态
//                整体应该放在所有代理关系中
//                $games = Yii::$app->params['games'];
//                foreach ($games as $v) {
//                    if ($v == 1114112) {
//                        continue;
//                    }
//                    $db = Yii::$app->$v;

                    $info = $db->createCommand("SELECT * FROM t_player_member WHERE MEMBER_INDEX = '{$agent['playerid']}'")->queryOne();
                    if ($info && $info['PLAYER_INDEX'] != 999) {
                        $parent = $info['PLAYER_INDEX'];
                    } else {
                        $parent = 999;
                    }

                    $t = date('Y-m-d H:i:s', time());
                    $db->createCommand("INSERT IGNORE INTO t_daili_player (id, player_id, name, tel, parent_index, create_time, pay_back_gold, all_pay_back_gold, daili_level, status) VALUES (NULL,'{$agent['playerid']}', '{$agent['nickname']}', '{$mobile}', '{$parent}', '{$t}', '{$agent['cash_withdrawn']}', '{$agent['total']}', '3', 1)")->execute();

//                查询所有子玩家
                    $son = $redis->zrangebyscore($junior_relation_key, $uid, $uid);
                    foreach ($son as $v) {
                        $info = $db->createCommand("SELECT * FROM t_player_member WHERE MEMBER_INDEX = '{$v}'")->queryOne();
                        if ($info && $info['PLAYER_INDEX'] != $uid) {
                            $db->createCommand("UPDATE t_player_member SET PLAYER_INDEX = '{$uid}' WHERE MEMBER_INDEX  = '{$v}'")->execute();
                        } else {
                            $t = time();
                            $db->createCommand("INSERT INTO t_player_member VALUES(NULL, '{$uid}', '{$v}', '{$t}')")->execute();
                        }
                    }
//                }

//                删除这个人的初级代理关系
                $redis->zrem($junior_relation_key, $uid);
                $redis->zremrangebyscore($junior_relation_key, $uid, $uid);
                $db->createCommand("DELETE FROM junior_agent WHERE playerid = '{$uid}'")->execute();
                $db->createCommand("DELETE FROM junior_relation WHERE parent = '{$uid}'")->execute();

                $data['code'] = 1;
            } else {
                $data['code'] = 0;
            }
        } else {
            $data['code'] = 0;
        }

        $this->asJson($data);
    }

    /**
     * 玩家提现
     */
    public function actionWithdraw($playerid, $money)
    {
//        转化为分的数值是多少
        $point_money = 100 * $money;
        $db = Yii::$app->dbTest;
        $redis = Yii::$app->redis;
        $t = time();

        $data['code'] = 1;
        $max_count = self::TIXIAN_COUNT;
        $max_money = self::WITHDRAW;
        $junior_list_key = Yii::$app->params['platform_redis_key']['pk_junioragent_list'];

//        判断是否有这个用户
        $player = $redis->zscore($junior_list_key, $playerid);
        if (!empty($player)) {
//            判断关注了微信公众号
            $wx_player = $db->createCommand("SELECT WX_OPENID FROM t_game_wx_player WHERE PLAYER_INDEX = '{$playerid}'")->queryOne();

//            如果有对应的公众号openid、继续执行
            if ($wx_player['WX_OPENID']) {
                $player = $db->createCommand("SELECT * FROM junior_agent WHERE playerid = '{$playerid}' AND status = 1")->queryOne();

                if ($player === false) {
                    $data['code'] = 0;
                } else {
                    if ($player['total'] >= $max_money && $player['user'] >= $max_count && $player['cash_withdrawn'] >= $money) {
                        $db->createCommand("INSERT INTO junior_withdraw VALUES(NULL, '{$playerid}', '{$money}', '{$t}', 0)")->execute();
                        $order_data = [
                            'WX_MP' => 'qxmj_mp',
                            'WX_OPENID' => $wx_player['WX_OPENID'],
                            'TRUE_NAME' => '人人代理',
                            'ORDER_ID' =>  date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                        ];
//                        Yii::$app->params['current_game'] = 524803;
//                        file_put_contents('/tmp/order_with.log', print_r([$money, $order_data], 1), FILE_APPEND);
//                        $wechat = new WechatController();
//                        $info = WechatController::payDaili($order_data, $point_money,$order_data['ORDER_ID'], '支付订单' . $order_data['ORDER_ID'],$order_data['TRUE_NAME']);
//                        $wechat->outPayDaili();

//                        $wechat = new \WechatController();

//                        如果提现成功、更改状态/玩家神圣元宝数量等
//                        if ($info['result_code'] == 'SUCCESS') {
//                            $db->createCommand("UPDATE junior_agent SET `cash_withdrawn` = `cash_withdrawn` - {$money}, `withdrawn` = `withdrawn` + {$money} WHERE playerid = {$playerid}")->execute();
//                            $db->createCommand("UPDATE junior_withdraw SET `status` = 1 WHERE playerid = '{$playerid}'")->execute();
//                        }
                    } else {
                        $data['code'] = 0;
                    }
                }
            } else {
//                没有关注公众号、code=2
                $data['code'] = 2;
            }

        } else {
            $data['code'] = 0;
        }

        $this->asJson($data);
    }

    /**
     * 生成随机验证码
     */
    private static function randomSign($type = 4)
    {
        $str = '';
        for ($i = 0; $i < $type; $i++) {
            $str .= rand(0, 9);
        }
        return $str;
    }

    /**
     * 执行发送验证码
     */
    public function actionSmsSend($mobile, $message)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::SMS_ADDREST);

        curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD  , self::SMS_KEY);

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $mobile,'message' => $message));

        $res = curl_exec( $ch );
        curl_close( $ch );
//$res  = curl_error( $ch );
        return $res;
    }
}