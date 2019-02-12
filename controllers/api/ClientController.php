<?php
/**
 * User: SeaReef
 * Date: 2018/11/27 16:31
 */
namespace app\controllers\api;

use app\common\helpers\Sms;
use app\common\Tool;
use app\controllers\BaseController;
use app\models\Activity;
use Yii;
use yii\db\Query;
use wsl\ip2location\Ip2Location;
use app\common\RedisKey;
use app\common\Code;
use app\models\LobbyPlayer;
use app\models\LogUserActivity;
use app\common\DailiCalc;

class ClientController extends BaseController
{
    public $enableCsrfValidation = false;

    private $auth_given_yuanbao = 50;//认证后赠送元宝

    /**
     * IP、MAC黑名单redis配置
     */
    const MAC_BLACK = 'pk_mac_black';

    const ONE_IP_BLACK = 'pk_oneip_black';

    const MANY_IP_BLACK = 'pk_manyip_black';

    //    以下是功能性接口
    const REPORT_TIME = 180;

    public function actionLoginCheck($mac = 1)
    {
//        默认信息
        $data = [
            'down' => 1,
            'down_info' => '尊敬的一拳娱乐用户，本平台正在拼命维护优化中，请耐心等待，给您造成的不便敬请谅解，如需帮助，请联系客服1:PUKEQIPAI ,客服2：PKQP03',
            'login' => 1,
            'login_info' => '由于您近期游戏操作异常，暂时无法登录，请联系客服!',
        ];

        $redis = Yii::$app->redis_3;
//        判断是否更新维护
        $down = $redis->hget('downtime', 'time');
        $data['down'] = $down == 0 ? 0 : 1;
        if ($down == 0) {
            $data['down_info'] = $redis->hget('downtime', 'info');
            $this->writeJson($data);
        }

//        判断登录黑名单
        $request = Yii::$app->request;
        $ip = $request->userIP ?: '127.0.0.1';
        $ip = sprintf('%u', ip2long($ip));
        $mac = $request->get('mac', '');
        $login = $this->checkUser($ip, $mac);
        $data['login'] = $login ? 1 : 0;
        if (!$login) {
            $data['login_info'] = '由于您近期游戏操作异常，暂时无法登录，请联系客服!';
            $this->writeJson($data);
        }

//        先检测是否开服、在判断是否登录黑名单、再查询轮播图地址
        $img = (new Query())
            ->select(['img_url', 'jump_type', 'jump_url'])
            ->from('t_lunbo')
            ->all();

        foreach ($img as &$v) {
            if ($v['jump_type'] == 2) {
                $ids = explode('_', $v['jump_url']);
                $tmp1 = '';
                foreach ($ids as $id) {
                    $tmp = (new Query())
                        ->select('jump_id')
                        ->from('conf_gamejump')
                        ->where(['id' => $id])
                        ->scalar();
                    $tmp1 .= $tmp . '_';
                }
                $tmp1 = rtrim($tmp1, '_');
                $v['jump_url'] = $tmp1;
            }
        }

        $play_interval = $redis->get('lunbo_interval') ?: 2;
        $data['lunbo'] = [
            'img_url' => $img,
            'set' => [
                'play_interval' => $play_interval,
            ]
        ];

        $this->writeJson($data);
    }

