<?php
/**
 * User: SeaReef
 * Date: 2018/9/4 21:05
 *
 * 微信后台
 */

namespace app\controllers;

use app\common\Common;
use app\common\DailiCalc;
use app\common\helpers\Sms;
use app\common\RedisKey;
use app\common\Tool;
use app\models\AgentActivity;
use app\models\ConfRebateRatio;
use app\models\DailiPlayer;
use app\models\LobbyPlayer;
use app\models\LogAgentActivity;
use app\models\LogRebate;
use Yii;
use yii\base\Curl;
use yii\db\Query;
use callmez\wechat\sdk\MpWechat;
use app\common\Code;

class WechatController extends CommonBaseController
{
    public $Default_Page = 1;
    public $Default_Limit = 20;
    private $Daili_Access_Token = 'daili_access_token';
    private $rootUrl = 'https://oss-fenxiang.601yx.com';

    private $redirectUrl = 'https://oss.601yx.com/wechat/index?activity=1';

    private $dailiRelation = 'weixin_daili_relation';

    //企业付款接口地址
    private $companyRechargeUrl = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    //企业付款api秘钥
    private $api_key = '7293edb63c09a81dbc6b2f6e3aacd9fc';
    /**  商户appid */
    const MCH_APPID = 'wx5223c60abfaaf719';
    /** 商户号 */
    const MCHID = '1515631881';

    /**
     * uid与openid、unionid对应关系
     */
    const DAILI_RELATION = 'weixin_daili_relation';

    /**
     * 代理提现队列redisKey
     */
    const PAY_DAILI_MONEY = 'pay_daili_money';

    /**
     * 联系客服微信
     */
    const WX_CUSTOMER = '您还不是代理，请联系微信客服：PPPK0317';

    /**
     * 模板消息 模板id
     */
    //申请通知
    const APPLICATION_NOTICE = 'Cubtk96z-RBrd6WBTqNjX0DNZmXtMOe7lSFd9Vv1itk';
    //成为会员
    const BECOME_DAILI = 'YFgFgANbOs2JOqrjDNB-o1qYsuZp0ge0_j_sdNsn0V8';

    /**
     * 调取接口的access_token地址
     */
    const ACCESS_TOKEN = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';

    /**
     * 最小提现金额，单位“分”、不是“元”
     */
    const MIN_TAKE_MONEY = 5000;

    /**
     * 最大提现金额，单位“分”、不是“元”
     */
    const MAX_TAKE_MONEY = 500000;




    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;//禁用CSRF

        //验证是否是来自微信的请求（为在本地测试暂时注释，需要打开）
        $wechat = Yii::$app->wechat;
        if (!$wechat->checkSignature()) {
            //return false;
        };

        ////////////////下面注释暂不需要打开//////////////////
        /*$session = Yii::$app->session;
        $user_id = $session->get('user_id');
        if (!$user_id){
            return $this->redirect('/wechat/index');
        }*/

