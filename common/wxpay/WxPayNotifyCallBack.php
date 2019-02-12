<?php 
namespace common\wxpay;

use common\wxpay\Lib\WxPayNotify;
use common\wxpay\Lib\WxPayOrderQuery;
use common\wxpay\Lib\WxPayApi;
use Yii;
use Exception;
use common\models\Order;
use common\Api;

class WxPayNotifyCallBack extends WxPayNotify{

	const EVENT_AFTER_VALIDATE = 'afterValidate';
	//查询订单
	public function Queryorder($transaction_id){
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg){
		$encode_data = json_encode($data);
		$order_id = $data["out_trade_no"];
		$gid = Yii::$app->params['gid'] = $data["attach"];
		$msgPrefix = "[$gid][$order_id]";
		
		Yii::info("$msgPrefix begin notify process, gid: $gid, data: $encode_data",'wxpay');
		
		$games = Yii::$app->params['mj'];
		if(!isset($games[$gid])){
			$msg = "$msgPrefix Yii::\$app->params['mj']['\$gid'] required, please check file `common/config/params-local.php`";
			Yii::info($msg,'wxpay');
			return false;
		}
		$order = Order::findOne(['f_order_id'=>$order_id]);
		if(!$order){
			$msg = "$msgPrefix order not found in table `t_order`";
			Yii::info($msg,'wxpay');
			return false;
		}
		
		//$msgPrefix = "[file:".__FILE__."][line:".__LINE__."][order_id:$order_id][uid:$order->f_uid]";
		$msgPrefix = "[$gid][$order_id][$order->f_uid]";
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "$msgPrefix transaction_id required";
			Yii::info($msg,'wxpay');
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "$msgPrefix order query fail with transaction_id: {$data["transaction_id"]}";
			Yii::info($msg,'wxpay');
			return false;
		}

		// 验证订单金额
		if ($data['total_fee'] != $order->f_price * 100) {
			$msg = "$msgPrefix fee check fail, expect " . ($order->f_price * 100) . " but {$data['total_fee']} received";
			Yii::warning($msg,'wxpay');
			return false;
		}
		
		//验证订单状态
		if($order->f_status == 1){
			$msg = "$msgPrefix already processed, current order status is `1`";
			Yii::warning($msg,'wxpay');
			return true;
        }
        
        //触发事件
        Api::RechargeFlowingToAgentSystem($order->f_uid, $order_id, $order->f_price,'');
		//开始发货
		
		if($order->f_status == 1){
			$msg = "$msgPrefix already processed, current order status is `1`";
			Yii::info($msg,'wxpay');
			return true;
        }

		$db = Yii::$app->db;
		$transaction = $db->beginTransaction();
		try {
            $order->f_charge_paramdown = $encode_data;
            $order->f_status = 1;
            $order->save();
            if(isset($games[$gid]['recharge']['apiUrlOld'])){
            	$total = $order->f_num + $order->f_award;
            	$apiParams = [
            		'gid' => $gid,
            		'order_id' => $order_id,
            		'player_id' => $order->f_uid,
            		'recharge_diamond' => $total,
            		'recharge_money' => $order->f_price,
            		'remark' => 'wxpay',
            		'send_diamond' => $order->f_award,
            		'recharge_type' => $order->f_type,//Api::RECHARGE_TYPE_DIAMOND,
            		'operation_type' => $order->f_pay_channel,
            	];
            	if (!Api::PlayerRechargeOld($games[$gid]['recharge']['apiUrlOld'], $apiParams, "wxpay")) {
            		$msg = "$msgPrefix recharge api error";
            	}
            }
			$transaction->commit();
		} catch (Exception $e) {
			$msg = "$msgPrefix transaction rollback: {$e->getMessage()}";
			if (defined(YII_ENV_TEST) && YII_ENV_TEST) {
				Yii::error($msg);
			} else {
				Yii::info($msg,'wxpay');
			}
            $transaction->rollBack();
            $db->close();
            return false;
		}
		$db->close();
		Yii::info("$msgPrefix end notify process, " . ($msg == 'OK' ? 'success' : 'with internal error'),'wxpay');
		return true;
	}
}

 ?>