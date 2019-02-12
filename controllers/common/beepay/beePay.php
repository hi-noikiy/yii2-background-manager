<?php 
namespace app\controllers\common\beepay;
require_once("sdk/src/rest/config.php");
require_once("sdk/src/rest/network.php");
require_once("sdk/src/rest/api.php");

use Yii;
use Exception;
use common\models\Order;
use app\models\GoldOrder;
use app\common\Api;

class beePay {

	public $api;
	public $international;
	public $subscription;
	public $auth;

	const APP_ID = 'e9b0dd9a-5666-41be-8c3c-d41d15a2157a';
	//支付或者查询时使用
	const APP_SECRET = '8239d116-d67a-469b-a9a9-d46fdfa07138';
	//退款或者打款时使用
	const MASTER_SECRET = '91a1ec63-fe55-46d0-b328-2ee26d0c46bf';
	//test_secret for sandbox
	const TEST_SECRET = '91a1ec63-fe55-46d0-b328-2ee26d0c46bf';
	

	public function __construct(){
		$this->api = new \beecloud\rest\api();
    		$this->international = new \beecloud\rest\international();
    		$this->subscription = new \beecloud\rest\Subscriptions();
    		$this->auth = new \beecloud\rest\Auths();
    	try {
		    /* registerApp fun need four params:
		     * @param(first) $app_id beecloud平台的APP ID
		     * @param(second) $app_secret  beecloud平台的APP SECRET
		     * @param(third) $master_secret  beecloud平台的MASTER SECRET
		     * @param(fouth) $test_secret  beecloud平台的TEST SECRET, for sandbox
		     */
		    $this->api->registerApp(self::APP_ID,self::APP_SECRET,self::MASTER_SECRET,self::TEST_SECRET);
		    //Test Model,只提供下单和支付订单查询的Sandbox模式,不写setSandbox函数或者false即live模式,true即test模式
		    $this->api->setSandbox(false);
		    //\beecloud\rest\api::registerApp(APP_ID, APP_SECRET, MASTER_SECRET, TEST_SECRET);
		    //\beecloud\rest\api::setSandbox(false);
		}catch(Exception $e){
		    die($e->getMessage());
		}
	}

	public function checkSign($info){

		if(!$info['signature']){
			return false;
		}
		$sign = md5(self::APP_ID.$info['transaction_id'].$info['transaction_type'].$info['channel_type'].$info['transaction_fee'].self::MASTER_SECRET);
		if($sign == $info['signature']){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 支付成功后的回调处理
	 * @Author   WKein
	 * @DateTime 2018-02-07T21:49:48+0800
	 * @param    [type]                   $info [description]
	 */
	public function NotifyProcess($info){

	Yii::info('进行BEEPAY回调处理','beePay');
		
        $gid   = Yii::$app->params['gid'] = Yii::$app->request->get('gid');
        $games_url = Yii::$app->params['recharge_Url'];
     
        $msgPrefix = '['.$gid.']['.$info['transaction_id'].']';
        //查询订单信息
        $order_id = $info['transaction_id'];

        $order = GoldOrder::findOne(['f_order_id'=>$order_id]);
        if(!$order){
            Yii::info( $msgPrefix.'支付订单不存在：','beePay');
            return false;
        }

        // 验证订单状态
        if($order->f_status == 1){  
	
            Yii::info($msgPrefix.'支付订单已经成功处理!','beePay');
            return true;
        }

        // 验证订单金额
        if ($info['transaction_fee'] != ($order->f_price*100)) {
            Yii::info($msgPrefix.'订单金额不匹配：数据库信息'.$order->f_price.' 反馈信息'.($info['transaction_fee']/100),'beePay');
            return false;
        }
       
        //触发事件
//        Api::RechargeFlowingToAgentSystem($order->f_uid,$order_id, $order->f_price,'');

        // 开始发货
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $order->f_charge_paramdown = json_encode($info['messageDetail']);
            $order->f_status = 1;
            $order->save();
            if(isset($games_url)){
                $total = $order->f_num + $order->f_award;
                $apiParams = [
                    'gid' => $gid,
                    'order_id' => $order_id,
                    'player_id' => $order->f_uid,
                    'recharge_diamond' => $order->f_num,
                    'recharge_money' => $order->f_price,
                    'remark' => 'beepay',
                    'send_diamond' => $order->f_award,
                    'recharge_type' => $order->f_type,
                    'operation_type' => $order->f_pay_channel,
                ];
                if (!Api::PlayerRechargeOld($games_url, $apiParams, "beePay")) {
                    Yii::info($msgPrefix.'订单发货失败！','beePay');
                }
            }
            $transaction->commit();
        }catch (Exception $e) {
            file_put_contents("/home/bwqpweb/relog/log.txt",$e->getMessage()."/r/n",FILE_APPEND);
            Yii::info($msgPrefix.'订单信息已经回滚，错误信息'.$e->getMessage(),'beePay');
            if (defined(YII_ENV_TEST) && YII_ENV_TEST) {
                Yii::error($msgPrefix.'订单信息已经回滚，错误信息'.$e->getMessage());
            } else {
                Yii::info($msgPrefix.'订单信息已经回滚，错误信息'.$e->getMessage(),'beePay');
            }
            $transaction->rollBack();
            $db->close();
            return false;
        }
        $db->close();
        Yii::info($msgPrefix.' end notify process, success','iappPay');
        return true;
	}

}
