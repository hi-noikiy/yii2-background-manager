<?php
/**
 * User: SeaReef
 * Date: 2018/10/24 19:50
 *
 * 人人代理
 */
namespace app\controllers\api;

use Yii;
use app\controllers\BaseController;
use yii\db\Query;
use yii\base\Curl;
use callmez\wechat\sdk\Wechat;

class AgentController extends BaseController
{
    /**
     * 申请高级代理的条件
     */
//    已邀请人数大于等于10
    const AGENT_COUNT = 10;

//    累计收益达到100
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
     * 客户端可控展示参数
     */
//    成为高级代理提升的返利比例
    const REVENUE = '35%';

//    最高可获得金额
    const INVITE_MAX = 1;

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

//    分享下载地址
    public $share_url = 'https://share-pk.dropgame.cn/';

    public $enableCsrfValidation = false;

//    微信授权code
    private $code;

//    数据连接池
    private $db;

    private $redis;

    /**
     * redis键名
     */
//    人人代理列表列表、
    private $junior_list_key;

//    人人代理代理关系表
    private $junior_relation_key;

//    人人代理参数表
    private $junior_params_key;

    /**
     * 模拟轮播图
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

    /**
     * 初始化方法
     */
    public function init()
    {
        $this->db = Yii::$app->db;
        $this->redis = Yii::$app->redis;

        $this->junior_list_key = Yii::$app->params['prk']['junior_list'];
        $this->junior_relation_key = Yii::$app->params['prk']['junior_relation'];
        $this->junior_params_key = Yii::$app->params['prk']['junior_params'];
    }

