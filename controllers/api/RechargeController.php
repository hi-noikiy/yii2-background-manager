<?php
/**
 * User: SeaReef
 * Date: 2018/9/4 11:33
 *
 * 总充值接口
 */
namespace app\controllers\api;

use app\common\Code;
use app\common\Tool;
use app\controllers\BaseController;
use app\models\Activity;
use app\models\LobbyPlayer;
use app\models\LogUserActivity;
use app\models\Order;
use app\models\Payment;
use app\models\RechargeConf;
use callmez\wechat\sdk\Wechat;
use Yii;
use yii\base\Curl;
use yii\db\Query;
use yii\helpers\Url;
use app\common\wxpay\JsApiPay;
use app\common\wxpay\Lib\WxPayApi;
use app\common\wxpay\Lib\WxPayConfig;
use app\common\wxpay\Lib\WxPayUnifiedOrder;


class RechargeController extends BaseController
{
    /**
     * 是否轮序支付
     */
    const IS_POLLING = 0;

    /**
     * 是否开启权重
     */
    const IS_WEIGHT = 0;

    public $enableCsrfValidation = false;

    /**
     * 初始化参数
     */
    private $uid;

    private $gid;

    private $rid;

    private $pay_type;

    private $terminal;

//    支付渠道配置信息
    private $channel_info;


    public function init()
    {
//        初始化参数
        $this->initParams();
    }

    /**
     * 验证初始化参数
     */
    public function initParams()
    {
        $request = Yii::$app->request;
        $this->uid = $request->get('uid');
        $this->gid = $request->get('gid');
        $this->rid = $request->get('rid');
        $this->pay_type = $request->get('pay_type');
        $this->terminal = $request->get('terminal');

        Yii::info($request->get(), '玩家下单记录');

        if (empty($this->uid) || empty($this->gid) || empty($this->rid) || empty($this->pay_type) || empty($this->terminal)) {
            $this->writeJson(Code::CODE_PARAMS_ERROR);
        }
    }

    /**
     * 总支付入口
     *
     * @params string uid 用户id
     * @params string gid 游戏id
     * @params int rid 商品id
     * @params int pay_type wechat/alipay/unionpay
     * @params string terminal ios/android
     */
    public function actionPay()
    {
//        检测充值黑名单
        $this->checkBlack($this->uid);

//        检测白名单
        $this->checkWhite($this->uid);

//        选择支付渠道
        $this->ChoiceChannel($this->pay_type, $this->uid);
    }

    /**
     * 白名单支付功能
     */
    public function actionWhitePay()
    {
        $class_code = Yii::$app->request->get('class_code');
        $this->$class_code();
    }

    /**
     * 检测支付黑名单
     */
    private function checkBlack($uid)
    {
        $black = (new Query())
            ->select('id')
            ->from('conf_recharge_black_list')
            ->where(['status' => 1, 'player_id' => $uid])
            ->scalar();

        if ($black) {
//            $this->writeResult(Code::CODE_PAY_BLACK_LIST);
            echo Code::$CODE_MESSAGES[Code::CODE_PAY_BLACK_LIST];
        }
    }

    /**
     * 检测支付白名单
     * 能测试所有充值渠道
     */
    private function checkWhite($uid)
    {
        $white = (new Query())
            ->select('id')
            ->from('conf_recharge_white_list')
            ->where(['status' => 1, 'player_id' => $uid])
            ->scalar();

//        展示所有充值方式
        if ($white) {
            $data = $this->paymentChannel();

            echo $this->render('white_list', ['data' => $data]);
            die();
        }
    }

    /**
     * 所有充值方式充值方式
     */
    private function paymentChannel()
    {
        $payment_channel = (new Query())
            ->select('*')
            ->from('conf_payment_channel')
            ->all();

        foreach ($payment_channel as $v) {
//            var_dump($v);
            $payment = (new Query())
                ->select('*')
                ->from('conf_payment')
                ->where(['id' => $v['payment']])
                ->one();
            $channel = (new Query())
                ->select('*')
                ->from('conf_pay_channel')
                ->where(['id' => $v['pay_channel']])
                ->one();

            $data[$v['id']] = [
                'name' => $payment['remark'] . '---' . $channel['channel_name'],
                'url' => Url::toRoute(['api/recharge/white-pay', 'class_code' => $channel['class_code'], 'uid' => $this->uid, 'gid' => $this->gid, 'rid' => $this->rid, 'pay_type' => $this->pay_type, 'terminal' => $this->terminal]),
            ];
        }

        return $data;
    }

    /**
     * 选择支付渠道
     */
    private function choiceChannel($pay_type, $uid)
    {
//        是否开启轮询支付
        if (self::IS_POLLING) {
//            是否开启权重
            if (self::IS_WEIGHT) {

            } else {
                $this->channel_info = Payment::getPayChannelByPolling($pay_type, $uid);
            }
        } else {
            $this->channel_info = Payment::getPayChannel($pay_type, $uid);
        }

        $class_name = $this->channel_info['class_code'];
        $this->$class_name();
    }