        return true;
    }

    /**
     * 服务器配置验证
     */
    public function actionVerify()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $echostr = $_GET['echostr'];

        $token = Yii::$app->wechat->token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            exit($echostr);
        } else {
            exit('失败了');
        }
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
                file_put_contents('/tmp/wx.log', date('Y-m-d H:i:s', time()) . '\n' . '-获取微信接口access_token失败' . PHP_EOL, FILE_APPEND);
                return false;
            }

            file_put_contents('/tmp/wx.log', date('Y-m-d H:i:s', time()) . '-获取微信接口access_token:' . $result['access_token'] . PHP_EOL, FILE_APPEND);
        }

        return $access_token;
    }

    /**
     * 创建自定义菜单
     */
    public function actionCreateMenu()
    {
        $menu = [
            [
                "type" => "view",
                "name" => "游戏下载",
                "url" => $this->rootUrl . "/api/share/down"
            ],
            [
                "type" => "view",
                "name" => "代理后台",
                "url" => $this->rootUrl . "/wechat/index"
            ]
        ];

        return Yii::$app->wechat->createMenu($menu);
    }

    /**
     * 发送模板消息
     */
    private function sendTemplateMessage($data)
    {
        file_put_contents('/tmp/wx.log', date('Y-m-d H:i:s', time()) . '模板消息数据：' . PHP_EOL . print_r($data, 1) . PHP_EOL, FILE_APPEND);
        file_put_contents('/tmp/wx.log', date('Y-m-d H:i:s', time()) . '模板消息数据：' . PHP_EOL . print_r($data, 1) . PHP_EOL, FILE_APPEND);
        file_put_contents('/tmp/wx.log', date('Y-m-d H:i:s', time()) . '调用接口access_token：' . PHP_EOL . $this->getAccessToken() . PHP_EOL, FILE_APPEND);
        $wechat = Yii::$app->wechat;
        $result = (new Curl())->setRawPostData(json_encode($data, JSON_UNESCAPED_UNICODE))->post(MpWechat::WECHAT_BASE_URL . MpWechat::WECHAT_TEMPLATE_MESSAGE_SEND_PREFIX . '?access_token=' . $this->getAccessToken());
        file_put_contents('/tmp/wx.log', date('Y-m-d H:i:s', time()) . '模板消息结果：' . PHP_EOL . print_r($result, 1) . PHP_EOL, FILE_APPEND);

        return isset($result['msgid']) ? $result['msgid'] : false;
    }

    /**
     * 获取网页授权验证code
     */
    public function getCode()
    {
        $session = Yii::$app->session;
        $user_id = $session->get('user_id');
        if (!$user_id) {
            $wechat = Yii::$app->wechat;
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $wechat->appId . '&redirect_uri=' . urlencode($this->redirectUrl) . '&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
            header('Location:' . $url);
        } else {
            return $this->writeResult(Code::CODE_OK);
        }
    }

    /**
     * 首页
     * @return string
     */
    public function actionIndex()
    {
        $wechat = Yii::$app->wechat;
        $code = Yii::$app->request->get('code');
        $session = Yii::$app->session;
        $user_id = $session->get('user_id');
        $activity = isset($_REQUEST['activity']) ? $_REQUEST['activity'] : 0;

        /** 通过code获取授权access_token */
        if ($code && !$user_id) {
            //测试
            $result = $wechat->getOauth2AccessToken($code);
//            $result['openid'] = 'o5J1a1XZu-4tedR3lYHT2QaGHniU';
//            $result['unionid'] = 'ooZvs0jSY-cE2SmhRDHXr8WhLqBo';

            /** 存储用户的access_token */
            if (!isset($result['openid'])) {
                return $this->render('nw_error', ['msg' => self::WX_CUSTOMER]);
            }

            //TODO::存储refresh_token
            $lobbyModel = new LobbyPlayer();
            $user = $lobbyModel->getPlayerInfo(['weixin_union_id' => $result['unionid']], "*", 2);

            /** 非游戏用户 */
            if (!$user) {
                return $this->render('nw_error', ['msg' => self::WX_CUSTOMER]);
            }

            $dailiModel = new DailiPlayer();
            $myUserInfo = $dailiModel->getById($user['u_id']);

            /** 非代理 */
            if (!$myUserInfo) {
                return $this->render('nw_error', ['msg' => self::WX_CUSTOMER]);
            }

            $session->set('user_id', $user['u_id']);
            $user_id = $session->get('user_id');
            Yii::$app->redis->hset($this->dailiRelation, $user['u_id'], json_encode(['player_id' => $user['u_id'], 'union_id' => $result['unionid'], 'openid' => $result['openid']], JSON_UNESCAPED_UNICODE));

        } else if (!$code && !$user_id) {
            $this->getCode();
        }

        if (!Yii::$app->params['pay_daili_forbid_switch']) {
            $session = Yii::$app->session;
            $my_user = $session->get('user_id');
            $blackSwitch = Yii::$app->params['wechat_web_black_switch'];
            if($blackSwitch == 1){
                $blackList = Yii::$app->params['wechat_web_black_list'];
                if(in_array($my_user,$blackList)){
                    return $this->render('nw_error', ['msg' => self::WX_CUSTOMER]);
                }
            }

            $return = $this->playerAndMemberNums();
            $return['activity'] = $activity;
            $return['rebateSwitch'] = 0;
            if(Yii::$app->params['wechat_web_rebate_switch']){
                $rebateBlackList = Yii::$app->params['wechat_web_rebate_back_list'];
                if(in_array($my_user,$rebateBlackList)){
                    $return['rebateSwitch'] = 1;
                }
            }

            return $this->render('index', $return);
        }

        /*
        $user_list = Yii::$app->params['can_login_userId'];
        if (strstr($user_list, $user_id)) {
            return $this->render('index', $this->playerAndMemberNums());
        } else {
            echo '<div style="width:800px;text-align:center;font-size: 80px;">代理后台维护中</div>';
            exit;
        }
        */
    }

    private function checkSignature()
    {
        $signature = Yii::$app->request->get("signature");
        $timestamp = Yii::$app->request->get("timestamp");
        $nonce = Yii::$app->request->get("nonce");
        $tmpArr = array($timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($signature == $tmpStr) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检测活动页
     * @param activity: 是否检测活动页 1：是
     */
    public function actionCheckActivityPage(){
        $playerId = $this->session->get('user_id');

        //检测开关
        $switch = Yii::$app->params['agent_activity_switch'];
        if($switch == 1){
            $whiteList = Yii::$app->params['agent_activity_white_list'];
            if(! in_array($playerId,$whiteList)){
                return $this->writeResult(Code::OK,'success',[]);
            }
        }

        //内部跳转页面不触发
        if(! $_REQUEST['activity'] || $_REQUEST['activity'] != 1){
            return $this->writeResult(Code::OK,'success',[]);
        }

        //用与前端连续弹窗
        $thisActivityId = $_REQUEST['id'];

        $agentActivityModel = new AgentActivity();
        $thisDate = date('Y-m-d H:i:s');
        $where[] = "status = 1";
        $where[] = "start_time < '".$thisDate."'";
        $where[] = "end_time > '".$thisDate."'";
        $where = implode(" and ",$where);

        $activity = $agentActivityModel->getDataByCon($where,'*',7,0,0,'',"sort");
        $logActivityModel = new LogAgentActivity();
        foreach ($activity as $key=>$val){
            if($val['show_type'] == 1){
                $con=[];
                $con[] = 'player_id = '.$playerId;
                $con[] = 'activity_id = '.$val['id'];
                $con[] = 'operate_date = "'.date('Y-m-d').'"';
                $con = implode(" and ",$con);
                if($logActivityModel->getDataByCon($con,'id',3)){
                    $activity[$key] = [];
                }
            }else if($val['show_type'] == 3){
                $thisTime = date('H:i:s');
                if($val['show_time_start'] > $thisTime || $val['show_time_end'] < $thisTime){
                    $activity[$key] = [];
                }
            }
        }

        //跳过不符合条件的活动页
        $i = $thisActivityId;
        if(!isset($activity[$thisActivityId]) || !$activity[$thisActivityId]){
            while ($i < count($activity)){
                $i += 1;
                if(isset($activity[$i]) && !empty($activity[$i])){
                    break;
                }
            }
        }

        if($i > count($activity)-1){
            return $this->writeResult(Code::OK,'success',[]);
        }

        $result = $activity[$i];
        $result['num'] = $i;

        /** 记录用户访问活动log */
        if($result){
            $this->setAgentActivityLog($result,$playerId);
        }

        return $this->writeResult(Code::OK,'success',$result);
    }

    public function setAgentActivityLog($activityData,$playerId){
        $logData['player_id'] = $playerId;
        $logData['activity_id'] = $activityData['id'];

        $logAgentActivityModel = new LogAgentActivity();
        if($logAgentActivityModel->setLogAgentActivity($logData)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取渠道合伙人伞下数据
     */
    public function actionGetChannelInfo()
    {
        $my_user = $this->getUserId();
        $return = [];
        $this->totalPlayers($my_user, $return);
        array_pop($return);
        $players = implode(',', $return);

        //总消耗
        $allCost = round($this->playersCost($players) / 110, 2);

        //当月消耗
        $start_month = strtotime(date('Y-m-01'));
        $end_month = strtotime($start_month . '+1 month');
        $monthCost = round($this->playersCost($players, $start_month, $end_month) / 110, 2);

        //代理数量
        $dailiCount = $this->dailiNum($players);

        //玩家数量
        $playerCount = count($return) - $dailiCount;

        //今日新增代理
        $addDaili = (new Query())
            ->select('*')
            ->from('t_daili_player')
            ->where('parent_index in (' . $players . ') and unix_timestamp(create_time) >= ' . strtotime('today') . ' and unix_timestamp(create_time) <' . strtotime('tomorrow'))
            ->count();
        //今日新增玩家
        $addPlayer = (new Query())
            ->select('*')
            ->from('t_player_member')
            ->where('player_index in (' . $players . ') and bind_time >= ' . strtotime('today') . ' and bind_time <' . strtotime('tomorrow'))
            ->count();
        return $this->writeResult(Code::CODE_OK, '', ['all_cost' => $allCost, 'month_cost' => $monthCost, 'daili_count' => $dailiCount, 'player_count' => $playerCount, 'add_daili' => $addDaili, 'add_player' => $addPlayer - $addDaili]);
    }

    /**
     * 伞下所有人
     */
    private function totalPlayers($player_id, &$return = [])
    {
        $member_index = (new Query())
            ->select('member_index')
            ->from('t_player_member')
            ->where('player_index =' . $player_id)
            ->all();
        if ($member_index) {
            $member_index = array_column($member_index, 'member_index');
            foreach ($member_index as $v) {
                $this->totalPlayers($v, $return);
            }
        }
        array_push($return, $player_id);
    }

    /**
     * 消耗统计
     */
    private function playersCost($players, $start_time = '', $end_time = '')
    {
        $where = [];
        if ($players) {
            $where[] = "player_index in ({$players})";
        }
        if ($start_time) {
            $where[] = "unix_timestamp(day)>={$start_time}";
        }
        if ($end_time) {
            $where[] = "unix_timestamp(day)<{$end_time}";
        }
        $where = implode(' and ', $where);
        $result = '';
        if ($where) {
            $result = (new Query())
                ->select('sum(num)')
                ->from('t_oper_user_expend_day')
                ->where($where)
                ->scalar();
        }

        return $result ? $result : 0;
    }

    /**
     * 消耗列表
     */
    public function actionGetCostList()
    {
        $my_user = $this->getUserId();
        $return = [];
        $this->totalPlayers($my_user, $return);
        array_pop($return);
        $players = implode(',', $return);
        $start_time = Yii::$app->request->get('start_time');
        $end_time = Yii::$app->request->get('end_time');
        $page = Yii::$app->request->get('page', 1);
        $limit = Yii::$app->request->get('limit', 10);
        $where = [];
        if ($players) {
            $where[] = "player_index in ({$players})";
        }
        if ($start_time) {
            $start_time = strtotime($start_time);
            $where[] = "unix_timestamp(day)>={$start_time}";
        }
        if ($end_time) {
            $end_time = strtotime($end_time) + 86400;
            $where[] = "unix_timestamp(day)<{$end_time}";
        }
        $where = implode(' and ', $where);
        $result = (new Query())
            ->select('day,sum(num) as sum')
            ->from('t_oper_user_expend_day')
            ->where($where)
            ->groupBy('day')
            ->orderBy('day desc')
            ->offset(($page - 1) * $limit)
            ->limit($limit)
            ->all();
        if ($result) {
            $result = array_map(function ($val) {
                $val['sum'] = round($val['sum'] / 110, 2);
                return $val;
            }, $result);
        }
        $count = (new Query())
            ->select('day,sum(num) as sum')
            ->from('t_oper_user_expend_day')
            ->where($where)
            ->groupBy('day')
            ->count();
        return $this->writeLayui(Code::CODE_LAYUI_OK, 'ok', $count, $result ? $result : []);
    }

    /**
     * 上月和当月消耗(返回值单位为元)
     */
    public function actionGetSameLast()
    {
        $my_user = $this->getUserId();
        $return = [];
        $this->totalPlayers($my_user, $return);
        array_pop($return);
        $players = implode(',', $return);
        $start_time1 = strtotime(date('Y-m-01'));
        $end_time1 = strtotime($start_time1 . '+1 month');
        $end_time2 = strtotime($end_time1 . '+1 month');
        $sameCost = $this->playersCost($players, $start_time1, $end_time1);
        $lastCost = $this->playersCost($players, $end_time1, $end_time2);
        return $this->writeResult(Code::CODE_OK, '', ['same_cost' => $sameCost ? round($sameCost / 110, 2) : 0, 'last_cost' => $lastCost ? round($lastCost / 110, 2) : 0]);
    }

    /**
     * 提现
     * @return bool
     */
    public function actionTakeMoney()
    {
        if (Yii::$app->params['pay_button_switch']) {//所有人可提
            Yii::info('提现按钮已打开');
            $my_user = $this->session->get('user_id');

            $this->withdraw($my_user);
        } else {
            Yii::info("提现按钮关闭,仅对内部人开放");
            $my_user = $this->session->get('user_id');

            //关闭时，可操作id
            $canWithdrawId = Yii::$app->params['can_withdraw_deposit_id'];
            if (!strstr($canWithdrawId, $my_user)) {
                return false;
            }

            $this->withdraw($my_user);
        }

        return false;
    }

    /**
     * 代理提现处理方法
     *
     * @param $my_user
     */
    public function withdraw($my_user)
    {
        if($my_user == '30601818'){
            return $this->writeResult(Code::CODE_NOT_BIND, '您好,您暂时无法提现');
        }

        $dailiModel = new DailiPlayer();
        $tel = $dailiModel->getById($my_user,2)['tel'];
        if(!$tel){
            return $this->writeResult(Code::CODE_NOT_BIND, '您好，您绑定手机号，请到我的信息页面绑定手机后重试');
        }

        $openid = Yii::$app->redis->hget(self::DAILI_RELATION, $my_user);
        $openid = json_decode($openid, 1)['openid'];

        $db = Yii::$app->db;

        //提现金额为元
        $cash = Yii::$app->request->post('cash');
        $cash = floor($cash);//暂时只能提整数

        //金币数为金额的100倍
        $gold = $cash * self::REBATE_RATIO;
        $real_name = Yii::$app->request->post('real_name');
        $downLine = Yii::$app->params['withdraw_deposit_down_line'];
        $upLine = Yii::$app->params['withdraw_deposit_up_line'];

        //同步真实姓名
        $dailiModel->updateDailiPlayer($my_user,['true_name'=>$real_name],2);

//        if ((strtotime('today') + 60 * 60 * 2) > time() || time() > (strtotime('today') + 60 * 60 * 22)) {
//            return $this->writeResult(Code::CODE_NOT_WITHDRAW_TIME, '非可提现时间');
//        }
        if ($cash < $downLine || $cash > $upLine) {
            if ($cash > $upLine) {
                return $this->writeResult(Code::CODE_WITHDRAW_UP_RANGE, '您好，您的提现金额超过5000元，请更改提现金额');
            } else if ($cash < $downLine) {
                return $this->writeResult(Code::CODE_WITHDRAW_LOW_RANGE, '您好，您的提现金额未满20元，请更改提现金额');
            }
        }

        $userInfo = $dailiModel->getDataByCon(['player_id' => $my_user], "*", 2);
        if (!$userInfo) {
            return $this->writeResult(Code::CODE_NOT_USER_INFO);
        }

        if (!$userInfo['true_name']) {//无真实姓名
            return $this->writeResult(Code::CODE_NOT_REAL_NAME);
        }

        if ($userInfo['pay_back_gold'] < $gold) {
            return $this->writeResult(Code::CODE_WITHDRAW_NO_ENOUGH, '您的余额不足');
        }

        if ($real_name != $userInfo['true_name']) {
            return $this->writeResult(Code::CODE_REAL_NAME_WRONG, '真实姓名错误');
        }

        $tran = $db->beginTransaction();
        $order_id = 'Pay' . time() . Sms::randNumber(12);
        $re_1 = $this->createOrder($order_id, $my_user, $real_name, $gold);

        //更新可提现金额,增加冻结额度
        $re_2 = $this->updateGold($my_user, $gold);
        Yii::info('扣除金币结果:' . json_encode($re_2));

        $wechat_after_gold = $dailiModel->getDataByCon(['player_id' => $my_user], 'pay_back_gold', 3);

        if (!$re_1 || !$re_2) {
            $tran->rollBack();
            $wechat_after_gold = $dailiModel->getDataByCon(['player_id' => $my_user], 'pay_back_gold', 3);
            Yii::info('扣除金币失败，回滚后金币:' . $wechat_after_gold);

            return $this->writeResult(Code::CODE_ERROR);
        }

        //写入提现队列
        //提现金额单位为分//兑换比例是 1： 110
        $result = $this->payDaili($openid, $cash * 100, $order_id);

        //微信企业付款 写入日志 更新信息 提现次数 提现次数
        $tran->commit();
        Yii::info('开始提现前金币:' . $userInfo['pay_back_gold']);
        Yii::info('扣除后金币数:' . $wechat_after_gold);

        return $this->writeResult(Code::CODE_OK);
    }

    private function updateGold($my_user, $gold)
    {
        if (!$my_user || !$gold) {
            return false;
        }

        $AgentModel = new DailiPlayer();
        $beforeGold = $AgentModel->getDataByCon(['player_id' => $my_user], 'pay_back_gold', 3);
        $frozenMoney = $AgentModel->getDataByCon(['player_id' => $my_user], 'forzen_money', 3);
        $beforeGold -= $gold;
        $frozenMoney += $gold;
        Yii::info("可提现金额：" . $beforeGold . ", 冻结金额：" . $frozenMoney);
        return $AgentModel->updateDailiPlayer($my_user, ['pay_back_gold' => $beforeGold, 'forzen_money' => $frozenMoney], 2);
    }

    private function createOrder($order_id, $my_user, $real_name, $gold)
    {
        $db = Yii::$app->db;

        //生成订单
        $data = [
//            'order_id' => $order_id,
//            'player_index' => $my_user,
//            'wx_mp' => '',
//            'wx_openid' => '',
//            'bank_account' => '',
//            'true_name' => $real_name,
//            'pay_money' => $gold,
//            'pay_fee' => '',
//            'pay_status' => 0,
//            'api_code' => '',
//            'api_desc' => '',
//            'create_time' => time(),
//            'update_time' => time(),
//            'remark' => '',
            'ORDER_ID' => $order_id,
            'PLAYER_INDEX' => $my_user,
            'WX_MP' => '',
            'WX_OPENID' => '',
            'BANK_ACCOUNT' => '',
            'TRUE_NAME' => $real_name,
            'PAY_MONEY' => $gold,
            'PAY_FEE' => '',
            'PAY_STATUS' => 0,
            'API_CODE' => '',
            'API_DESC' => '',
            'CREATE_TIME' => time(),
            'UPDATE_TIME' => time(),
            'REMARK' => '',
        ];

        return $db->createCommand()->insert('t_pay_order', $data)->execute();
    }

    /**
     * 获取用户提现订单
     */
    public function actionGetTakeMoneyOrder()
    {
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');
        //0处理中,1成功,2失败,已解冻
        $page = Yii::$app->request->get('page', $this->Default_Page);
        $limit = Yii::$app->request->get('limit', $this->Default_Limit);
        $offset = ($page - 1) * $limit;
        $start_time = Yii::$app->request->get('start_time');
        $end_time = Yii::$app->request->get('end_time');
        $where = '';
        if ($start_time) {
            $where .= ' and create_time >= ' . strtotime($start_time);
        }
        if ($end_time) {
            $where .= ' and create_time < ' . (strtotime($end_time) + 86400);
        }
        $orders = (new Query())
            ->select('*')
            ->from('t_pay_order')
            ->where('player_index = ' . $my_user . $where)
            ->orderBy('create_time desc')
            ->offset($offset)
            ->limit($limit)
            ->all();
        $count = (new Query())
            ->select('*')
            ->from('t_pay_order')
            ->where('player_index = ' . $my_user . $where)
            ->count();

        $orders = array_map(function ($data) {
            $data['CREATE_TIME'] = date('Y-m-d H:i:s', $data['CREATE_TIME']);
            $data['PAY_MONEY'] = intval($data['PAY_MONEY'] / 1.1);
            return $data;
        }, $orders);
        return $this->writeLayui(0, 'ok', $count, $orders ? $orders : []);
    }

    /**
     * 所选日期提现
     */
    public function actionGetTakeMoneyTimeOrder()
    {
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');//$_SESSION['user_id'];
        //0冻结,1成功
        $start_time = Yii::$app->request->get('start_time');
        $end_time = Yii::$app->request->get('end_time');
        $where = '';
        if ($start_time) {
            $where .= ' and create_time >= ' . strtotime($start_time);
        }
        if ($end_time) {
            $where .= ' and create_time < ' . (strtotime($end_time) + 86400);
        }
        $orders = (new Query())
            ->select('sum(pay_money) as sum')
            ->from('t_pay_order')
            ->where('player_index = ' . $my_user . $where)
            ->one();
        return $this->writeResult(Code::CODE_OK, '', isset($orders['sum']) ? round($orders['sum'] / 100, 2) : 0);
    }

    /**
     * 我的代理所选日期总收益
     * 下两级对当前用户的返利
     */
    public function actionDailiMoneyInTime()
    {
        $request = $this->checkRequestWay(0);
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');

        if (!isset($request['start_time']) || !isset($request['end_time'])) {
            $this->writeResult(Code::PARAM_ERROR, '参数错误！');
        }

        $common = new Common();
        $date = $common->disposeTemporalInterval($request['start_time'], $request['end_time']);

        $startTime = $date['startTime'];
        $endTime = $date['endTime'];
        if ($startTime == $endTime) {
            $dates = array($startTime);
        } else {
            $dates = $common->Date_segmentation($startTime, $endTime)['days_list'];
        }

        //获取用户代理
        $agentList = $this->getUnderPlayerList($my_user, 1);
        if (!$agentList) {//没有下级
            return $this->writeLayui(0, 'ok', 0, []);
        }

        //查询玩家信息
        $underConsume = 0;//业绩
        foreach ($agentList as $k => $v) {
            foreach ($dates as $d => $val) {
                $underConsume += $this->getPlayerTodayAchievements($v, 1, $val);//业绩
            }
        }

        return $this->writeResult(Code::CODE_OK, '', Common::disposeStr($underConsume/self::REBATE_RATIO));
    }

    /**
     * 我的玩家
     * 所选日期总收益
     */
    public function actionMoneyInTime()
    {
        $my_user = $this->session->get('user_id');
        $request = $this->checkRequestWay(0);
        $common = new Common();
        $date = $common->disposeTemporalInterval($request['start_time'], $request['end_time']);

        if ($date['startTime'] == $date['endTime']) {
            $dates = array($date['endTime']);
        } else {
            $dates = $common->Date_segmentation($date['startTime'], $date['endTime'])['days_list'];
        }

        //获取用户所有直属下级（包括代理）
        $listAgent = $this->getIdListByPlayerId($my_user, 2);

        if (!$listAgent) {//没有下级
            return $this->writeLayui(0, 'ok', 0, []);
        }

        $consume = 0;
        foreach ($listAgent as $k => $v) {
            foreach ($dates as $d => $val) {
                $consume += $this->getConsumeByPlayerId($v, 2, $val);
            }
        }
        return $this->writeResult(Code::CODE_OK, '', round($consume / self::REBATE_RATIO,2));
    }

    /**
     * 获取下级玩家信息.
     * 当前用户的所有下级信息、不区分代理和玩家
     */
    public function actionMemberList()
    {
        $request = $this->checkRequestWay(0);
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');
        $page = $request['page'];
        $limit = $request['limit'];
        if($page == 1){
            $pageStart = 0;
        }else{
            $pageStart = ($page - 1)*$limit;
        }

        if (!isset($request['start_time']) || !isset($request['end_time'])) {
            $this->writeResult(Code::PARAM_ERROR, '参数错误！');
        }

        $common = new Common();
        $date = $common->disposeTemporalInterval($request['start_time'], $request['end_time']);

        $startTime = $date['startTime'];
        $endTime = $date['endTime'];
        if ($startTime == $endTime) {
            $dates = array($startTime);
        } else {
            $dates = $common->Date_segmentation($startTime, $endTime)['days_list'];
        }

        //获取用户所有直属下级（包括代理）
        $listAgentAll = $this->getIdListByPlayerId($my_user, 2);
        $count = count($listAgentAll);
        $listAgent = array_slice($listAgentAll,$pageStart,$limit);

        if (!$listAgent) {//没有下级
            return $this->writeLayui(0, 'ok', 0, []);
        }
        Yii::info('redis中关系列表' . json_encode($listAgent));

        $lobbyModel = new LobbyPlayer();
        $con = 'u_id in (' . implode(',', $listAgent) . ')';
        $user_list = $lobbyModel->getPlayerInfo($con);

        Yii::info('查询数据库中玩家' . json_encode($user_list));

        $user_ids = $user_list ? array_column($user_list, 'u_id') : [];

        if (!$user_list) {
            $diff = $listAgent;
        } else {
            $diff = array_diff($listAgent, $user_ids);
        }
        Yii::info('存在差异的id' . json_encode($diff));
        if ($diff) {
            Yii::info('存在未登录的用户');
            foreach ($diff as $val) {
                $info = $this->getNoLoginInfo($val);
                array_push($user_list, $info['wxinfo']);
            }
        }

        if (!$user_list) {
            return $this->writeLayui(0, 'ok', 0, []);
        }

        foreach ($user_list as $k => $v) {
            if (isset($v['no_login']) && $v['no_login'] == 1) {
                $user_list[$k]['weixin_nickname'] = isset($v['nickname']) ? $v['nickname'] : '';
                $user_list[$k]['consume'] = 0;
                $user_list[$k]['gold_bar'] = 0;
                $user_list[$k]['last_login_time'] = '未登录';
            } else {
//算返利
//                $where = [];
//                $where[] = 'parent_id = ' . $my_user;
//                $where[] = "rebate_week >='" . $startTime . "' and rebate_week <= '" . $endTime . "'";
//                $where[] = 'player_id = ' . $v['u_id'];
//                $where = implode(" and ", $where);
//                $rebate = (new Query())
//                    ->select('sum(rebate) as rebate')
//                    ->from('log_rebate')
//                    ->where($where)
//                    ->one();

//业绩
                $consume = 0;
                foreach ($dates as $d => $val) {
                    $consume += $this->getConsumeByPlayerId($v['u_id'], 2, $val);
                }
                $user_list[$k]['consume'] = round(($consume / self::REBATE_RATIO), 2);
            }
        }

        //TODO::未登录用户在登录中没有信息，只在绑定关系表中有
        return $this->writeLayui(0, 'ok', $count, $user_list ? $user_list : []);
    }

    /**
     * 玩家列表只包含玩家
     */
    public function actionPlayerList()
    {
        $my_user = $this->session->get('user_id');
        $request = Yii::$app->request->post();
        $page = $request['page'];
        $limit = $request['limit'];
        if($page == 1){
            $pageStart = 0;
        }else{
            $pageStart = ($page-1)*$limit;
        }
        $count = DailiCalc::getDailiInfo($my_user)['allDirectPlayer'];
        $count = DailiCalc::getDailiInfo($my_user)['allDirectPlayer'];
        $playerListAll = DailiCalc::getAgentList($my_user,'allDirectPlayer');
        $playerList = array_slice($playerListAll,$pageStart,$limit);

        $lobbyModel = new LobbyPlayer();
        $data = [];
        foreach ($playerList as $key => $val) {
            $data[$key]['playerId'] = $val;
            $playerInfo = $lobbyModel->getPlayer($val, 'weixin_nickname,u_id,last_login_time');
            if ($playerInfo){
                $data[$key]['nickname'] = $playerInfo['weixin_nickname'];
                $data[$key]['last_login_time'] = $playerInfo['last_login_time'];
            }else{
                $unionid = Yii::$app->redis_2->hget('reserved_key_id', $val);
                $info = Yii::$app->redis->hget('share_scan_info', $unionid);
                $info = json_decode($info);
                if(isset($info->wxinfo->nickname)){
                    $data[$key]['nickname'] = $info->wxinfo->nickname;
                }else{
                    $data[$key]['nickname'] = '未登录';
                }
            }

            $consume = $this->getConsumeByPlayerId($val);
            $data[$key]['consume'] = Common::disposeStr($consume / self::REBATE_RATIO);
        }
        return $this->writeLayui(0, 'ok', $count, $data ? $data : []);
    }


    /**
     * 下级代理信息
     */
    public function actionMemberDailiList()
    {
        $request = $this->checkRequestWay(0);
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');
        $page = $request['page'];
        $limit = $request['limit'];
        if($page == 1){
            $pageStart = 0;
        }else{
            $pageStart = ($page - 1)*$limit;
        }

        if (!isset($request['start_time']) || !isset($request['end_time'])) {
            $this->writeResult(Code::PARAM_ERROR, '参数错误！');
        }

        $common = new Common();
        $date = $common->disposeTemporalInterval($request['start_time'], $request['end_time']);

        $startTime = $date['startTime'];
        $endTime = $date['endTime'];
        if ($startTime == $endTime) {
            $dates = array($startTime);
        } else {
            $dates = $common->Date_segmentation($startTime, $endTime)['days_list'];
        }

        //获取用户代理
        $agentListAll = $this->getUnderPlayerList($my_user, 1);
        $count = count($agentListAll);
        $agentList = array_slice($agentListAll,$pageStart,$limit);

        if (!$agentList) {//没有下级
            return $this->writeLayui(0, 'ok', 0, []);
        }
        Yii::info("代理列表：" . json_encode($agentList));

        //查询玩家信息
        $db = Yii::$app->db;
        $data = array();
        foreach ($agentList as $k => $v) {
            $where = [];
            $where[] = 'parent_id = ' . $my_user;
            $where[] = "rebate_week >='" . $startTime . "' and rebate_week <= '" . $endTime . "'";
            $where[] = 'type = 2';
            $where[] = 'player_id = ' . $v;
            $where = implode(" and ", $where);
//返利
//            $rebate = (new Query())//返利
//                ->select('sum(rebate) as rebate')
//                ->from('log_rebate')
//                ->where($where)
//                ->one();

//业绩
            $underConsume = 0;
            foreach ($dates as $d => $val) {
                $underConsume += $this->getPlayerTodayAchievements($v, 1, $val);//业绩
            }
            $data[$k]['player_id'] = $v;
            $data[$k]['consume'] = Common::disposeStr($underConsume/self::REBATE_RATIO) ?: 0;
            $dailiName = $db->createCommand("SELECT weixin_nickname FROM login_db.t_lobby_player WHERE u_id = '{$v}'")->queryScalar() ?: "";
            $data[$k]['nickname'] = $dailiName;
        }

        $this->writeLayui(0, 'ok', $count, $data ? $data : []);

    }

    /**
     * 代理信息
     */
    public function actionMyInfo()
    {
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');

        $dailiModel = new DailiPlayer();
        $user = $dailiModel->getDataByCon(['player_id' => $my_user], 'player_id,name,pay_back_gold,all_pay_back_gold,true_name', 2);
        if(!$user){
            return $this->writeResult(Code::CODE_ERROR,'该用户不存在');
        }
        //接口返回数据
        $data = $this->getDailiInfoInterface($my_user);

        //今日业绩
        $user['todayConsume'] = Common::disposeStr($this->getPlayerTodayAchievements($my_user, 1)/self::REBATE_RATIO) ?: 0;

        //上周业绩
        $start_time = strtotime(date('Y-m-d')) - 86400 * 7;
        $week_suffix = date('Ymd',strtotime(date('Y-m-d', $start_time)) - date('w',strtotime(date('Y-m-d', $start_time - 86400))) * 86400);

        $user['lastWeekConsume'] = Common::disposeStr($this->getPlayerTodayAchievements($my_user, 2, $week_suffix)/self::REBATE_RATIO) ?:0;

        //本周业绩
        $user['weekConsume'] = Common::disposeStr($this->getPlayerTodayAchievements($my_user, 2)/self::REBATE_RATIO);

        //今日新增玩家
        $user['today_under_user'] = $data['nowUnderPlayer'];

        //今日新增代理
        $user['today_under_agent'] = $data['nowUnderDaili'];

        //可提现金额
        $proportion = Yii::$app->params['gold_withdraw_deposit'];
        $user['pay_back_gold'] = Common::disposeStr($user['pay_back_gold'] / self::REBATE_RATIO);

        //历史收益
        $user['all_pay_back_gold'] = Common::disposeStr($user['all_pay_back_gold'] / self::REBATE_RATIO);

        $user['rebateSwitch'] = 0;
        if(Yii::$app->params['wechat_web_rebate_switch']){
            $rebateBlackList = Yii::$app->params['wechat_web_rebate_back_list'];
            if(in_array($my_user,$rebateBlackList)){
                $user['rebateSwitch'] = 1;
            }
        }

        return $this->writeLayui(0, 'ok', 1, $user ? $user : []);
    }

    public function actionDirect()
    {
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');

        $dailiModel = new DailiPlayer();
        $user = $dailiModel->getDataByCon(['player_id' => $my_user], 'player_id,name,pay_back_gold,all_pay_back_gold,true_name', 2);

        //接口返回数据
        $data = $this->getDailiInfoInterface($my_user);

        //今日业绩
        $user['todayConsume'] = Common::disposeStr($this->getPlayerTodayAchievements($my_user, 1)/self::REBATE_RATIO);

        //今日直属新增玩家
        $user['today_direct_user'] = $data['newDirectPlayer'];

        //今日直属新增代理
        $user['today_direct_agent'] = $data['newDirectDaili'];

        //可提现金额
        $proportion = Yii::$app->params['gold_withdraw_deposit'];
        $user['pay_back_gold'] = Common::disposeStr($user['pay_back_gold'] / self::REBATE_RATIO);

        //历史收益
        $user['all_pay_back_gold'] = Common::disposeStr($user['all_pay_back_gold'] / self::REBATE_RATIO);

        return $this->writeLayui(0, 'ok', 1, $user ? $user : []);

    }

    /**
     * 业绩信息
     *
     */
    public function actionResults()
    {
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');

        $dailiModel = new DailiPlayer();
        $user = $dailiModel->getDataByCon(['player_id' => $my_user], 'player_id,name,pay_back_gold,all_pay_back_gold', 2);

        //今日业绩
        $user['todayConsume'] = Common::disposeStr($this->getPlayerTodayAchievements($my_user, 1)/self::REBATE_RATIO);
        Yii::info("今日业绩：" . $user['todayConsume']);

        //本周业绩
        $user['weekConsume'] = Common::disposeStr($this->getPlayerTodayAchievements($my_user, 2)/self::REBATE_RATIO);

        $agentInfo = $this->getDailiInfoInterface($my_user);

        //渠道代理（伞下代理数量）
        $user['channelAgent'] = $agentInfo['allUnderDaili'];

        //渠道玩家（伞下玩家数量）
        $user['channelPlayer'] = $agentInfo['allUnderPlayer'];

        //上周收益
        $logRebateModel = new LogRebate();
        $where['parent_id'] = $my_user;
        $where['player_id'] = 0;
        $where['type'] = 1;
        $where['rebate_week'] = $logRebateModel->getLastParamValue('rebate_week');//直属返利
        $rebateFirst = $logRebateModel->getData($where, "sum(rebate) as rebate", 3);

        $where['type'] = 2;
        $rebateUnder = $logRebateModel->getData($where, "sum(rebate) as rebate", 3);//伞下返利

        $user['weekPayBackGold'] = Common::disposeStr(($rebateFirst + $rebateUnder) / self::REBATE_RATIO);
        $user['all_pay_back_gold'] = Common::disposeStr($user['all_pay_back_gold'] / self::REBATE_RATIO);

        $user['rebateSwitch'] = 0;
        if(Yii::$app->params['wechat_web_rebate_switch']){
            $rebateBlackList = Yii::$app->params['wechat_web_rebate_back_list'];
            if(in_array($my_user,$rebateBlackList)){
                $user['rebateSwitch'] = 1;
            }
        }

        return $this->writeLayui(0, 'ok', 1, $user ? $user : []);
    }

    /**
     * 我的基本信息
     */
    public function actionMyBaseInfo()
    {
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');
        //$my_user = 30011607;//$_SESSION['user_id'];
        $user = (new Query())
            ->select('*')
            ->from('t_daili_player')
            ->where('player_id = ' . $my_user)
            ->one();

        $allUser = (new Query())
            ->select('player_id')
            ->from('t_player_member')
            ->where('parent_id = ' . $my_user)
            ->all();

        if ($allUser) {
            $num = $this->playerAndMemberNums();
            //下级玩家
            $user['player_count'] = $num['player_num'];
            $user['daili_count'] = $num['daili_num'];
        } else {
            $user['player_count'] = 0;
            $user['daili_count'] = 0;

        }

        return $this->writeResult(Code::CODE_OK, '', $user);
    }

    /**
     * 上周收入排行榜
     */
    public function actionYesterdayIncomeList()
    {
        $list = Yii::$app->redis->hgetall(Yii::$app->params['redisKeys']['daili_income_rank']);
        $data = [];
        $i = 0;
        foreach ($list as $key => $val) {
            if ($key % 2 == 0) {
                $dailiModel = new DailiPlayer();
                $info = $dailiModel->getById($val, 2);

                $data[$key]['name'] = $info['name'];
                $data[$key]['num'] = $list[$key + 1];
                $data[$key]['rank'] = ++$i;
            }
        }
        return $this->writeLayui(0, 'ok', count($data), $data);
    }

    /**
     * 我的上周收入
     */
    public function actionMyYesterdayIncome()
    {
        $my_user = $this->session->get('user_id');

        $my_income = 0;
        return $this->writeLayui(0, 'ok', 1, $my_income);
    }

    /**
     * 根据验证码修改代理绑定手机号
     *
     */
    public function actionVerifyCode()
    {
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');
        $request = Yii::$app->request->post();
        $phone = $request['phone'];
        $code = $request['code'];

        $redis_code = Yii::$app->redis_3->get('daili_phone_' . $request['phone']);

        if ($phone && $code) {
            if (!$redis_code) {
                return $this->writeResult(Code::CODE_PHONE_VERIFY_CODE_TIME_OUT, '');
            }
            if ($code != $redis_code) {
                return $this->writeResult(Code::CODE_PHONE_VERIFY_CODE_ERROR, '手机验证码错误');
            }

            if ($my_user) {
                $dailiModel = new DailiPlayer();
                $dailiModel->updateDailiPlayer($my_user, ['tel' => $phone], 2);
            }
            return $this->writeResult(Code::CODE_OK);

        } else {
            return $this->writeResult(Code::CODE_PARAMS_ERROR);
        }
    }

    /**
     * 修改代理真实姓名
     */
    public function actionUpdateRealName()
    {
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');
        $name = Yii::$app->request->post('real_name');
        if ($name) {
            $dailiModel = new DailiPlayer();
            $result = $dailiModel->updateDailiPlayer($my_user, ['true_name' => $name], 2);
            if ($result) {
                return $this->writeResult(Code::CODE_OK);
            } else {
                return $this->writeResult(Code::CODE_ERROR);
            }
        } else {
            return $this->writeResult(Code::CODE_PARAMS_ERROR);
        }

    }

    /**
     * 开通代理
     */
    public function actionOpenDaili()
    {
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');
        //获取参数
        $user_id = Yii::$app->request->post('user_id');
        if (!$user_id) {
            return $this->writeResult(Code::CODE_PARAMS_ERROR);
        }
        $db = Yii::$app->db;
        $addUser = (new Query())
            ->select('*')
            ->from('login_db.t_lobby_player')
            ->where('u_id =' . $user_id)
            ->one();
        if (!$addUser) {
            return $this->writeResult(Code::CODE_NOT_LOGIN, '该玩家尚未登录游戏，无法开通代理！');
        }
        $member = $db->createCommand('select id from t_player_member where parent_id = ' . $my_user . ' and player_id = ' . $user_id)->queryScalar();
        if (!$member) {
            return $this->writeResult(Code::CODE_NOT_FOUND_DOWN_USER, '非下级玩家');
        }
        $data = [
            'player_id' => $user_id,
            'name' => $addUser['weixin_nickname'] ? $addUser['weixin_nickname'] : '',
            'tel' => $addUser['phone_number'] ? $addUser['phone_number'] : '',
            'address' => $addUser['province'] . $addUser['city'],
            'sex' => $addUser['sex'] ?: 1,
            //'age' => '',
            'true_name' => '',
            //'type' => $addUser['weixin_nickname']?$addUser['weixin_nickname']:'',
            'daili_level' => 3,
            'parent_index' => $my_user,
            'member_num' => 0,
            'open_num' => 10000,
            'create_time' => date('Y-m-d H:i:s', time()),
            'update_time' => date('Y-m-d H:i:s', time()),
            'bind_time' => date('Y-m-d H:i:s', time()),
            //'follow' => '',
            'status' => 1,//0/取消代理、1/正常代理
            'create_type' => 2,//1/后台开通、2/代理开通
            'last_login_ip' => $addUser['ip'] ? $addUser['ip'] : '',
            'last_login_time' => $addUser['last_login_time'] ? $addUser['last_login_time'] : '',
        ];
        $daili = $db->createCommand("select * from t_daili_player where player_id = {$user_id}")->queryOne();
        $myUserInfo = $db->createCommand("select * from t_daili_player where player_id = {$my_user}")->queryOne();
        if (!$daili) {
//            $tran = Yii::$app->db->beginTransaction();
            $result = $db->createCommand()->insert('t_daili_player', $data)->execute();
            if ($result) {
                //可开通代理数减1
                /*$db->createCommand()->update('t_daili_player', ['open_num' => $myUserInfo['open_num'] - 1], 'player_id = ' . $my_user)->execute();*/
//                $db->createCommand('update t_daili_player set open_num = open_num-1 where player_id = ' . $my_user)->execute();
//                $tran->commit();

                // 开通代理
                DailiCalc::openDaili($user_id);

                //$session = Yii::$app->session;
                $my = json_decode(Yii::$app->redis->hget($this->dailiRelation, $my_user), 1);
                $user = json_decode(Yii::$app->redis->hget($this->dailiRelation, $user_id), 1);
                //开通人
                $data_1 = [
                    "touser" => isset($my['openid']) ? $my['openid'] : '',
                    "template_id" => self::APPLICATION_NOTICE,
                    "url" => $this->redirectUrl,
                    "data" => [
                        "first" => [
                            "value" => "您已为{$daili['name']}ID：{$user_id}成功开通代理资格，他的下级玩家将为您提供服务费的返利！",
                            "color" => "#173177"
                        ],
                        "keyword1" => [
                            "value" => $user_id
                            //"color"=>"#173177"
                        ],
                        "keyword2" => [
                            "value" => date('Y-m-d H:i')
                            //"color"=>"#173177"
                        ],
                        "keyword3" => [
                            "value" => "开通成功"
                            //"color"=>"#173177"
                        ],
                        "remark" => [
                            "value" => "请及时引导他关注\"一拳娱乐\"公众号，在代理后台-代理学堂中学习代理相关知识！",
                            "color" => "#173177"
                        ]
                    ]
                ];
                $res1 = $this->sendTemplateMessage($data_1);
                file_put_contents('/tmp/tmp_list.log', print_r([$data_1, $res1], 1), FILE_APPEND);
                //被开通人
                $data_2 = [
                    "touser" => isset($user['openid']) ? $user['openid'] : '',
                    "template_id" => self::BECOME_DAILI,
                    "url" => $this->redirectUrl,
                    "data" => [
                        "first" => [
                            "value" => "恭喜您成为一拳娱乐的代理，在代理后台-推广二维码里可以找到您的专属推广二维码图片，抓紧分享给您的小伙伴引导他们下载游戏，开启躺着赚钱模式吧！",
                            "color" => "#173177"
                        ],
                        "date" => [
                            "value" => date('Y-m-d H:i')
                            //"color"=>"#173177"
                        ],
                        "expiry" => [
                            "value" => '2099-12-31'
                            //"color"=>"#173177"
                        ],
                        "remark" => [
                            "value" => "小秘诀：在代理后台-代理学堂中可以快速学习代理相关知识，祝早日成为老司机！",
                            "color" => "#173177"
                        ]
                    ]
                ];
                $res2 = $this->sendTemplateMessage($data_2);
                file_put_contents('/tmp/tmp_list.log', print_r([$data_2, $res2], 1), FILE_APPEND);

                Yii::info('开通人：' . json_encode($data_1));
                Yii::info('被开通人：' . json_encode($data_2));

                return $this->writeResult(Code::CODE_OK);
            } else {
//                $tran->rollBack();
                return $this->writeResult(Code::CODE_ERROR);
            }
        } else {
            if ($daili) {//代理已被开通
                return $this->writeResult(Code::CODE_DAILI_EXISTS, '代理已存在');
            } else if ($myUserInfo['open_num'] == 0) {//已打最大开通上限
                return $this->writeResult(Code::CODE_DAILI_OPEN_LIMIT, '已达开通上限');
            }
        }
    }

    /**
     * 取消代理
     */
    public function actionDelDaili()
    {
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');
        $user_id = Yii::$app->request->post('user_id');
        $db = Yii::$app->db;
        if (!$user_id) {
            return $this->writeResult(Code::CODE_PARAMS_ERROR);
        }
        $is_exists = $db->createCommand('select * from t_daili_player where parent_index = ' . $my_user . ' and player_id = ' . $user_id)->queryOne();
        if (!$is_exists) {//没有代理关系
            return $this->writeResult(Code::CODE_DAILI_NOT_EXISTS);
        }

        //被删除代理对上级有返利
        $dailiInfo = (new Query())
            ->select('*')
            ->from('t_income_details')
            ->where('player_id = ' . $user_id)
            ->one();
        if ($dailiInfo) {//有返利
            return $this->writeResult(Code::CODE_DAILI_HAS_MONEY);
        }
        //有下级
        $childInfo = (new Query())
            ->select('*')
            ->from('t_player_member')
            ->where('player_index = ' . $user_id)
            ->one();
        if ($childInfo) {//存在下级代理
            return $this->writeResult(Code::CODE_LOWER_DAILI_EXISTS);
        }
        /*//被删除代理的下级
        $re_1 = $db->createCommand()->update('t_daili_player', ['parent_index' => 999], 'parent_index = ' . $user_id)->execute();
        file_put_contents('/tmp/wx.log',PHP_EOL.'re_1:'.$re_1,FILE_APPEND);
        //删除与下级关系代理关系
        $re_3 = $db->createCommand()->delete('t_player_member', 'player_index = ' . $user_id)->execute();
        file_put_contents('/tmp/wx.log',PHP_EOL.'re_3:'.$re_3,FILE_APPEND);*/
        //删除与上级关系代理关系
        /*$re_2 = $db->createCommand()->delete('t_player_member', 'member_index = ' . $user_id)->execute();
        file_put_contents('/tmp/wx.log',PHP_EOL.'re_2:'.$re_2,FILE_APPEND);*/

        //移除代理
        $tran = $db->beginTransaction();
        $re_4 = $db->createCommand()->delete('t_daili_player', 'player_id = ' . $user_id)->execute();
        //代理数加1
        $re_5 = $db->createCommand('update t_daili_player set open_num = open_num+1 where player_id = ' . $my_user)->execute();
        file_put_contents('/tmp/wx.log', PHP_EOL . 're_4:' . $re_4, FILE_APPEND);

        if ($re_4 && $re_5) {
            $tran->commit();
            return $this->writeResult(Code::CODE_OK);
        } else {
            $tran->rollBack();
            return $this->writeResult(Code::CODE_ERROR);
        }

    }

    /**
     * 代理根据id绑定自己的下级玩家
     */
    public function actionBindMember()
    {

    }

    /**
     * 业绩查询-日查
     *
     */
    public function actionResultsDay()
    {
        $request = $this->checkRequestWay(1);
        $playerId = $this->session->get('user_id');

        if (!isset($request['start_time']) || !isset($request['end_time'])) {
            $this->writeResult(Code::PARAM_ERROR, '参数错误！');
        }

        $common = new Common();
        $date = $common->disposeTemporalInterval($request['start_time'], $request['end_time']);

        $startTime = $date['startTime'];
        $endTime = $date['endTime'];

        if ($startTime != $endTime) {
            $timeDivision = $common->Date_segmentation($startTime, $endTime);
            $resDate = $timeDivision['days_list'];
        } else {
            $resDate[] = $startTime;
        }

        $returnData = array();
        $redisKey = RedisKey::INF_UNDER_DAY_CONSUME;
        $i = 0;
        foreach ($resDate as $key => $val) {
            $dateRedisKey = $redisKey . date('Ymd', strtotime($val));
            $returnData[$i]['date'] = $val;
            $returnData[$i]['consume'] = Common::disposeStr(Yii::$app->redis->hget($dateRedisKey, $playerId) / self::REBATE_RATIO) ?: 0;
            $returnData[$i]['playerId'] = $playerId;
            $i++;
        }

        $this->writeLayui(Code::OK, 'ok', count($returnData), $returnData);

    }

    /**
     * 业绩查询-周查
     *
     */
    public function actionResultsWeek()
    {
        $request = $this->checkRequestWay(1);
        $playerId = $this->session->get('user_id');

        if (!isset($request['start_time']) || !isset($request['end_time'])) {
            $this->writeResult(Code::PARAM_ERROR, '参数错误！');
        }

        if (isset($request['agentId']) && $request['agentId']) {
            $playerId = $request['agentId'];
        }

        $common = new Common();
        $date = $common->disposeTemporalInterval($request['start_time'], $request['end_time']);

        $startTime = $date['startTime'];
        $endTime = $date['endTime'];

        $today = date('Y-m-d');
        $logRebateModel = new LogRebate();
        $where[] = "parent_id = " . $playerId;
        $where[] = 'player_id != 0';
        if (($startTime == $endTime) && ($endTime == $today)) {
            $where[] = "rebate_week = '" . $logRebateModel->getLastParamValue('rebate_week') . "'";
        } else {
            $where[] = "rebate_week >= '" . $startTime . "' and rebate_week <= '" . $endTime . "'";
        }

        $where = implode(" and ", $where);
        $info = $logRebateModel->getDataByGroup($where, "parent_id,rebate_week,sum(rebate) as rebate,sum(consume) as consume", "rebate_week");//直属返利
        foreach ($info as $key => $val) {
            $info[$key]['rebate'] = Common::disposeStr($val['rebate'] / self::REBATE_RATIO);
            $info[$key]['consume'] = Common::disposeStr($val['consume'] / self::REBATE_RATIO);
        }

        $this->writeLayui(Code::OK, 'ok', count($info), $info);
    }
    /* ================================ 公众号向代理付款 ======================================= */

    /**
     * 向代理付款
     *
     * 1491131092
     * wx6ea0277292666e7d
     */
    private function payDaili($openid, $amount, $order_id, $desc = '代理提现')
    {
        //生成订单号
        //$order_id = $order_id;
        $params["mch_appid"] = self::MCH_APPID;  //公众账号appid
        $params["mchid"] = self::MCHID;  //商户号 微信支付平台账号
        $params["nonce_str"] = 'diandongkeji' . mt_rand(100, 999);  //随机字符串
        $params["partner_trade_no"] = $order_id;  //商户订单号
        $params["amount"] = $amount;  //金额
        $params["desc"] = $desc;  //企业付款描述
        $params["openid"] = $openid;  //用户openid
        $params["check_name"] = 'NO_CHECK';  //不检验用户姓名
        $params["spbill_create_ip"] = $_SERVER['SERVER_ADDR'];  //ip
        //生成签名,key为api秘钥
        $str = 'amount=' . $params["amount"] . '&check_name=' . $params["check_name"] . '&desc=' . $params["desc"] . '&mch_appid=' . $params["mch_appid"] . '&mchid=' . $params["mchid"] . '&nonce_str=' . $params["nonce_str"] . '&openid=' . $params["openid"] . '&partner_trade_no=' . $params["partner_trade_no"] . '&spbill_create_ip=' . $params['spbill_create_ip'] . '&key=' . $this->api_key;
        //md5加密 转换成大写
        $sign = strtoupper(md5($str));

        $params["sign"] = $sign;//签名
        file_put_contents('/tmp/wx_pay.log', PHP_EOL . date('Y-m-d H:i:s', time()) . PHP_EOL . "写入提现队列信息:" . print_r($params, 1), FILE_APPEND);
        $re = Yii::$app->redis->lpush(self::PAY_DAILI_MONEY, json_encode($params, JSON_UNESCAPED_UNICODE));
        file_put_contents('/tmp/wx_pay.log', PHP_EOL . date('Y-m-d H:i:s', time()) . PHP_EOL . "写入提现队列结果:{$re}", FILE_APPEND);
        //$xml = $this->arrayToXml($params);
        //return $this->curl_post_ssl($this->companyRechargeUrl, $xml);
    }


    /**
     * 日查详情
     * @params $player_id
     * @params $search_date
     * @params $is_agent
     */
    public function actionDayDetails()
    {
        $request = $this->checkRequestWay(0);
        $agent_id = $request['agent_id'];
        $search_date = $request['search_date'];
        $is_agent = $request['is_agent'];

        if (!$agent_id && !$search_date && !empty($is_agent)) {
            $this->writeResult(Code::CODE_ERROR);
        }

        switch ($is_agent) {
            case 1:
                $data = $this->getDayAgent($agent_id, $search_date);
                break;
            case 0:
                $data = $this->getDayUser($agent_id, $search_date);
                break;
        }

        $this->writeLayui(Code::OK, 'ok', count($data), $data);
    }

    /**
     * 获取代理日详情
     * @params $player_id
     * @params $search_date Y-m-d
     */
    private function getDayAgent($agent_id, $search_date)
    {
        $db = Yii::$app->db;
        $d = date('Ymd', strtotime($search_date));
        $data = array();
        $player_list = Yii::$app->redis->zrangebyscore(RedisKey::INF_AGENT_RELATION, $agent_id, $agent_id);
        $dailiModel = new DailiPlayer();
        foreach ($player_list as $k => $player_id) {
            if ($dailiModel->getById($player_id)) {
                $data[$k] = [
                    'nickname' => $db->createCommand("SELECT weixin_nickname FROM login_db.t_lobby_player WHERE u_id = '{$player_id}'")->queryScalar(),
                    'id' => $player_id,
                    'consume' => round(Yii::$app->redis->hget(RedisKey::INF_UNDER_DAY_CONSUME . $d, $player_id) / self::REBATE_RATIO, 2),
                ];
            }
        }

        return $data;
    }

    /**
     * 获取玩家日详情
     * @params $agent_id
     * @params $search_date Y-m-d
     */
    private function getDayUser($agent_id, $search_date)
    {
        Yii::info('获取玩家日详情');
        $db = Yii::$app->db;
        $d = date('Ymd', strtotime($search_date));
        $data = array();
        $player_list = Yii::$app->redis->zrangebyscore(RedisKey::INF_AGENT_RELATION, $agent_id, $agent_id);
        foreach ($player_list as $k => $player_id) {
            Yii::info($player_id . ";玩家消耗：：" . Yii::$app->redis->hget(RedisKey::INF_DAY_CONSUME . $d, $player_id));
            $data[$k] = [
                'nickname' => $db->createCommand("SELECT weixin_nickname FROM login_db.t_lobby_player WHERE u_id = '{$player_id}'")->queryScalar() ?: '',
                'id' => $player_id,
                'consume' => round(Yii::$app->redis->hget(RedisKey::INF_DAY_CONSUME . $d, $player_id) / self::REBATE_RATIO, 2) ?: 0,
            ];
        }
        return $data;
    }

    /**
     * 周详情
     */
    public function actionWeekDetails()
    {
        $request = $this->checkRequestWay(0);
        $agent_id = $request['agent_id'];
        $search_date = $request['search_date'];
        $is_agent = $request['is_agent'];
        $page = $request['page'];
        $limit = $request['limit'];

        if (!$agent_id && !$search_date && !empty($is_agent)) {
            $this->writeResult(Code::CODE_ERROR);
        }
        $data = array();
        $count = 0;
        switch ($is_agent) {
            case 1:
                $data = $this->getWeekAgent($agent_id, $search_date,$limit,$page);
                $count = $data['count'];
                unset($data['count']);
                break;
            case 2:
                $data = $this->getWeekUser($agent_id, $search_date,$limit,$page);
                $count = $data['count'];
                unset($data['count']);
                break;
        }
        $this->writeLayui(Code::OK, 'ok', $count, $data);
    }

    /**
     * 获取玩家周详情
     * @params $agent_id
     * @params $search_date
     * @params $is_agent
     */
    private function getWeekAgent($agent_id, $search_date,$limit,$page)
    {
        $where[] = 'parent_id = ' . $agent_id;
        $where[] = "rebate_week = '" . $search_date . "'";
        $where[] = 'type = 2';
        $where[] = 'player_id != 0';
        $where = implode(" and ", $where);

        $logRebateModel = new LogRebate();
        $data = (new Query())
            ->select('player_id, consume, rebate')
            ->from("log_rebate")
            ->where($where)
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();

        $count = $logRebateModel->getData($where,"id",5);

        $redisKey = RedisKey::INF_LEVEL.date("Ymd",strtotime($search_date));
        $parentRadio = Yii::$app->redis->hget($redisKey,$agent_id);//每周的返利比例

        $confRebateModel = new ConfRebateRatio();
        if ($data) {
            $db = Yii::$app->db;
            foreach ($data as $k => $v) {
                $data[$k]['consume'] = round($v['consume'] / self::REBATE_RATIO, 2);
                $data[$k]['rebate'] = round($v['rebate'] / self::REBATE_RATIO, 2);
                $nickname='';
                $nickname = $db->createCommand("SELECT weixin_nickname FROM login_db.t_lobby_player WHERE u_id = '{$v['player_id']}'")->queryScalar();
                if(!$nickname){
                    $info = $this->getNoLoginInfo($v['player_id']);
                    if(isset($info['wxinfo']['nickname'])){
                        $nickname = $info['wxinfo']['nickname'];
                    }
                }
                $data[$k]['nicknameId'] = $nickname."(".$v['player_id'].")";

                $radio = Yii::$app->redis->hget($redisKey,$v['player_id']) ?: 0.3;
                $level = $confRebateModel->getLevel($radio) ?: 1;
                $data[$k]['level'] = "V" . $level."(".($radio*100)."%".")";//下级等级
                $data[$k]['gap'] = (($parentRadio-$radio) * 100)."%";//级差
            }
        }

        $data['count'] = $count;

        return $data;
    }

    /**
     * 获取玩家周详情
     * @params $agent_id
     * @params $search_date
     * @params $is_agent
     */
    private function getWeekUser($agent_id, $search_date,$limit,$page)
    {
        $where[] = 'parent_id = ' . $agent_id;
        $where[] = "rebate_week = '" . $search_date . "'";
        $where[] = 'type = 1';
        $where[] = 'player_id != 0';
        $where = implode(" and ", $where);

        $logRebateModel = new LogRebate();
        $db = Yii::$app->db;
        $data = $logRebateModel->getData($where,'player_id, consume, rebate',4,$limit,$page);

        $count = $logRebateModel->getData($where,"id",5);

        $confRebateModel = new ConfRebateRatio();
        if ($data) {
            foreach ($data as $k => $v) {
                $data[$k]['consume'] = round($v['consume'] / self::REBATE_RATIO, 2);
                $data[$k]['rebate'] = round($v['rebate'] / self::REBATE_RATIO, 2);
                $nickname = $db->createCommand("SELECT weixin_nickname FROM login_db.t_lobby_player WHERE u_id = '{$v['player_id']}'")->queryScalar();
                if(!$nickname){
                    $info = $this->getNoLoginInfo($v['player_id']);
                    if(isset($info['wxinfo']['nickname'])){
                        $nickname = $info['wxinfo']['nickname'];
                    }
                }
                $data[$k]['nicknameId'] = $nickname."(".$v['player_id'].")";
                $redio = $db->createCommand("SELECT ratio FROM log_rebate WHERE parent_id = {$agent_id} AND player_id != 0 AND type= 1 AND rebate_week='".$search_date."'")->queryScalar() ?: 0;
                $data[$k]['radio'] = ($redio*100)."%";
            }
        }

        $data['count'] = $count;

        return $data;
    }

    /**
     * 推广二维码
     */
    public function actionTwodimensioncode()
    {
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');
        //$my_user = 30826102;
        $dst = '/web/wx/qr/';
        $dst_ = '/wx/qr/';
        $file = Yii::$app->basePath . $dst . 'qr_' . $my_user . '.png';//最终生成图片
        $QR = Yii::$app->basePath . '/web/static/mobile/agent/images/3.png';//二维码底图
        $qrname = Yii::$app->basePath . $dst . $my_user . '.png';//二维码图
        if (!is_dir(Yii::$app->basePath . $dst)) {
            mkdir(Yii::$app->basePath . $dst, 0777, true);
        }
        //跳转绑定URL
        $url = $this->rootUrl . '/api/share/index?uid=' . $my_user;
        Tool::getQrImg(2, $url, $file, $QR, $qrname);

        return $this->render('twoDimensionCode', ['img' => 'http://' . $_SERVER['HTTP_HOST'] . $dst_ . 'qr_' . $my_user . '.png?' . time()]);
    }

    /**
     * 直兑检测用户
     *
     */
    public function actionCheckPlayer()
    {
        $request = $this->checkRequestWay(0);
        if (!isset($request['orderId']) || !$request['orderId']) {
            echo "<script>alert('订单不存在');</script>";
            exit;
        }

        return $this->render('checkPlayer', ['orderId' => $request['orderId']]);
    }

    /**
     * 开通代理
     */
    public function actionCreateagent()
    {
        $my_user = $this->session->get('user_id');
        $dailiNum = $this->getDailiInfoInterface($my_user)['allDirectDaili'];

        return $this->render('createAgent', ['num' => $dailiNum]);
    }


    /**
     * 周收益详情
     */
    public function actionWeekRebateDetail()
    {
        $request = $this->checkRequestWay(0);
        $playerId = $this->session->get('user_id');
        if(!$playerId){
            return '';
        }
        if(!isset($request['date'])){
            $date = date("Y-m-d");
        }else{
            $date = $request['date'];
        }

        $start_time = strtotime($date);
        $date = date('Y-m-d',strtotime(date('Y-m-d', $start_time)) - date('w',strtotime(date('Y-m-d', $start_time - 86400))) * 86400);

        $redisKey = RedisKey::INF_LEVEL.date("Ymd",strtotime($date));
        $radio = Yii::$app->redis->hget($redisKey,$playerId);//每周的返利比例
        $conRebateModel = new ConfRebateRatio();
        $level = $conRebateModel->getLevel($radio);//返利比例对应的等级

        $logRebateModel = new LogRebate();
        $condition[] = 'parent_id ='.$playerId;
        $condition[] = 'player_id != 0';
        $condition[] = "rebate_week='".$date."'";
        $condition = implode(' and ',$condition);
        $allData = $logRebateModel->getData($condition,'sum(rebate) as rebate,sum(consume) as consume',2);
        $rebate = Common::disposeStr($allData['rebate']/self::REBATE_RATIO);
        $consume = Common::disposeStr($allData['consume']/self::REBATE_RATIO);

        $where[] = 'parent_id='.$playerId;
        $where[] = 'type = 1';
        $where[] = "rebate_week='".$date."'";
        $where[] = 'player_id != 0';
        $where = implode(' and ',$where);
        $playerData = $logRebateModel->getData($where,'sum(rebate) as rebate,sum(consume) as consume',2);
        $playerData['rebate'] = Common::disposeStr($playerData['rebate']/self::REBATE_RATIO);
        $playerData['consume'] = Common::disposeStr($playerData['consume']/self::REBATE_RATIO);

        $con[] = 'parent_id='.$playerId;
        $con[] = 'type = 2';
        $con[] = "rebate_week='".$date."'";
        $con[] = 'player_id != 0';
        $con = implode(' and ',$con);
        $agentData = $logRebateModel->getData($con,'sum(rebate) as rebate,sum(consume) as consume',2);
        $agentData['rebate'] = Common::disposeStr($agentData['rebate']/self::REBATE_RATIO);
        $agentData['consume'] = Common::disposeStr($agentData['consume']/self::REBATE_RATIO);

        $data['radio'] = ($radio*100).'%';
        $data['level'] = $level;
        $data['rebate'] = $rebate;
        $data['consume'] = $consume;
        $data['playerData'] = $playerData;
        $data['agentData'] = $agentData;

        return $this->render('weekRebateDetail',['data'=>$data,'playerId'=>$playerId,'date'=>$date]);
    }
    /** *********************************************跳转页面 无逻辑代码********************************************** */

    /**
     * 代理信息
     */
    public function actionAgentinfo()
    {
        return $this->render('agentInfo');
    }

    /**
     * 直属信息
     */
    public function actionMyDirect()
    {
        return $this->render('myDirect');
    }

    public function actionResultsQuery()
    {
        $session = Yii::$app->session;
        $my_user = $session->get('user_id');

        $rebateSwitch=0;
        if(Yii::$app->params['wechat_web_rebate_switch']){
            $rebateBlackList = Yii::$app->params['wechat_web_rebate_back_list'];
            if(in_array($my_user,$rebateBlackList)){
                $rebateSwitch = 1;
            }
        }

        return $this->render('resultsQuery',['rebateSwitch'=>$rebateSwitch]);
    }

    /**
     * 提现
     */
    public function actionWithdrawcash()
    {
        return $this->render('withdrawCash');
    }

    /**
     * 我的玩家
     */
    public function actionMyplayer()
    {
        return $this->render('myPlayer');
    }

    /**
     * 我的代理
     */
    public function actionMyagent()
    {
        return $this->render('myAgent');
    }


    /**
     * 上周收入排行榜
     */
    public function actionYesterdayincome()
    {
        return $this->render('yesterdayIncome');
    }

    public function actionResultsInfo()
    {
        return $this->render('resultsInfo');
    }

    /**
     * 我的信息
     */
    public function actionBaseinfo()
    {
        return $this->render('baseInfo');
    }

    /**
     * 代理学堂
     */
    public function actionAgencyschool()
    {
        return $this->render('agencySchool');
    }

    /**
     * 代理学堂-p1
     */
    public function actionIntroduce1()
    {
        return $this->render('introduce1');
    }

    /**
     * 代理学堂-p2
     */
    public function actionIntroduce2()
    {
        return $this->render('introduce2');
    }

    /**
     * 代理学堂-p3
     */
    public function actionIntroduce3()
    {
        return $this->render('introduce3');
    }

    /**
     * 代理学堂-p4
     */
    public function actionIntroduce4()
    {
        return $this->render('introduce4');
    }

    /**
     * 代理学堂-p5
     */
    public function actionIntroduce5()
    {
        return $this->render('introduce5');
    }

    /**
     * 代理学堂-p6
     */
    public function actionIntroduce6()
    {
        return $this->render('introduce6');
    }

    /**
     * 渠道合伙人
     */
    public function actionChannelIndex()
    {
        return $this->render('channelPartner');
    }

    /**
     * 渠道合伙人信息
     */
    public function actionChannelInfo()
    {
        return $this->render('channelInfo');
    }

    /**
     * 渠道合伙人开通代理
     */
    public function actionChannelCreateAgent()
    {
        return $this->render('channelCreateAgent');
    }

}