    /**
     * 判断玩家是否是初级代理
     *
     * @params playerid 玩家ID
     * @return code、0初级代理、1初级代理、2高级代理
     */
    public function actionIsJuniorAgent($playerid)
    {
        $agent = $this->redis->zscore($this->junior_list_key, $playerid);

//        是初级代理
        if (empty($agent)) {
            $data['code'] = 0;
            $high = (new Query())->select('id')->from('t_daili_player')->where(['player_id' => $playerid])->scalar();
//            是高级代理
            if ($high) {
                $data['code'] = 2;
            } else {
                $withdraw = $this->db->createCommand("SELECT tmp.playerid, tmp.withdraw, tmp2.weixin_nickname FROM
                                                      (SELECT playerid, withdraw FROM junior_withdraw ORDER BY create_time DESC LIMIT 5) AS `tmp`
                                                      LEFT JOIN
                                                      login_db.t_lobby_player AS `tmp2`
                                                      ON tmp.playerid = tmp2.u_id")->queryAll();
//                如果没有最新的五条提现记录
                if (empty($withdraw)) {
                    $withdraw = $this->simulation;
                }

                foreach ($withdraw as $v) {
                    $recently[$v['weixin_nickname']] = $v['withdraw'];
                }
                $data['Recetly'] = $recently;
            }
        } else {
            $junior = (new Query())->select('*')->from('junior_agent')->where(['playerid' => $playerid, 'status' => 1])->one();
            $data = [
                'code' => 1,
                'user' => $junior['user'],
                'today' => $junior['today'],
                'total' => $junior['total'],
                'cash_withdraw' => $junior['cash_withdrawn'],
                'user_limit' => $this->redis->hget($this->junior_params_key, 'agent_count') ? : self::AGENT_COUNT,
                'total_limit' => $this->redis->hget($this->junior_params_key, 'total_count') ? : self::TOTAL_COUNT,
                'revenue' => $this->redis->hget($this->junior_params_key, 'revenue') ? : self::REVENUE,
                'invite' => $this->redis->hget($this->junior_params_key, 'invite') ? : self::INVITE,
                'invite_max' => $this->redis->hget($this->junior_params_key, 'invite_max') ? : self::INVITE_MAX,
                'withdraw' => $this->redis->hget($this->junior_params_key, 'withdraw') ? : self::WITHDRAW,
                'tixian_count' => $this->redis->hget($this->junior_params_key, 'tixian_count') ? : self::TIXIAN_COUNT,
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
        $info = $this->redis->zadd($this->junior_list_key, $t, $playerid);

//        添加成功、持久化到mysql
        if ($info) {
            $player_info = (new Query())->select(['weixin_union_id', 'weixin_open_id', 'weixin_nickname'])->from('login_db.t_lobby_player')->where(['u_id' => $playerid])->one();
            if ($player_info) {
                $agent = $this->db->createCommand($sql = "INSERT INTO `junior_agent` VALUE(NULL, '{$playerid}', '{$player_info['weixin_union_id']}', '{$player_info['weixin_open_id']}', '{$player_info['weixin_nickname']}', '0', '0', '0', '{$t}', 1, '0.00', '0.00', '0.00', '0.00', 0)")->execute();

                if ($agent) {
                    $this->redis->zadd($this->junior_relation_key, 999, $playerid);
                    $this->db->createCommand("INSERT INTO junior_relation VALUES(NULL, '999', '{$playerid}', '{$t}', 1)")->execute();

                    $junior = $this->db->createCommand("SELECT * FROM junior_agent WHERE playerid = '{$playerid}' AND status = 1")->queryOne();
                    $data = [
                        'code' => 1,
                        'user' => intval($junior['user']),
                        'today' => intval($junior['today']),
                        'total' => intval($junior['total']),
                        'cash_withdraw' => intval($junior['cash_withdrawn']),
                        'user_limit' => $this->redis->hget($this->junior_params_key, 'agent_count') ? : self::AGENT_COUNT,
                        'total_limit' => $this->redis->hget($this->junior_params_key, 'total_count') ? : self::TOTAL_COUNT,
                        'revenue' => $this->redis->hget($this->junior_params_key, 'revenue') ? : self::REVENUE,
                        'invite' => $this->redis->hget($this->junior_params_key, 'invite') ? : self::INVITE,
                        'invite_max' => $this->redis->hget($this->junior_params_key, 'invite_max') ? : self::INVITE_MAX,
                        'withdraw' => $this->redis->hget($this->junior_params_key, 'withdraw') ? : self::WITHDRAW,
                        'tixian_count' => $this->redis->hget($this->junior_params_key, 'tixian_count') ? : self::TIXIAN_COUNT,
                    ];
                }
            }
        }

        $data['playerid'] = $playerid;

        $this->writeJson($data);
    }

    /**
     * 分享二维码
     *
     * @params $uid 玩家ID
     */
    public function actionShareQrcode($uid)
    {
        echo json_encode(['imageUrl' => Yii::$app->request->hostInfo . '/api/agent/share-qrcode1?uid=' . $uid]);
    }

    /**
     * 生成二维码
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
    public function actionBind()
    {
        ini_set('memory_limit', '521M');

        $request = Yii::$app->request;
        $session = Yii::$app->session;
        $uid = $request->get('uid');
        $this->code = Yii::$app->request->get('code','');

//        如果有uid
        if ($uid) {
            $send_user = $this->redis->zscore($this->junior_list_key, $uid);
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
                    $this->redis->zadd($this->junior_relation_key, $uid, $scan_uid);
                    $this->db->createCommand("INSERT INTO junior_relation VALUES(NULL, '{$uid}', '{$scan_uid}', '{$t}', 1)")->execute();
                    $info = Yii::$app->db->createCommand("UPDATE junior_agent SET `user` = `user` + 1, `today` = `today` + 1, `total` = `total` + 1, `cash_withdrawn` = `cash_withdrawn` + 1, `reward` = `reward` + 1 WHERE playerid = '{$uid}'")->execute();
                }
//                不是代理
            }
//            没有uid
        }

        return $this->redirect($this->share_url);
    }

    /**
     * 获取unionid
     */
    private function getUnionid(){
        $session = Yii::$app->session;
        file_put_contents('/tmp/renren_wx.log', PHP_EOL.date("Y-m-d H:i:s") .PHP_EOL. "union:" . $session->get('unionid'). '\n', FILE_APPEND);
        if($session->get('unionid')){
            return $session->get('unionid');
        }
        $this->getOpenid();
        return $session->get('unionid');
    }

    /**
     * 获取openid
     */
    private function getOpenid()
    {
        $wechat = Yii::$app->wechat;
        $session = Yii::$app->session;
        if (!$this->code) {
            $url = $this->GetCurUrl();
            //获取code
            header( 'Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $wechat->appId . '&redirect_uri=' . urlencode($url) . '&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect');exit;
        }
        $result = (new Curl())->get(Wechat::WECHAT_BASE_URL.Wechat::WECHAT_OAUTH2_ACCESS_TOKEN_URL.'appid='.$wechat->appId.'&secret='.$wechat->appSecret.'&code='.$this->code.'&grant_type=authorization_code');
        $result = json_decode($result,1);
//        file_put_contents('/tmp/wx.log', PHP_EOL.date("Y-m-d H:i:s") . "access_token:".PHP_EOL . print_r($result,1) . '\n', FILE_APPEND);
        $session->set('openid',$result['openid']);
        $session->set('unionid',$result['unionid']);
        $session->set('access_token',$result['access_token']);
        $session->set('wx_user_info',Yii::$app->wechat->getSnsMemberInfo($result['openid'],$result['access_token']));
        return $session->get('openid');
    }

    /**
     * 获取回调地址
     */
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

        $data = [];
        file_put_contents('/tmp/check_high.log', print_r($_GET, 1), FILE_APPEND);
//        判断高级代理条件
        $agent = $db->createCommand("SELECT * FROM junior_agent WHERE playerid = '{$uid}'")->queryOne();

        if ($agent['user'] >= self::AGENT_COUNT && $agent['total'] >= self::TOTAL_COUNT) {

            $redis = Yii::$app->redis;
//          如果验证通过
            if ($redis->get($mobile) == $sign) {
//                添加到高级代理表中、复制下级的绑定关系到t_player_member中、如果下级绑定在999下、更改为绑在当前代理下、删除redis关系、更改表状态
                $info = $db->createCommand("SELECT * FROM t_player_member WHERE MEMBER_INDEX = '{$agent['playerid']}'")->queryOne();
                if ($info && $info['PLAYER_INDEX'] != 999) {
                    $parent = $info['PLAYER_INDEX'];
                } else {
                    $parent = 999;
                }

                $t = date('Y-m-d H:i:s', time());
                $db->createCommand("INSERT IGNORE INTO t_daili_player (id, player_id, name, tel, parent_index, create_time, pay_back_gold, all_pay_back_gold, daili_level, status) VALUES (NULL,'{$agent['playerid']}', '{$agent['nickname']}', '{$mobile}', '{$parent}', '{$t}', '{$agent['cash_withdrawn']}', '{$agent['total']}', '3', 1)")->execute();

//                查询所有子玩家
                $son = $redis->zrangebyscore($this->junior_relation_key, $uid, $uid);
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
                $redis->zrem($this->junior_relation_key, $uid);
                $redis->zremrangebyscore($this->junior_relation_key, $uid, $uid);
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
     * @params playerid 玩家id
     * @params money 金额
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











