    /**
     * 汇付宝
     */
    public function Heepay()
    {
//        验证用户信息
        $user = LobbyPlayer::checkUser($this->uid);
        if (!$user) {
            $this->writeResult(Code::CODE_PAY_USER_NOT_FOUND);
        }

//        验证商品信息
        $goods = RechargeConf::checkGoods($this->rid);
        if (!$goods) {
            $this->writeResult(Code::CODE_PAY_GOODS_NOT_FOUND);
        }

//        如果这个商品配置是活动进行验证
        if ($goods['is_activity']) {
//        验证是否有对应活动
            $activity = Activity::isValid($goods['activity_id']);
            if (!$activity) {
                $this->writeResult(Code::CODE_INVALID_RECHARGE_ACTIVITY);
            }
//        验证用户是否领取活动
            $is_receive = LogUserActivity::isReceive($user['player_id'], $goods['activity_id']);
            if ($is_receive) {
                $this->writeResult(Code::CODE_ACTIVITY_HAS_COMPLETED);
            }
        }

//        添加临时订单
        $order_id = Order::generateOrderNum();
        $create_time = date('Y-m-d H:i:s', time());
        $order = new Order();
        $res = $order->addOrder($data = [
            'channel_id' => 1,
            'order_id' => $order_id,
            'player_id' => $user['player_id'],
            'nickname' => $user['nickname'] ? : '特殊昵称',
            'player_create' => $user['reg_time'],
            'goods_id' => $this->rid,
            'goods_type' => $goods['type'],
            'goods_num' => $goods['num'],
            'goods_price' => $goods['price'],
            'pay_channel' => $this->channel_info['channel_code'],
            'pay_type' => $this->pay_type,
            'pay_terminal' => $this->terminal,
            'status' => 0,
            'create_time' => $create_time,
        ]);
        if (!$res) {
            file_put_contents('/tmp/tm_order.log', print_r([$data, $res], 1), FILE_APPEND);
            $this->writeResult(Code::CODE_ADD_ORDER_ERROR);
        }

//        渠道需要的参数格式
        $order_time = date('YmdHis');
        $ip = str_replace('.', '_', Yii::$app->request->userIP);
        $pay_type = $this->heepay_trans($this->pay_type);

//        公用参数列表
        $params_arr = [
            'version' => 1,
            'agent_id' => $this->channel_info['appid'],
            'agent_bill_id' => $order_id,
            'agent_bill_time' => $order_time,
            'pay_type' => $pay_type,
            'pay_amt' => $goods['price'],
            'notify_url' => $this->channel_info['notify_url'],
            'return_url' => $this->channel_info['return_url'],
            'user_ip' => $ip,
        ];

        $str = '';
        foreach ($params_arr as $k => $v) {
            $str .= '&' . $k . '=' . $v;
        }
        $str = ltrim($str, '&');
        $sign = md5($str . '&key=' . $this->channel_info['appkey']);
        $goods_name = iconv('UTF-8', 'GB2312', $goods['goods_name']);

        switch ($this->pay_type) {
            case 'wechat':
                $meta_option = urldecode(base64_encode(json_encode([
                    's' => 'WAP',
                    'n' => '超然官网',
                    'id' => 'http://www.game0165.com/',
                ])));

                $url = $this->channel_info['trade_url'] . $str . '&goods_name=' . $goods_name . '&goods_num=' . $goods['num'] . '&goods_note="说明"&remark=1&mate_option=' . $meta_option . '&is_phone=1&is_frame=1&sign=' . $sign;
                break;
            case 'alipay':
                $url = $this->channel_info['trade_url'] . $str . "&goods_name={$goods_name}&goods_num={$goods['num']}&goods_note='说明'&remark=1&is_phone=1&sign={$sign}";
                break;
            case 'unionpay':
                $url = $this->channel_info['trade_url'] . $str . "&goods_name={$goods_name}&goods_num={$goods['num']}&goods_note='说明'&remark=1&sign={$sign}";
                break;
        }

        $this->redirect($url);
    }

    /**
     * @param $pay_type
     * @return mixed
     *
     * 支付宝H5 -》22
     * 微信公众号 -》 30
     * 银联WAP-》19
     * 2117096
     *
     * 网银 -》 20
     * 2117095
     */
    private function heepay_trans($pay_type)
    {
        $params = [
            'wechat' => 30,
            'alipay' => 22,
            'unionpay' => 19,
        ];

        return $params[$pay_type];
    }

