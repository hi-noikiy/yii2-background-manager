<?php
/**
 * User: SeaReef
 * Date: 2018/6/11 15:18
 */
namespace app\controllers\api;

use app\controllers\BaseController;
use app\models\LobbyPlayer;
use app\models\Player;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\Url;
use app\common\helpers\Sms;
use yii\base\Curl;
use app\models\GoldOrder;
use app\models\RechargeConf;


/**
 * 平台控制显示充值方式的显示按钮
 */
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

    /**
     * 登录之前客户端获取数据
     * @params mac 当前用户机器码
     * @return down/0维护中、1正常游戏、login/0禁止登录、1可以登录、
     */
    public function actionLoginCheck($mac = 1)
    {
        $data = [
            'down' => 0,
            'down_info' => '尊敬的一拳娱乐用户，本平台正在拼命维护优化中，请耐心等待，给您造成的不便敬请谅解，如需帮助，请联系客服1:PUKEQIPAI ,客服2：PKQP03',
            'login' => 0,
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
            $this->writeResult(self::CODE_ERROR);
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
     * 总纲
     * #登录之前获取数据
     * 控制停服开关、@params、无开关
     * 登录mac、ip限制：@params mac、
     * 获取轮播图数据
     *
     *
     * #####登录之后获取的数据、要求能够平台控制实时更新#####
     *
     * 获取二维码信息
     * 控制充值方式：@params、返回可显示的名称和对应拉起链接
     * 商品列表：@params uid、gid、rid
     * 充值限额、排行榜可见度、@params uid、gid、rid、type
     * 分享地址、@params uid、gid、
     * ip地址、@params 无参数
     */


    /**
     * 手机号绑定获取的元宝数
     */
    const BIND_PHONE = 50;


    /**
     * @params uid 用户id
     * @params gid 子游戏id、大厅现在都是1114112
     * @params rid 商品id
     * @params limit 充值限额的位置、1、游戏内限额、2、游戏外限额
     * @params type 默认全部数据、可以根据不同的功能拉取对应的数据
     */
    public function actionPull()
    {
        $data = [];
        $request = Yii::$app->request;
        $type = $request->get('pull_type', 'all');
        $gid = $request->get('gid', '1114112');
        $uid = $request->get('uid');

        $goods_type = $request->get('goods_type', 3);

        switch ($type) {
//            获取轮播图信息
            case 'lunbo':
                $data['lunbo'] = $this->getLunbo();
                break;

//            获取商品列表
            case 'get_goods':
                $data['goods_list'] = $this->getGoodsList($gid, $goods_type);
                $data['vip_list'] = $this->getVIPList();
                break;

//            获取充值方式和对应的拉起链接
            case 'get_payment':
                $rid = $request->get('rid');
                $pay_type = $request->get('pay_type');
                $terminal = $request->get('terminal');
                $data['payment'] = $this->getPayMent($uid, $gid, $rid, $pay_type, $terminal);
                break;

//            获取场内场外充值限额
            case 'get_pay_limit':
                $rid = $request->get('rid');
                $type = $request->get('type');
                $start_time = date('Y-m-d') . ' 00:00:00';
                $end_time = date('Y-m-d') . ' 23:59:59';
                $data['pay_limit'] = $this->getPayLimit($uid, $gid, $rid, $type);
                break;


            case 'get_is_novice':
                $ts = $request->get('ts');
                $sign = $request->get('sign');
                $data['is_novice'] = $this->getIsNovice($uid, $ts, $sign);
                break;

//            游戏内分享
            case 'get_share':
                $data['share'] = $this->getShare($uid, $gid);
                break;

//            获取ip位置
            case 'get_location':
                $ip = Yii::$app->request->userIP;
                $data['location'] = $this->getLocation($ip);
                break;

//            用户认证获取验证码
            case 'player_auth':
                $phone = $request->get('phone');
                $redis = Yii::$app->redis;
                $code = Sms::randNumber(4);
                $time = $redis->ttl('player_auth:' . $uid);
                if ($time > 0) {
                    $data['time'] = $time;
                    $data['status'] = 0;
                } else {
                    $redis->set('player_auth:' . $uid, $code);
                    $redis->expire('player_auth:' . $uid, 60);
                    $result = Sms::send($phone, '验证码：' . $code.'，60秒之内有效，期间无需重复获取【点动科技】');
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



//            手机号绑定
            case 'bind_phone':
                $data['bind_phone'] = $this->getIsBindPhone($uid);
                break;

            case 'get_activity':
                $data['activity'] = $this->actionActivity($uid);
                break;

//            获取所有的静态数据
            case 'all':
//                添加一个在login_db.t_lobby_player中没有的用户、插入到t_player用户表中的功能
                $info = $this->checkPlayerInfo($uid);

                $data['lunbo'] = $this->getLunbo();
                $data['goods_list'] = $this->getGoodsList($gid, $goods_type);
                $data['share'] = $this->getShare($uid, $gid);
                $data['bind_phone'] = $this->getIsBindPhone($uid);
                $data['exchange'] = $this->isBind($uid);

                $ip = Yii::$app->request->userIP;
                $data['location'] = $this->getLocation($ip);

                break;
        }

        $this->writeJson($data);
    }

    /**
     * 查询玩家信息、没有的记录
     */
    public function checkPlayerInfo($uid)
    {
        $player_info = LobbyPlayer::findOne(['u_id' => $uid]);
        if ($player_info) {
            $db = Yii::$app->db;
            $ip = Yii::$app->request->getUserIP();
            $db->createCommand("INSERT INTO t_player VALUES(NULL, '{$player_info->u_id}','','{$player_info->weixin_nickname}', '{$player_info->machine_code}', '{$player_info->head_img}', '{$player_info->phone_number}', '{$player_info->reg_time}', '{$player_info->last_login_time}', '{$ip}', '{$player_info->sex}', '{$player_info->country}', '{$player_info->province}', 1, '') ON DUPLICATE KEY UPDATE last_login_time = '{$player_info->last_login_time}'")->execute();
        }
    }

    /**
     * 获取轮播图
     */
    public function getLunbo()
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
            'img_url' => $img,
            'set' => [
                'play_interval' => $play_interval,
            ]
        ];
    }

    /**
     * 获取商城列表
     */
    public function getGoodsList($gid, $type = 3)
    {
        $data['referrer_id'] = 0;//Api::QueryRefererId($uid);
        $data['keyword'] = '（';
        $list = (new Query())
            ->select('*')
            ->from('t_recharge_conf')
            ->where(['f_game' => $gid, 'f_award' => 0])
            ->all();

        $data['list'] = [];
        foreach ($list as $key => $v) {
            if ($v['f_type'] == $type) {
                $data['list'][] = ['id' => $v['f_id'], 'num' => $v['f_num'], 'desc' => $v['f_desc'], 'price' => $v['f_price']];
            }
        }
        $data['vip_list'] = $this->getVIPList();//vip充值列表

        return $data;
    }

    /**
     * 游戏内分享地址
     */
    public function getShare($uid, $gid)
    {
        $user = (new Query())
            ->select('*')
            ->from('login_db.t_lobby_player')
            ->where(['u_id' => $uid])
            ->one();

        $data['share'] = [
            'identity' => 1,
//            'imageurl' => $_SERVER['HTTP_HOST'].Url::to(['share/qrcode','gid'=>$gid,'uid'=>$uid]),
//            'shareurl' => $_SERVER['HTTP_HOST'].Url::to(['share/index','gid'=>$gid,'uid'=>$uid]),
        ];
    }

    /**
     * 获取ip所在位置
     */
    public function getLocation($ip)
    {
//        $location = new Ip2Location();
//        $data['location'] = $location->getLocation($ip);
        $data['location'] = '北京';

        return $data;
    }

    /**
     * 获取二维码地址
     */
    public function getQrcode()
    {

    }

    /*
     * 获取充值方式和对应的充值地址
     *
     * 返回需要开启的充值方式、
     * 对应的链接
     */
    public function getPayMent($uid, $gid, $rid, $pay_type, $terminal)
    {
        /**
        $pay = (new Query())
            ->select('*')
            ->from('t_payment')
            ->where(['status' => 1])
            ->all();

        foreach ($pay as $v) {
            $data[$v['pay_name']] = 'https://' . $_SERVER['SERVER_NAME'] . Url::toRoute(['api/recharge/pay']);
        }
         */

        $pay = (new Query)
            ->select('*')
            ->from('t_pay_url_config')
            ->where(['is_use' => 1])
            ->all();

        foreach ($pay as $v) {
            $data[$v['short_name']] = $v['url'];
        }

        return $data;
    }


    /**
     * 用户的充值限额
     */
    public function getPayLimit($uid, $gid, $rid, $type)
    {
        //$request = Yii::$app->request;
        $player_id = $uid;
        $start_time = date('Y-m-d') . '00:00:00';
        $end_time = date('Y-m-d') . '23:59:59';

        $redis   = Yii::$app->redis;

        //获取游戏内配置的值
        $data['on_game']      = $redis->hget('money_client_config','on_game');              //游戏内额度
        $data['off_game']     = $redis->hget('money_client_config','off_game');             //游戏外额度
        $data['top_num']      = $redis->hget('money_client_config','top_num');              //排行榜
        $data['all_num']      = $redis->hget('money_client_config','all_num');              //无限制额度
        $data['money_switch'] = $redis->hget('money_client_config','money_switch');         //金额开关

        $data['bIsShowRankingList'] = 0;
        $data['all_count']          = 0;
        $data['repay']              = 0;

        //如果没有开启金额限制，直接放行
        if( $data['money_switch'] == 0 ){
            $data['pay'] = 1;
            return $data;
        }
        //开启金额限制后的处理
        if($rid && $player_id && $type){
            //获取用户今日已充值金额
            $count = GoldOrder::find()->where(['f_uid'=>$player_id,'f_status'=>1])->andWhere(['between','f_created',$start_time,$end_time])
                ->sum('f_price');
            //获取用户充值总额度
            $allcount = GoldOrder::find()->where(['f_uid'=>$player_id,'f_status'=>1])->sum('f_price');
            $data['all_count'] = intval($allcount);

            //用户是否可以查看排行榜
            $data['bIsShowRankingList'] = ($data['all_count'] >= $data['top_num'])?1:0;
            $pay = $count?$count:0;
            //获取产品的价格
            $rinfo = RechargeConf::find()->where(['f_id'=>$rid])->asArray()->one();

            $data['repay'] = intval($pay);                      //今日已支付价格
            $shop_price    = intval($rinfo['f_price']);         //产品价格

            //如果类型1，限额为游戏外充值额度，如果type=2 ,限额为游戏内充值额度；
            $recharge_num  = ($type == 1)?$data['off_game']:$data['on_game'];
            if($allcount  > $data['all_num']){  //总充值大于总限额 可以充值
                $data['pay'] = 1;
            }else if( ($data['repay'] + $shop_price) < $recharge_num  ){  //今日已充值额度 +产品小于限额 可以充值
                $data['pay'] = 1;
            }else{
                $data['pay'] = 0;
            }
        }else{
            $data['pay'] = 0;
        }
        //file_put_contents('/tmp/userpay.log', print_r($data, 1), FILE_APPEND);
        return $data;
    }

    /**
     * 是否获得首冲礼包
     */
    public function getIsNovice()
    {
        return [
            'code' => 1,
            'goods_id' => '8',
            'num' => 1,
            'price' => 10,
            'type' => 1
        ];
    }

    /**
     * 获取二维码接口
     */
    public function actionShareImage()
    {

    }

    /**
     * 新手礼包接口
     */
    public function actionGetUserNoviceInfo()
    {

    }

    /**
     * 首冲礼包
     */
    public function actionIsNovice()
    {

    }

    /**
     * 活动是否领取
     * @params activity_id 活动ID
     * @params $uid 玩家ID
     */
    public function actionIsReceive($activity_id, $uid)
    {
        $data = (new Query())
            ->select('id')
            ->from('log_user_activity')
            ->where(['player_id' => $uid, 'activity_id' => $activity_id])
            ->scalar();

        if ($data) {

        } else {

        }
    }

    /**
     * 活动是否点击
     * @params activity_id 活动ID
     * @params $uid 玩家ID
     */
    public function actionIsClient($activity_id, $uid)
    {

    }

    /**
     * 领取活动礼包
     * @params activity_id 活动ID
     * @params $uid 玩家ID
     */
    public function actionReceive($activity_id, $uid)
    {

    }

//    以下是功能性接口
    const REPORT_TIME = 180;

    /**
     * h5拉起支付宝转账
     */
    public function actionH5Alipay()
    {

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
                $phone_num = $db->createCommand('select phone_num from t_player where player_id =' . $request['uid'])->queryScalar();
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
                    $present_data = [
                        'sourceType' => 4,
                        'propsType' => 3,
                        'count' => $this->auth_given_yuanbao,
                        'operateType' => 1,//增加元宝
                        'gameId' => 1114112,//只有大厅游戏id,1114112
                        'userId' => $request['uid']
                    ];
                    $present_url = Yii::$app->params['recharge_Url'];
                    $curl = new Curl();
                    $present_data = 'msg=' . json_encode($present_data, JSON_UNESCAPED_UNICODE);
                    $info = $curl->get($present_url . '?' . $present_data);
                    $info = json_decode($info, true);
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

    public function actionT100()
    {
        $lock_key = 'langhaijiao';
        $redis = Yii::$app->redis;

        if ($redis->get($lock_key)) {
            echo 'error';
        } else {
            $redis->set($lock_key, 1);
            $redis->pexpire($lock_key, 1);
        }
    }

    /**
     * 所有活动的配置信息
     */
    const PK_ACTIVITY = 'pk_activity';

    /**
     * 玩家已领取活动
     */
    const PK_RECEIVE_ACTIVITY = 'pk_receive_activity';

    /**
     * 玩家已点击活动
     */
    const PK_CLICK_ACTIVITY = 'pk_click_activity';

    /**
     * 活动功能
     * @params jump_type、1跳转外部、2跳转内部功能、3、weabview
     * @params jump_url、操作_子操作_子操作
     * @params goods_id、1元宝
     *
     * 需要redis中获取缓存数据
     * 根据玩家id判断各活动的开启情况
     * 后台操作之后实时更新到redis
     *
     * 使用平台自己的redis进行操作
     */
    public function actionActivity($uid)
    {
        /*
        echo '<pre>';

//        获取所有有效的活动key
        $redis = Yii::$app->platform_redis_3;
        $all = $redis->hkeys(self::PK_ACTIVITY);

//        判断当前用户可用活动
        $tmp = $redis->hget(self::PK_RECEIVE_ACTIVITY, '30705020');
        $receive = json_decode($tmp, 1);

//        最终当前用户未领取的任务id
        $activity = array_diff($all, $receive);

        $tmp = $redis->hget(self::PK_CLICK_ACTIVITY, '30705020');
        $click = json_decode($tmp, 1);

        foreach ($activity as $v) {
            $list[] = json_decode($redis->hget(self::PK_ACTIVITY, $v), 1);
        }


//        是否点击过的存储方式太麻烦、需要改改、数据库外键约束
        */

        $t = date('Y-m-d H:i:s', time());
//        所有活动列表
        $list = (new Query())
            ->select(['id', 'sort', 'title', 'title_url', 'img_url', 'goods_id', 'goods_num', 'jump_type', 'jump_url', 'activity_name'])
            ->from('conf_activity')
            ->where(['and', 'status = 1', "start_time < '{$t}'", "end_time > '{$t}'"])
            ->andWhere([])
	        ->orderBy('sort')
            ->all();
//return $list;

//        var_dump($list);
//        echo '<hr/>';

//        玩家已将领取的活动、剔除
        $user_info = (new Query())
            ->select(['activity_id', 'is_activity', 'is_click'])
            ->from('log_user_activity')
            ->where(['player_id' => $uid])
            ->all();
//        var_dump($user_info);

        foreach ($list as &$v) {
            $v['is_click'] = 0;
        }

        foreach ($list as $k => &$v) {
            foreach ($user_info as $vv) {
                if ($v['id'] == $vv['activity_id'] && $vv['is_activity'] == 1) {
                    unset($list[$k]);
                }
                if ($v['id'] == $vv['activity_id'] && $vv['is_click'] == 1) {
                    $v['is_click'] = 1;
                }
            }

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

	return array_merge($list, []);
    }

    /**
     * 是否活动点击接口
     */
    public function actionIsClick()
    {
        $request = Yii::$app->request;
        $player_id = $request->get('uid');
        $activity_id = $request->get('activity_id');
        $is_click = $request->get('click');
        $db = Yii::$app->db;
        $t = date('Y-m-d H:i:s', time());

        $id = (new Query())
            ->select('id')
            ->from('log_user_activity')
            ->where(['player_id' => $player_id, 'activity_id' => $activity_id])
            ->scalar();

        $info = $db->createCommand("INSERT INTO log_user_activity (id, player_id, activity_id, is_activity, is_click, click_count, click_time) VALUES (NULL, '{$player_id}', '{$activity_id}', 0, '{$is_click}', 1, '{$t}') ON DUPLICATE KEY UPDATE click_count = click_count + 1, last_click_time = '{$t}'")->execute();
        return $info;
    }

    /**
     * 封装所有操作功能
     */
    public function actionActivityClick()
    {

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
                $info = $db->createCommand("INSERT INTO `player_report` VALUES(NULL, '{$r['playerid']}', '{$r['be_report']}', '{$r['tableid']}', '{$r['gid']}', '{$option1}', '{$option2}','{$option3}', '{$option4}', '{$option5}', '{$t}', '{$mobile}', '{$qq}', '{$wechat}')")->execute();

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
}