    /**
     * 检测用户ip、mac黑名单
     * @params ip
     * @params mac
     */
    public function checkUser($ip, $mac)
    {
//        判断是否传递mac地址
        if (empty($mac)) {
            $this->writeResult(Code::CODE_ERROR);
        }

        $redis = Yii::$app->redis;
//        检测单IP
        $one = $redis->sismember(self::ONE_IP_BLACK, $ip);

//        检测IP段
        $ips = $redis->smembers(self::MANY_IP_BLACK);
        if (!empty($ips)) {
            foreach ($ips as $k) {
                $num = explode('-', $k);
                if ($ip >= $num[0] && $ip <= $num[1]) {
                    $many = true;
                } else {
                    $many = false;
                }
            }
        } else {
            $many = false;
        }

//        检测mac
        $mac = $redis->zscore(self::MAC_BLACK, $mac);

        if ($one || $many || $mac) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 手机号绑定获取的元宝数
     */
    const BIND_PHONE = 50;



    /***************************************登录后接口汇总*******************************************/
    public function actionPull()
    {
        $request = Yii::$app->request;
        $type = $request->get('pull_type', 'get_all');
        $uid = $request->get('uid');

        switch ($type) {
//            获取IP地址
            case 'get_ip':
                $data = $this->getIp();
                break;

//            获取轮播图信息
            case 'get_lunbo':
                $data = $this->getLunbo();
                break;

//            获取二维码
            case 'get_qrcode':
                $data = $this->getQrcode();
                break;

//            商城列表
            case 'get_goods':
                $data['goods_list'] = $this->getGoods();
                $data['vip_list'] = $this->getVIPList();
                $data['get_payment'] = $this->getPayment();
                break;

//            充值链接
            case 'get_payment':
                $data = $this->getPayment();
                break;

//            充值限额
            case 'get_userpay':
                $data = $this->getPay();
                break;

//            获取活动列表
            case 'get_activity':
                $data = $this->getActivity();
                break;

//            活动完成情况
            case 'get_activity_info':
                $data = $this->getActivityInfo();
                break;

//            手机号绑定
            case 'bind_phone':
                $data['bind_phone'] = $this->getIsBindPhone($uid);
                break;

            case 'get_exchange':
                $data = $this->isBind($uid);
                $switch = Yii::$app->params['exchange_white_list_switch'];
                if($switch == 1){
                    $whiteList = Yii::$app->params['exchange_white_list'];
                    if(in_array($uid,$whiteList)){
                        $data['bank']['condition']['is_use']=1;
                    }
                }
                break;

            case 'player_auth'://用户认证获取验证码
                $phone = $request->get('phone');
                $redis = Yii::$app->redis_1;
                $code = Sms::randNumber(4);
                $time = $redis->ttl('player_auth:' . $uid);
                if ($time > 0) {
                    $data['time'] = $time;
                    $data['status'] = 0;
                } else {
                    $redis->set('player_auth:' . $uid, $code);
                    $redis->expire('player_auth:' . $uid, 60);
                    $result = Sms::send($phone, '验证码：' . $code.'，60秒之内有效，期间无需重复获取【一拳网络】');
                    $result = json_decode($result,true);
                    if ($result['error'] == -40) {//错误手机号
                        $data['status'] = 2;
                        $data['time'] = '';
                    } else {
                        $data['status'] = 1;
                        $data['time'] = 60;
                    }
                }
                break;
            case 'get_all':
                $data['get_ip'] = $this->getIp();
                $data['get_lunbo'] = $this->getLunbo();
                $data['get_qrcode'] = $this->getQrcode();
                $data['get_userpay'] = $this->getPay();
                $data['get_activity'] = $this->getActivity();
                $data['exchange'] = $this->isBind($uid);
                $data['goods_list'] = $this->getGoods();
                $data['vip_list'] = $this->getVIPList();
                $data['get_payment'] = $this->getPayment();
                $data['bind_phone'] = $this->getIsBindPhone();

//                更新t_player数据
                $this->updatePlayer();

                break;
        }

        $this->writeJson($data);
    }

    /**
     * 玩家手机验证
     * @throws \yii\db\Exception
     */
    public function actionPlayerAuth()
    {
        $request = Yii::$app->request->get();
        $redis = Yii::$app->redis;
        $code = $redis->get('player_auth:' . $request['uid']);
        $login_db = Yii::$app->login_db;
        $db = Yii::$app->db;
        if (!$code) {//超时
            return $this->writeJson([
                'code' => 1,
                'msg' => 'code time out',
            ]);
        }
        if ($code != $request['code']) {//验证码错误
            return $this->writeJson([
                'code' => 2,
                'msg' => 'code error',
            ]);
        } else {
            if (isset($request['uid']) && $request['uid']) {
                $phone_num = $db->createCommand('select phone_number from login_db.t_lobby_player where u_id =' . $request['uid'])->queryScalar();
                if ($phone_num) {//是否已经验证过
                    return $this->writeJson([
                        'code' => 4,
                        'msg' => 'already auth'
                    ]);
                }
                $result_1 = $login_db->createcommand()->update('t_lobby_player', ['phone_number' => $request['phone']], 'u_id =' . $request['uid'])->execute();
                $result_2 = $db->createcommand()->update('t_player', ['phone_num' => $request['phone'], 'auth_time' => date('Y-m-d H:i:s', time())], 'player_id =' . $request['uid'])->execute();
                if ($result_1 !== false && $result_2 !== false) {
                    //认证成功充值元宝
//                    $present_data = [
//                        'sourceType' => 4,
//                        'propsType' => 3,
//                        'count' => $this->auth_given_yuanbao,
//                        'operateType' => 1,//增加元宝
//                        'gameId' => 1114112,//只有大厅游戏id,1114112
//                        'userId' => $request['uid']
//                    ];
//                    $present_url = Yii::$app->params['recharge_Url'];
//                    $curl = new Curl();
//                    $present_data = 'msg=' . json_encode($present_data, JSON_UNESCAPED_UNICODE);
//                    $info = $curl->get($present_url . '?' . $present_data);
//                    $info = json_decode($info, true);
                    return $this->writeJson([
                        'code' => 0,
                        'msg' => 'success'
                    ]);
                } else {
                    return $this->writeJson([
                        'code' => 3,
                        'msg' => 'auth fail'
                    ]);
                }
            }
        }
    }

    /**
     * 获取IP地址
     * @params ip 自动获取
     */
    private function getIp()
    {
        $request = Yii::$app->request;
        $request->ipHeaders = [
            'ali-cdn-real-ip',
        ];
        $ip = Yii::$app->request->userIp;

        $ipLocation = new Ip2Location();
        $locationModel = $ipLocation->getLocation($ip);
        return $locationModel->toArray();
    }

    /**
     * 获取轮播图
     */
    private function getLunbo()
    {
        $redis = Yii::$app->redis_3;
        $img = (new Query())
            ->select(['img_url', 'jump_type', 'jump_url'])
            ->from('t_lunbo')
            ->all();
        foreach ($img as &$v) {
            if ($v['jump_type'] == 2) {
                $ids = explode('_', $v['jump_url']);
                $tmp1 = '';
                foreach ($ids as $id) {
                    $tmp = (new Query())
                        ->select('jump_id')
                        ->from('conf_gamejump')
                        ->where(['id' => $id])
                        ->scalar();
                    $tmp1 .= $tmp . '_';
                }
                $tmp1 = rtrim($tmp1, '_');
                $v['jump_url'] = $tmp1;
            }
        }

        $play_interval = $redis->get('lunbo_interval') ?: 2;

        return [
            'lunbo' => $img,
            'set' => [
                'play_interval' => $play_interval,
            ]
        ];
    }

    /**
     * 获取商城列表
     */
    private function getGoods()
    {
        $db = Yii::$app->db;
        $goods_list[] = $db->createCommand("SELECT id, (category * 100) AS `num`, category AS `price`, goods_name as `desc` FROM conf_recharge WHERE category = 10 ORDER BY RAND() LIMIT 1")->queryOne();
        $goods_list[] = $db->createCommand("SELECT id, (category * 100) AS `num`, category AS `price`, goods_name as `desc` FROM conf_recharge WHERE category = 50 ORDER BY RAND() LIMIT 1")->queryOne();
        $goods_list[] = $db->createCommand("SELECT id, (category * 100) AS `num`, category AS `price`, goods_name as `desc` FROM conf_recharge WHERE category = 100 ORDER BY RAND() LIMIT 1")->queryOne();
        $goods_list[] = $db->createCommand("SELECT id, (category * 100) AS `num`, category AS `price`, goods_name as `desc` FROM conf_recharge WHERE category = 300 ORDER BY RAND() LIMIT 1")->queryOne();
        $goods_list[] = $db->createCommand("SELECT id, (category * 100) AS `num`, category AS `price`, goods_name as `desc` FROM conf_recharge WHERE category = 500 ORDER BY RAND() LIMIT 1")->queryOne();
        $goods_list[] = $db->createCommand("SELECT id, (category * 100) AS `num`, category AS `price`, goods_name as `desc` FROM conf_recharge WHERE category = 1000 ORDER BY RAND() LIMIT 1")->queryOne();

        return $goods_list;
    }

    /**
     * 获取二维码
     *
     * * 先模拟数据、后期调整
     */
    private function getQrcode()
    {
        Yii::info("获取二维码开始");
        $request = Yii::$app->request;
        $uid = $request->get('uid');

        $channel_id = Yii::$app->redis->zscore(RedisKey::CHANNEL_PLAYER, $uid);
        Yii::info("当前redis渠道id---".$channel_id);

        if (empty($channel_id)) {
            $channel_id = (new Query())
                ->select('channel_id')
                ->from('login_db.t_lobby_player')
                ->where(['u_id' => $uid])
                ->scalar();

            Yii::info("当前数据库渠道id---".$channel_id);

            if ($channel_id) {
                $agent_id = (new Query())
                    ->select('agent_id')
                    ->from('t_channel')
                    ->scalar();
                Yii::info("当前数据库渠道代理id---".$agent_id);

                $db = Yii::$app->db;
                $t = date('Y-m-d');
                $db->createCommand("INSERT IGNORE INTO t_player_member VALUES(NULL, $agent_id, $uid, '{$t}')")->execute();
                DailiCalc::bindDaili($agent_id,$uid);
                Yii::$app->redis->zadd(RedisKey::CHANNEL_PLAYER, $channel_id, $uid);

                Yii::info("绑定完成");
            }
        }

        $qrcode = 'https://share-pk.601yx.com/api/share/qrcode?gid=524803&uid='.$uid;
        $shareurl = 'https://share-pk.601yx.com/api/share/index?gid=524803&uid='.$uid;
        return [
            'imageurl' => $qrcode,
            'shareurl' => $shareurl,
        ];
    }

    /**
     * 获取充值限额
     *
     * 先模拟数据、后期调整
     */
    private function getPay()
    {
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        $type = $request->get('type');

        $redis = Yii::$app->redis;
        $data['on_game'] = $redis->hget(RedisKey::MONEY_CLIENT_CONFIG, 'on_game') ? : 5000;
        $data['off_game'] = $redis->hget(RedisKey::MONEY_CLIENT_CONFIG, 'off_game') ? : 5000;
        $data['top_num'] = $redis->hget(RedisKey::MONEY_CLIENT_CONFIG, 'top_num') ? : 10000;
        $data['all_num'] = $redis->hget(RedisKey::MONEY_CLIENT_CONFIG, 'all_num') ? : 10000;
        $data['money_switch'] = $redis->hget(RedisKey::MONEY_CLIENT_CONFIG, 'money_switch') ? : 0;

        $data['bIsShowRankingList'] = 1;
        $data['all_count']          = 0;
        $data['repay']              = 0;

        $data['pay'] = 1;

        return $data;
    }

    /**
     * 获取活动列表
     *
     * @params uid 玩家id
     */
    private function getActivity()
    {

        $uid = Yii::$app->request->get('uid');
        if (!$uid) {
            $this->writeResult(Code::CODE_UID_NOT_FOUND);
        }

        $t = date('Y-m-d H:i:s', time());
        //所有活动列表、普通活动
        $list = (new Query())
            ->select(['id', 'sort', 'title', 'title_url', 'img_url', 'goods_id', 'goods_num', 'jump_type', 'jump_url', 'activity_name'])
            ->from('conf_activity')
            ->where(['and', 'status = 1', "start_time < '{$t}'", "end_time > '{$t}'", 'type = 1'])
            ->andWhere([])
	        ->orderBy('sort')
            ->all();

        foreach ($list as $k => $v) {
//            活动是否领取
            $receive = (new Query())
                ->select('id')
                ->from('log_user_activity')
                ->where(['player_id' => $uid, 'activity_id' => $v['id'], 'operate_type' => LogUserActivity::OPERATE_TYPE_RECEIVE, 'is_operate' => LogUserActivity::OPERATE_FINISHED])
                ->scalar();
            if ($receive) {
                unset($list[$k]);
            } else {
//                活动是否点击
                $click = (new Query())
                    ->select('is_operate')
                    ->from('log_user_activity')
                    ->where(['player_id' => $uid, 'activity_id' => $v['id'], 'operate_type' => LogUserActivity::OPERATE_TYPE_CLICK, 'is_operate' => LogUserActivity::OPERATE_FINISHED])
                    ->scalar();
                $list[$k]['is_click'] = (int) $click;
                $list[$k]['price'] = (new Query())->select('price')->from('conf_recharge')->where(['id' => $v['goods_id']])->scalar();
            }
        }

        return array_merge($list, []);
    }

    /**
     * 活动完成情况
     */
    private function getActivityInfo()
    {
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        $activity_id = $request->get('activity_id');
        $operate_type = $request->get('operate_type', LogUserActivity::OPERATE_TYPE_RECEIVE);

        $info = (new Query())
            ->select('*')
            ->from('log_user_activity')
            ->where(['player_id' => $uid, 'activity_id' => $activity_id, 'operate_type' => $operate_type, 'is_operate' => LogUserActivity::OPERATE_FINISHED])
            ->one();

        $data['is_operate'] = $info['is_operate'] ? 1 : 0;
        return $data;
    }

    /**
     * 领取活动
     * @params uid 玩家ID
     * @params $activity_id 活动ID
     */
    public function actionReceiveActivity()
    {
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        $activity_id = $request->get('activity_id');

//        判断必要参数
        if (empty($uid) || empty($activity_id)) {
            $this->writeResult(Code::CODE_PARAMS_ERROR);
        }

        switch ($activity_id) {
//            领取新手礼包
            case 2:
                $this->ReceiveNovice($uid, $activity_id);
                break;
        }
    }

    /**
     * 领取新手礼包
     * @params uid 玩家id
     * @params activity_id
     */
    private function ReceiveNovice($uid = '', $activity_id = '')
    {
        if (empty($uid) || empty($activity_id)) {
            $this->writeResult(Code::CODE_PARAMS_ERROR);
        }

//        判断有没有这个玩家
        $user = LobbyPlayer::checkUser($uid);
        if (!$user) {
            $this->writeResult(Code::CODE_UID_NOT_FOUND);
        }

//        判断有没有这个活动
        $activity_info = Activity::isValid($activity_id);
        if (!$activity_info['status']) {
            $this->writeResult(Code::CODE_ACTIVITY_HAS_INVALID);
        }

//        判断活动是否领取
        $is_receive = LogUserActivity::isReceive($uid, $activity_id);
        if ($is_receive) {
            $this->writeResult(Code::CODE_ACTIVITY_HAS_COMPLETED);
        }

//        发送活动奖励
        $res = Tool::sendGold(Tool::RECHARGE_ACTIVITY, Tool::PROPS_TYPE, $activity_info['goods_num'], Tool::GOLD_INCR, $uid);
        if ($res) {
            $res = LogUserActivity::saveActivityLog($uid, $activity_id, LogUserActivity::OPERATE_TYPE_RECEIVE);
            if ($res) {
                $this->writeResult(Code::CODE_OK);
            }
            else {
                $this->writeResult(Code::CODE_ERROR);
            }
        } else {
            $this->writeResult(Code::CODE_ERROR);
        }
    }

    /**
     * 点击活动功能
     */
    public function actionClickActivity()
    {
        $request = Yii::$app->request;
        $uid = $request->get('uid');
        $activity_id = $request->get('activity_id');

//        判断参数
        if (empty($uid) || empty($activity_id)) {
            $this->writeResult(Code::CODE_PARAMS_ERROR);
        }

//        判断有没有这个玩家
        $user = LobbyPlayer::checkUser($uid);
        if (!$user) {
            $this->writeResult(Code::CODE_UID_NOT_FOUND);
        }

//        判断有没有这个活动
        $activity_info = Activity::isValid($activity_id);
        if (!$activity_info['status']) {
            $this->writeResult(Code::CODE_ACTIVITY_HAS_INVALID);
        }

//        判断活动是否领取
        $is_receive = LogUserActivity::isReceive($uid, $activity_id);
        if ($is_receive) {
            $this->writeResult(Code::CODE_ACTIVITY_HAS_COMPLETED);
        }

//        执行点击记录
        $res = LogUserActivity::saveActivityLog($uid, $activity_id, LogUserActivity::OPERATE_TYPE_CLICK);
        if ($res) {
            $this->writeResult(Code::CODE_OK);
        } else {
            $this->writeResult(Code::CODE_ERROR);
        }
    }


    /**
     * 下单回调url
     */
    const PAY_URL = 'http://pay.game0165.com';

    /**
     * 充值链接
     */
    private function getPayment()
    {
        $request = Yii::$app->request;
        $uid = $request->get('uid', 111);
        $res = Tool::checkPaymentWhite($uid);

        if ($res) {
            $list = (new Query())
                ->select(['pay_name', 'pull_type', 'id'])
                ->from('conf_payment')
                ->all();
        } else {
            $list = (new Query())
                ->select(['pay_name', 'pull_type', 'id'])
                ->from('conf_payment')
                ->where(['status' => 1])
                ->all();
        }

        foreach ($list as $k => $v) {
            $pay_channel = (new Query())->select('pay_channel')->from('conf_payment_channel')->where(['payment' => $v['id'], 'master' => 1])->scalar();
            $data[$v['pay_name']]['url'] = (new Query())->select('launch_url')->from('conf_pay_channel')->where(['id' => $pay_channel])->scalar();
            $data[$v['pay_name']]['pull_type'] = $v['pull_type'];
        }

        return $data;
    }

    /**
     * 获取用户是否绑定手机号
     */
    public function getIsBindPhone($uid = 1)
    {
        $data['phone'] = (new Query())
            ->select('phone_number')
            ->from('login_db.t_lobby_player')
            ->where(['u_id' => $uid])
            ->scalar() ? : '';
        if ($data['phone']) {
            $data['gold_num'] = $this->auth_given_yuanbao;
        } else {
            $data['gold_num'] = $this->auth_given_yuanbao;
        }


        return $data;
    }

    /**
     * 是否绑定银行卡或者支付宝
     *
     * is_bind 0未绑定 1绑定
     */
    public function isBind($playerId){
        $status=0;//默认未绑定
        $condition = Yii::$app->params['bind_condition'];
        $aliCon=array();$bankCon=array();$wechatCon=array();
        if($condition){
            $aliCon     = $condition['ali'];
            $bankCon    = $condition['bank'];
            $wechatCon    = $condition['wechat'];
        }
        $data=array(
            'ali'=>['condition'=>$aliCon,'data'=>[],'is_bind'=>$status,'type'=>1],
            'bank'=>['condition'=>$bankCon,'data'=>[],'is_bind'=>$status,'type'=>2],
            'wechat'=>['condition'=>$wechatCon,'data'=>[],'is_bind'=>1,'type'=>3],//微信默认绑定
        );

        if($playerId){
            $res = (new Query())
                ->select('name,code,type')
                ->from('t_exchange')
                ->where(['player_id'=>$playerId])
                ->all();

            $lobbyPlayer = new LobbyPlayer();
            $playerInfo = $lobbyPlayer->getPlayer($playerId);
            if($playerInfo && isset($playerInfo['weixin_nickname'])){
                $data['wechat']['data']['name'] = $playerInfo['weixin_nickname'];
            }else{
                $data['wechat']['data']['name'] = '';
            }

            if($res){
                foreach ($res as $key=>$val){
                    $status=0;//默认未绑定
                    if($val['type'] == 1){
                        $status = 1;
                        $data['ali']['data'] = $val;
                        $data['ali']['is_bind'] = $status;
                    }else if($val['type'] == 2){
                        $status=1;
                        $data['bank']['data'] = $val;
                        $data['bank']['is_bind'] = $status;
                    }
                    unset($data[$key]['type']);
                }
            }
        }


        return $data;
    }

    /**
     * 更新t_player数据
     */
    private function updatePlayer()
    {
        $request = Yii::$app->request;
        $player_id = $request->get('uid');

        $player_info = (new Query())
            ->select('id')
            ->from('t_player')
            ->where(['player_id' => $player_id])
            ->scalar();

//        没有用户进行添加
        if (!$player_info) {
            $player_info = (new Query())
                ->select('*')
                ->from('login_db.t_lobby_player')
                ->where(['u_id' => $player_id])
                ->one();
            if ($player_info) {
                $db = Yii::$app->db;
                $db->createCommand()->insert('t_player', [
                    'player_id' => $player_info['u_id'],
                    'openid' => '',
                    'nickname' => $player_info['weixin_nickname'] ? : 0,
                    'machine_code' => $player_info['machine_code'] ? : 0,
                    'head_img' => $player_info['head_img'] ? : 0,
                    'phone_num' => $player_info['phone_number'] ? : 0,
                    'reg_time' => $player_info['reg_time'] ? : '1000-01-01 00:00:00',
                    'last_login_time' => $player_info['last_login_time'] ? : '1000-01-01 00:00:00',
                    'ip' => $player_info['ip'] ? : '1',
                    'sex' => $player_info['sex'] ? : '1',
                    'province' => $player_info['province'] ? : '1',
                    'city' => $player_info['city'] ? : '1',
                    'status' => 1,
                    'auth_time' => '1000-01-01 00:00:00',
                    'channel_id' => $player_info['channel_id'],
                ])->execute();
            }
        }
    }

    /**
     * 获取VIP充值列表
     *
     */
    public function getVIPList()
    {
        $list = (new Query())
            ->select('*')
            ->from('t_vip_recharge')
            ->where(['status'=>0])
            ->all();

        return $list;
    }

    /**
     * 举报用户
     */
    public function actionReport()
    {
        $r = $_REQUEST;
        $redis = Yii::$app->redis;
        $db = Yii::$app->db;
        Yii::info("report--".date('Y-m-d H:i:s', time()).json_encode($_REQUEST));
        //1、解析信息上报信息
        $key = 'pk_report_' . $r['gid'] . $r['playerid'] . $r['be_report'];
        $has = $redis->get($key);

        //如果有数据、此玩家已经举报过该玩家
        if ($has) {
            $ttl = $redis->ttl($key);
            $data = [
                'code' => 0,
                'gid' => $r['gid'],
                'playerid' => $r['playerid'],
                'ttl' => $ttl,
            ];
        } else {
            $redis->set($key, 1);
            $redis->expire($key, self::REPORT_TIME);

            $option1 = isset($r['option1']) ? 1 : 0;
            $option2 = isset($r['option2']) ? 1 : 0;
            $option3 = isset($r['option3']) ? 1 : 0;
            $option4 = isset($r['option4']) ? 1 : 0;
            $option5 = isset($r['option5']) ?: "";

            $mobile = isset($r['mobile']) ? $r['mobile'] : "";
            $wechat = isset($r['wechat']) ? $r['wechat'] : "";
            $qq = isset($r['qq']) ? $r['qq'] : "";

            //插入数据库记录
            $t = date('Y-m-d H:i:s', time());
            Yii::info("report--222".json_encode($r));
            try{
                Yii::info("report--333");
                $info = $db->createCommand($sql = "INSERT INTO `player_report` VALUES(NULL, '{$r['playerid']}', '{$r['be_report']}', '{$r['tableid']}', '{$r['gid']}', '{$option1}', '{$option2}','{$option3}', '{$option4}', '{$option5}', '{$t}', '{$mobile}', '{$qq}', '{$wechat}')")->execute();
//                echo $sql;

                Yii::info("report--444");
                $data = [
                    'code' => 1,
                    'gid' => $r['gid'],
                    'playerid' => $r['playerid'],
                    'ttl' => self::REPORT_TIME,
                ];
            }catch (Exception $e){
                Yii::info('举报失败！');
                $data['code'] = 101;
                $data['gid'] = 0;
                $data['playerid'] = 0;
                $data['ttl'] = 0;
            }

        }

        $this->asJson($data);
    }
}