    /**
     * 光大支付
     */
    public function Guangda()
    {
        header("Content-type: text/html; charset=utf-8");

        //        验证用户信息
        $user = LobbyPlayer::checkUser($this->uid);
        if (!$user) {
            $this->writeResult(Code::CODE_PAY_USER_NOT_FOUND);
        }

        //验证商品信息
        $goods = RechargeConf::checkGoods($this->rid);
        if (!$goods) {
            $this->writeResult(Code::CODE_PAY_GOODS_NOT_FOUND);
        }

        //如果这个商品配置是活动进行验证
        if ($goods['is_activity']) {
            //验证是否有对应活动
            $activity = Activity::isValid($goods['activity_id']);
            if (!$activity) {
                $this->writeResult(Code::CODE_INVALID_RECHARGE_ACTIVITY);
            }
            //验证用户是否领取活动
            $is_receive = LogUserActivity::isReceive($user['player_id'], $goods['activity_id']);
            if ($is_receive) {
                $this->writeResult(Code::CODE_ACTIVITY_HAS_COMPLETED);
            }
        }

        //添加临时订单
        $order_id = Order::generateOrderNum();
        $create_time = date('Y-m-d H:i:s', time());
        $order = new Order();
        $res = $order->addOrder($data = [
            'channel_id' => 1,
            'order_id' => $order_id,
            'player_id' => $user['player_id'],
            'nickname' => $user['nickname'] ? : '特殊昵称',
            'player_create' => $user['reg_time'],
            'goods_id' => $this->rid,
            'goods_type' => $goods['type'],
            'goods_num' => $goods['num'],
            'goods_price' => $goods['price'],
            'pay_channel' => $this->channel_info['channel_code'],
            'pay_type' => $this->pay_type,
            'pay_terminal' => $this->terminal,
            'status' => 0,
            'create_time' => $create_time,
        ]);
        if (!$res) {
            Yii::info($data, '添加临时订单失败');
            file_put_contents('/tmp/tm_order.log', print_r([$data, $res], 1), FILE_APPEND);
            $this->writeResult(Code::CODE_ADD_ORDER_ERROR);
        }

        $date = date('Ymd');
        $order_time = date('YmdHis');
        $ip = Yii::$app->request->userIP;

        header("Content-type: text/html; charset=utf-8");

        $type='alipay';
        if($type == 'alipay'){
            $payType = 'ALIPAY';
            $urls ='http://api.zql666.cn/wapPay/doPay';
        }elseif($type == 'wx'){
            $payType = 'wechat';
            $urls ='http://api.zql666.cn/scanPay/initPay';
        }else{
            echo "支付类型错误！";exit;
        }
        $config = $this->channel_info;

        $signStr ="field1=one1";
        $signStr .="&field2=two2";
        $signStr .="&field3=three3";
        $signStr .="&field4=fore4";
        $signStr .="&field5=".$this->gid;
        $signStr .="&notifyUrl=".$this->channel_info['notify_url'];  //支付成功页面回调地址;   //通知地址
        $signStr .="&orderDate=".date("Ymd");    //订单日期
        $signStr .= "&orderIp=".Yii::$app->request->userIP;  //订单ip
        $signStr .= "&orderNo=".$order_id;   //订单编号（保证商户系统唯一）
        $signStr .= "&orderPrice=".$goods['price'];     //金额
        $signStr .= "&orderTime=".date("YmdHis");    //订单时间  yyyyMMddHHmmss
        $signStr .= "&payKey=".$config['appid'];   //商户key
        $signStr .="&payWayCode=".$payType;    //支付类型 wap支付为 aplipay
        $signStr .="&productName=alipay";   //商品名
        $signStr .="&remark=alipay-wap-info";   //备注
        $signStr .="&returnUrl=".$this->channel_info['notify_url']; //支付回调
        $signStr .="&paySecret=".$config['appkey'];  //商户密钥
        $signStr .="&sign=".strtoupper(md5($signStr));

        $url = $urls.'?'. ( $signStr );  //支付url
        Yii::info("支付参数：".$url);
        $curl = new Curl();
        $data = $curl->post($url);
//        var_dump($data);exit;
        $res = json_decode($data,true);
//              var_dump($res);exit;

        if($res['status'] == 'WAITING_PAYMENT'){
            echo $res['payMessage'];exit;
        }else{
            echo "支付发起失败！请联系客服";die;
        }
    }

    /**
     * 竣付通支付
     */
    public function Jpay()
    {
//        如果是竣付通的公众号支付、进行网页授权
        if ($this->pay_type == 'wechat') {
            $res = $this->auth_wechat();
            $openid = $res['openid'];
            $p29_ext4 = $openid;
        } else {
            $p29_ext4 = '';
        }

        //验证用户信息
        $user = LobbyPlayer::checkUser($this->uid);
        if (!$user) {
            $this->writeResult(Code::CODE_PAY_USER_NOT_FOUND);
        }

//        验证商品信息
        $goods = RechargeConf::checkGoods($this->rid);
        if (!$goods) {
            $this->writeResult(Code::CODE_PAY_GOODS_NOT_FOUND);
        }

//        如果这个商品配置是活动进行验证
        if ($goods['is_activity']) {
//        验证是否有对应活动
            $activity = Activity::isValid($goods['activity_id']);
            if (!$activity) {
                $this->writeResult(Code::CODE_INVALID_RECHARGE_ACTIVITY);
            }
//        验证用户是否领取活动
            $is_receive = LogUserActivity::isReceive($user['player_id'], $goods['activity_id']);
            if ($is_receive) {
                $this->writeResult(Code::CODE_ACTIVITY_HAS_COMPLETED);
            }
        }

//        添加临时订单
        $order_id = Order::generateOrderNum();
        $create_time = date('Y-m-d H:i:s', time());
        $order = new Order();
        $res = $order->addOrder($data = [
            'channel_id' => 1,
            'order_id' => $order_id,
            'player_id' => $user['player_id'],
            'nickname' => $user['nickname'] ? : '特殊昵称',
            'player_create' => $user['reg_time'],
            'goods_id' => $this->rid,
            'goods_type' => $goods['type'],
            'goods_num' => $goods['num'],
            'goods_price' => $goods['price'],
            'pay_channel' => $this->channel_info['channel_code'],
            'pay_type' => $this->pay_type,
            'pay_terminal' => $this->terminal,
            'status' => 0,
            'create_time' => $create_time,
        ]);
        if (!$res) {
            file_put_contents('/tmp/tm_order.log', print_r([$data, $res], 1), FILE_APPEND);
            $this->writeResult(Code::CODE_ADD_ORDER_ERROR);
        }

        $pay_type = $this->jpay_trans($this->pay_type);
        $order_time = date('YmdHis');
        $ip = str_replace('.', '_', Yii::$app->request->userIP);
        $terminal = $this->jpay_terminal($this->terminal);

        $compkey 		   = $this->channel_info['appkey'];		//商户密钥
        $p1_yingyongnum	   = $this->channel_info['appid'];			//商户应用号
        $p2_ordernumber        = $order_id;		//商户订单号
        $p3_money 		   = $goods['price'];			//商户订单金额，保留两位小数
        $p6_ordertime  	   = $order_time;			//商户订单时间
        $p7_productcode	   = $pay_type;				//产品支付类型编码
        $presign 		   = $p1_yingyongnum."&".$p2_ordernumber."&".$p3_money."&".$p6_ordertime."&".$p7_productcode."&".$compkey;
        $p8_sign 		   = md5($presign);				//订单签名
        $p9_signtype 		   = "1";					//签名方式
        $p10_bank_card_code = "";						//银行卡或卡类编码
        $p11_cardtype  	   = "";						//商户支付银行卡类型id
        $p12_channel 	   = "";						//商户支付银行卡类型长度
        $p13_orderfailertime    = "";						//订单失效时间
        $p14_customname       = $user['player_id'];		//商户游戏账号
        $p15_customcontact    = "";						//商户联系内容
        $p16_customip  	   = $ip;			//付款ip地址
        $p17_product  	   = $goods['goods_name'];					//商户名称
        $p18_productcat	   = "";						//商品种类
        $p19_productnum        = "";						//商品数量
        $p20_pdesc                = "";						//商品描述
        $p21_version               = "";						//对接版本
        $p22_sdkversion	   = "";						//SDK版本
        $p23_charset   	   = "UTF-8";					//编码格式
        $p24_remark    	   = "";						//备注
        $p25_terminal  	   = "2";					//商户终端设备值
        // 终端设备值1 pc 2 ios  3 安卓
        $p26_ext1     		   = "1.1"; 					//商户标识
        $p27_ext2    		   = "";						//预留参数
        $p28_ext3     		   = "";						//预留参数

        file_put_contents('/tmp/jpay.log', print_r(["https://toqlicr.sunlin1.com/jh-web-order/order/receiveOrder?p1_yingyongnum={$p1_yingyongnum}&p2_ordernumber={$p2_ordernumber}&p3_money={$p3_money}&p6_ordertime={$p6_ordertime}&p7_productcode={$p7_productcode}&p8_sign={$p8_sign}&p9_signtype={$p9_signtype}&p25_terminal={$terminal}&p29_ext4={$p29_ext4}&paytype=zz"], 1), FILE_APPEND);

        echo "<body onLoad='document.yeepay.submit();'>
        <form name='yeepay' action=https://toqlicr.sunlin1.com/jh-web-order/order/receiveOrder?p1_yingyongnum={$p1_yingyongnum}&p2_ordernumber={$p2_ordernumber}&p3_money={$p3_money}&p6_ordertime={$p6_ordertime}&p7_productcode={$p7_productcode}&p8_sign={$p8_sign}&p9_signtype={$p9_signtype}&p25_terminal={$terminal}&p29_ext4={$p29_ext4}&paytype=zz method='get'>
                <input type='hidden' name='p1_yingyongnum'                     value='{$p1_yingyongnum}'>
                <input type='hidden' name='p2_ordernumber'                     value='{$p2_ordernumber}'>
                <input type='hidden' name='p3_money'                            value='{$p3_money}'>
                <input type='hidden' name='p6_ordertime'                       value='{$p6_ordertime}'>
                <input type='hidden' name='p7_productcode'                     value='{$p7_productcode}'>
                <input type='hidden' name='p8_sign'                             value='{$p8_sign}'>
                <input type='hidden' name='p9_signtype'                        value='{$p9_signtype}'>
                <input type='hidden' name='p25_terminal'                       value='{$terminal}'>
                <input type='hidden' name='p29_ext4'                            value='{$p29_ext4}'>
                <input type='hidden' name='paytype'                             value='zz'>
        </form>
</body>";
    }

    private function jpay_trans($pay_type)
    {
        $arr = [
            'alipay' => 'ZFB',
            'wechat' => 'WXGZH',
            'unionpay' => 'UNION',
        ];

        return $arr[$pay_type];
    }

    private function jpay_terminal($terminal)
    {
        $arr = [
            'ios' => 2,
            'android' => 3,
            'pc' => 1,
        ];
    }

    /**
     * 微信公众号支付获取openid
     */
    public function auth_wechat()
    {
        $request = Yii::$app->request;
        $code = $request->get('code', '');
        $wechat = Yii::$app->wechat_jpay;
//        file_put_contents('/tmp/jpay_wechat.log', print_r([$code], 1), FILE_APPEND);

        if (!empty($code)) {
            $info = $wechat->getOauth2AccessToken($code);
//            file_put_contents('/tmp/jpay_wechat.log', print_r([$info], 1), FILE_APPEND);
            return $info;
        } else {
            $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//            file_put_contents('/tmp/jpay_wechat.log', print_r([$url], 1), FILE_APPEND);
            $url = $wechat->getOauth2AuthorizeUrl($url);
//            file_put_contents('/tmp/jpay_wechat.log', print_r([$url], 1), FILE_APPEND);

            header('Location:' . $url);
        }
    }


    /**
     * 微信H5支付
     * 商户号/1518866601
     * appid wx8549c01c1a00382f
     */
    public function Wechatwap()
    {
        $user = LobbyPlayer::checkUser($this->uid);
        if (!$user) {
            $this->writeResult(Code::CODE_PAY_USER_NOT_FOUND);
        }

//        验证商品信息
        $goods = RechargeConf::checkGoods($this->rid);
        if (!$goods) {
            $this->writeResult(Code::CODE_PAY_GOODS_NOT_FOUND);
        }

//        如果这个商品配置是活动进行验证
        if ($goods['is_activity']) {
//        验证是否有对应活动
            $activity = Activity::isValid($goods['activity_id']);
            if (!$activity) {
                $this->writeResult(Code::CODE_INVALID_RECHARGE_ACTIVITY);
            }
//        验证用户是否领取活动
            $is_receive = LogUserActivity::isReceive($user['player_id'], $goods['activity_id']);
            if ($is_receive) {
                $this->writeResult(Code::CODE_ACTIVITY_HAS_COMPLETED);
            }
        }

//        添加临时订单
        $order_id = Order::generateOrderNum();
        $create_time = date('Y-m-d H:i:s', time());
        $order = new Order();
        $res = $order->addOrder($data = [
            'channel_id' => 1,
            'order_id' => $order_id,
            'player_id' => $user['player_id'],
            'nickname' => $user['nickname'] ? : '特殊昵称',
            'player_create' => $user['reg_time'],
            'goods_id' => $this->rid,
            'goods_type' => $goods['type'],
            'goods_num' => $goods['num'],
            'goods_price' => $goods['price'],
            'pay_channel' => $this->channel_info['channel_code'],
            'pay_type' => $this->pay_type,
            'pay_terminal' => $this->terminal,
            'status' => 0,
            'create_time' => $create_time,
        ]);
        if (!$res) {
            file_put_contents('/tmp/tm_order.log', print_r([$data, $res], 1), FILE_APPEND);
            $this->writeResult(Code::CODE_ADD_ORDER_ERROR);
        }

        $request = Yii::$app->request;
        $request->ipHeaders = [
            'ali-cdn-real-ip',
        ];
        $ip = Yii::$app->request->userIp;

        $url = $this->channel_info['trade_url'];
        $param['appid'] = $this->channel_info['appid'];
        $param['mch_id'] = $this->channel_info['reserve1'];
        $param['nonce_str'] = 'yiquan' . mt_rand(100, 999);
        $param['body'] = '充值';
        $param['out_trade_no'] = $order_id;
        $param['total_fee'] = $goods['price']*100;
        $param['spbill_create_ip'] = $ip;
        $param['notify_url'] = $this->channel_info['notify_url'];
        $param['trade_type'] = 'MWEB';
        $sign = $this->getSign($param,$this->channel_info['appkey']);
        $param['sign'] = $sign;

        $res = $this->curl_post_ssl($url,$param);

        $checkResult = (array)simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);

        return $this->render('wxpay', [
            'url' => $checkResult['mweb_url']."&redirect_url=".urlencode($this->channel_info['return_url']),
            'price' =>$goods['price'],
        ]);
    }

    /**
     * 作用：使用证书，以post方式提交xml到对应的接口url
     *
     * @param $url
     * @param $vars
     * @param int $second
     * @return bool|mixed
     */
    public function curl_post_ssl($url, $vars, $second = 30)
    {
        $vars_ = $this->arrayToXml($vars);
        Yii::info('提交给微信的xml---' . $vars_);
        $ch = curl_init();
        //超时时间　　
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars_);
        $data = curl_exec($ch);

        curl_close($ch);
        return $data;
    }

    /**
     * 汇潮支付下单
     */
    public function Ecpss()
    {
//        验证用户信息
        $user = LobbyPlayer::checkUser($this->uid);
        if (!$user) {
            $this->writeResult(Code::CODE_PAY_USER_NOT_FOUND);
        }

//        验证商品信息
        $goods = RechargeConf::checkGoods($this->rid);
        if (!$goods) {
            $this->writeResult(Code::CODE_PAY_GOODS_NOT_FOUND);
        }

//        如果这个商品配置是活动进行验证
        if ($goods['is_activity']) {
//        验证是否有对应活动
            $activity = Activity::isValid($goods['activity_id']);
            if (!$activity) {
                $this->writeResult(Code::CODE_INVALID_RECHARGE_ACTIVITY);
            }
//        验证用户是否领取活动
            $is_receive = LogUserActivity::isReceive($user['player_id'], $goods['activity_id']);
            if ($is_receive) {
                $this->writeResult(Code::CODE_ACTIVITY_HAS_COMPLETED);
            }
        }

//        添加临时订单
        $order_id = Order::generateOrderNum();
        $create_time = date('Y-m-d H:i:s', time());
        $order = new Order();
        $res = $order->addOrder($data = [
            'channel_id' => 1,
            'order_id' => $order_id,
            'player_id' => $user['player_id'],
            'nickname' => $user['nickname'] ? : '特殊昵称',
            'player_create' => $user['reg_time'],
            'goods_id' => $this->rid,
            'goods_type' => $goods['type'],
            'goods_num' => $goods['num'],
            'goods_price' => $goods['price'],
            'pay_channel' => $this->channel_info['channel_code'],
            'pay_type' => $this->pay_type,
            'pay_terminal' => $this->terminal,
            'status' => 0,
            'create_time' => $create_time,
        ]);
        if (!$res) {
            file_put_contents('/tmp/tm_order.log', print_r([$data, $res], 1), FILE_APPEND);
            $this->writeResult(Code::CODE_ADD_ORDER_ERROR);
        }

//        拼接需要的下单参数
        $order_time = date('YmdHis');
        $sign_str = 'MerNo=' . $this->channel_info['appid'] . '&BillNo=' . $order_id .'&Amount=' . $goods['price'] .'&OrderTime=' . $order_time .'&AdviceUrl=' . $this->channel_info['notify_url'];
        $private_key = self::ecpss_info($this->channel_info['appid'])['private_key'];
        $sign = Tool::genSign($sign_str, $private_key);

        $params['ScanPayRequest'] = [
            'MerNo' => $this->channel_info['appid'],
            'BillNo' => $order_id,
            'payType' => $this->trans_ecpss($this->pay_type),
            'Amount' => $goods['price'],
            'OrderTime' => $order_time,
            'ReturnUrl' => $this->channel_info['return_url'],
            'AdviceUrl' => $this->channel_info['notify_url'],
            'ScanpayMerchantCode' => $this->channel_info['appkey'],
            'SignInfo' => $sign,
            'MerName' => self::ecpss_info($this->channel_info['appid'])['name'],
        ];

        $xml = Tool::buildXml($params, 'xml');
        $url = $this->channel_info['trade_url'];
        $curl = new Curl();
        $res = $curl->setPostParams([
            'requestDomain' => base64_encode($xml),
        ])->post($url);

//        var_dump($res);
        $values = json_decode(json_encode(simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        if ($values['respCode'] == '0000') {
            header('Location:' . $values['qrCode']);
        } else {
            echo '下单失败、请重新点击';
        }
        die();
    }

    private function trans_ecpss($pay_type)
    {
        $params = [
            'wechat' => 'WxJsapi_OnLine',
            'alipay' => 'AliJsapiPay_OffLine',
            'unionpay' => 'UnionScanPay_I',
        ];

        return $params[$pay_type];
    }

    /**
     * 转化汇潮的appid与名称
     */
    public static function ecpss_info($appid)
    {
        $params = [
            46659 => [
                'name' => '广州志勇网络科技有限公司',
                'private_key' => "MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAL89vHkLh7S7I9q8O7HBe+uoKFYezCzIDjBCD6fqdW4F9nJRjX+8QSIq2r1ZKM4rn6tDo75bgcLbnphXnqYiHJ8X6cQElR+QWaAMfMOOue4Gg39XOQy0O9+P4Z7ZUERuvLEMuE58J9N8xBY6whuYlPIC5jAWGXu3S5Zo4cdYd5j/AgMBAAECgYBitNismVjn3Zd59KHS84yka1y2Zpr3miJIojA2ePs3WHiFNLaV8XKk0f2osXYwQ6/b08OWIuDs1DIda0wFb6HZTlzcUqT27QIYshfWSc718h9WDHrjZHOgAiQRm3z12u3aXURvrsRDUMFLRUaAkCvR7774bHcsDHnS9kePHnWTwQJBAPES2rSMfXmx2dXHWzRidOle3iAkxpf9dQwE3nQ6+ZAJViMsUfOw3N5xmcdlF6D9WWrsjIoQwUdx1d4J/yDkqo8CQQDLFQKnjp9wmkKC7lEycrhj9MCb9Jg269/adNfExT3agpODdi7aI/hxNiK7yszZ0PJXCJt7z6Fb12UmD8K49CKRAkAexSZPN0Novg+s3rZAeHStuOMnPSpwCfTfpNt0AHcMMHTjJmwLa761UdCsB7Y9YTkBkdHaaYsSAHCo16PN4gH3AkBhxxHqHs9BZeRUKe5KPdXtum/qJtAK0XKMOemRQe5QAMKJbyOLv/nkeE4s4K3UybeElA5YhFWKZKC8vKXiaxkhAkAnN3F4oD6JSjVO0EUah/sgjThBcdrsuqCUuk2zPfTsKI33p3p3PKEM+Sqq0nbkd312DObwl7cnrRrZlxNP3K8s",
//                'public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC/Pbx5C4e0uyPavDuxwXvrqChWHswsyA4wQg+n6nVuBfZyUY1/vEEiKtq9WSjOK5+rQ6O+W4HC256YV56mIhyfF+nEBJUfkFmgDHzDjrnuBoN/VzkMtDvfj+Ge2VBEbryxDLhOfCfTfMQWOsIbmJTyAuYwFhl7t0uWaOHHWHeY/wIDAQAB",
                'public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCO0nYy7DPpZ4usM3lDKN6UlKUx4OEN9RMEDarV9nUHmB9hgKZOj5z5aQEXst3HLTR/RbvsV2+UTH11/BqQCo/3nKu9FCHAFjCbOoXE+hMHpZnAxlQ2yXSIS6njRr7RblNj+yTKVl/s6DXSCqBqV8a/SuF8kCNWbBB3gBozG69rqwIDAQAB",
            ],
            46814 => [
                'name' => '上海一拳网络科技有限公司',
                'private_key' => "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAImg1XMkv6qRwUAXTXC+9xrnlQUaoxwQijK5Mo9xhfBIcVBOQ/+X2gbSdvKDt+ZjBwHtkzRrBp7ryMfT1csAEJEJFMgg5/tARS/7Quy90OAaP9sVzeuisrbM/4vuFGxQg3EX/PlU0Bo7EdsteoJNUZoTNuYsV0inN3kutR4i3IgvAgMBAAECgYBs5eX/BcTiohHXNJkB7Rh5IWS4dSs6uZugJwnqllj0l12RvVvwkabiXwXfPnn0wcZ5sBIaF9VVShvEgclYpuRzrIGGsHJsisNvJHaX3oP/4eFhga46rZx3QY9CqrKRjpRKbnK6QpHl/m15nG0FkoYgKPs9/3LotSI7VZT2MYS8AQJBAO8cPL6xwQryO9L9x2xapwyUFaSB/SUzDML+6+VaGTl7kQ9Ch4uCmsASL6QWIT7/6iNQWJmEV1oihmzRCXb56E8CQQCTWYaidonXkMCeP/NfYlNoBugaaUENndBHbEPOVHLWY8XL6+1MIDISFeetOEtB62J0Tp/syWx7jT6gUdDeJYohAkEA5hOgq8l0vCirwfEp9Cwic2sGjD38LNA7ZmJO8GjKvtIUmb9ll6s7ZrfZGaWlpe4wCHzmVAnQ181C1fAqkE4V5wJAeH9r5Iv/qXNMsZ0mj0g/YQDU6lRMyK/X1bCpup/A0aYB6QNkqS5jA/s53KP+l0fA2dA6ZE9MEbWuEzkVD7WvQQJACZ/NnwS/mX4wE67vPEyi/3n/J8Qiboi/gGmoFpo2CI4GodxKT28VZIAsKAeZxbPp6xpwSOsWi4zN0/X048ugoQ==",
//                'public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC/Pbx5C4e0uyPavDuxwXvrqChWHswsyA4wQg+n6nVuBfZyUY1/vEEiKtq9WSjOK5+rQ6O+W4HC256YV56mIhyfF+nEBJUfkFmgDHzDjrnuBoN/VzkMtDvfj+Ge2VBEbryxDLhOfCfTfMQWOsIbmJTyAuYwFhl7t0uWaOHHWHeY/wIDAQAB",
                'public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCM3Ref/s9TEe/q0ISe1a7AH76BgN5akYOsztfabx+MRSIS7vbGvy5pIWNxeZoTESoi4H4bXFJi2PobU13Uf+C6wk7vE3k2cV8LAg6370vq1oYjCLM1FOx9oznFvlAGQ1UfCxSxLJpVTMWMKvtqsGj1KRL7CQI2cVq++hIVKmrstQIDAQAB",
            ],
//            暂不可用
            47115 => [
                'name' => '广州威杰网络科技有限公司',
                'private_key' => "MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAL89vHkLh7S7I9q8O7HBe+uoKFYezCzIDjBCD6fqdW4F9nJRjX+8QSIq2r1ZKM4rn6tDo75bgcLbnphXnqYiHJ8X6cQElR+QWaAMfMOOue4Gg39XOQy0O9+P4Z7ZUERuvLEMuE58J9N8xBY6whuYlPIC5jAWGXu3S5Zo4cdYd5j/AgMBAAECgYBitNismVjn3Zd59KHS84yka1y2Zpr3miJIojA2ePs3WHiFNLaV8XKk0f2osXYwQ6/b08OWIuDs1DIda0wFb6HZTlzcUqT27QIYshfWSc718h9WDHrjZHOgAiQRm3z12u3aXURvrsRDUMFLRUaAkCvR7774bHcsDHnS9kePHnWTwQJBAPES2rSMfXmx2dXHWzRidOle3iAkxpf9dQwE3nQ6+ZAJViMsUfOw3N5xmcdlF6D9WWrsjIoQwUdx1d4J/yDkqo8CQQDLFQKnjp9wmkKC7lEycrhj9MCb9Jg269/adNfExT3agpODdi7aI/hxNiK7yszZ0PJXCJt7z6Fb12UmD8K49CKRAkAexSZPN0Novg+s3rZAeHStuOMnPSpwCfTfpNt0AHcMMHTjJmwLa761UdCsB7Y9YTkBkdHaaYsSAHCo16PN4gH3AkBhxxHqHs9BZeRUKe5KPdXtum/qJtAK0XKMOemRQe5QAMKJbyOLv/nkeE4s4K3UybeElA5YhFWKZKC8vKXiaxkhAkAnN3F4oD6JSjVO0EUah/sgjThBcdrsuqCUuk2zPfTsKI33p3p3PKEM+Sqq0nbkd312DObwl7cnrRrZlxNP3K8s",
//                'public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC/Pbx5C4e0uyPavDuxwXvrqChWHswsyA4wQg+n6nVuBfZyUY1/vEEiKtq9WSjOK5+rQ6O+W4HC256YV56mIhyfF+nEBJUfkFmgDHzDjrnuBoN/VzkMtDvfj+Ge2VBEbryxDLhOfCfTfMQWOsIbmJTyAuYwFhl7t0uWaOHHWHeY/wIDAQAB",
                'public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCM3Ref/s9TEe/q0ISe1a7AH76BgN5akYOsztfa
bx+MRSIS7vbGvy5pIWNxeZoTESoi4H4bXFJi2PobU13Uf+C6wk7vE3k2cV8LAg6370vq1oYjCLM1
FOx9oznFvlAGQ1UfCxSxLJpVTMWMKvtqsGj1KRL7CQI2cVq++hIVKmrstQIDAQAB",
            ],
            47135 => [
                'name' => '广州诗迪科技有限公司',
                'private_key' => "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAI2J1jcBqksCFC54qkuKCh6AC8b41s4HE8T6KsDMAv4/VGzYG0hDcYfl7ZmH3ncBi8Zw64+zzJqSpGIwpEV2yyw6wwQ5PIGwkgk0GfPCrQg42eooxdFQ/+DXBBoBZxCaIV0J5/EOoxd1UOBu235vLyv4+MwAhTEgDET64b+WNbWJAgMBAAECgYBtOScT/7b4dzw3uGpCLN4uN0XfX/vztcZtxVfkvAq7RQfTjtlO5Kzh4G+AgTcNwyWoF0/Q70E/L1anZz39VPTnoBeYcxnmaYq86g+fHHLRq4KyUpCTv2hbJuNTXoRRTowR0hSXHPzFU7dsqQVJoWUau7CfEPg+NXCvv7FjfufWGQJBAM5wLR9Kx2/kBTMskiU9HknJ/ieTM6I3e8UQ9DJc6YC7NWjXN58zc6P+WveYWR+efM1MvLhxP4f3eyw5sn1h/GcCQQCvhOEcOGdz/cnoyhqoNY9LQfG/pEYnErC9M4Nsk86Aa/lfc3c1KgmPJkdNuYURc7rHTugvgtA6SjxgSpewpYiPAkBN2qhpwL3uSMRdRFXpjV863N7o9e7nIp2e+IHf7IJzdibyXxgvBix6kJamAeK6tf7DPkl+Fder432kdC4Ic0fJAkEAofpuSL3g81F2QWgqe6uaByjfci2nNC3yf1kH427wduGubKFBlQWrdrkrupYIMNftujue2SHPCswxteeA39uUDwJARtxqpwZU+aS3UEbpAoHrid4hjFEyIGAO4CoEf1OP9pdm8xComm2hXrwNU/YzgjmQnjPv1wWuViPC5EfHtXucfA==",
//                'public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCrwY731gVPGBWAO3QWXsskjHTNc8/UURfXuEVB                yo0dTyU1j+BSigLaLNqlhKlk0tXSwibyVw17fKVIRrzcMi/v4AQ9VmrFAU1Gwpkh6lUHuVyMVjMRlXbsBP7hglg41jfziAhAa+jG3tNJEciijRMg+5HtclidFeKOSuwPOiFSJwIDAQAB",
                'public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCSmH3/b+ja5XNIPwIqCNXPdKl7yTdAMSQI/lHeFvkqYpFr7sNonx6dHEm81G1uebVPbqiMHlkXe/Ixt4acy9Bm9BuT+DDELXlFLfX8+E4uADia+oGv3k4BEWelRU5j2DAxW53v2m174iOyFr/sWs+8FW8ov/dRnhnXjjMXqWxwnwIDAQAB",
            ],
        ];

        return $params[$appid];
    }

    /**
     * 生成RSA签名
     */
    private function genRsaSign($toSign, $privateKey)
    {
        $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($privateKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";

        $key = openssl_get_privatekey($privateKey);
        openssl_sign($toSign, $signature, $key);
        openssl_free_key($key);
        $sign = base64_encode($signature);
        return $sign;
    }
}
