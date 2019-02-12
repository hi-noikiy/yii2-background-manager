<?php
namespace app\common;

use Yii;
class Api
{
	/**
	 * 充值类型：钻石
	 * @var integer
	 */
	const RECHARGE_TYPE_DIAMOND = 1;
	/**
	 * 充值类型：金币
	 * @var	integer
	 */
	const RECHARGE_TYPE_GOLDCOIN = 2;
	/**
	 * 玩家充值接口
	 * 接口参数列表说明
	 * [
	 * gid:"yichang",//游戏ID
	 * order_id:12,//非必须
	 * player_id:123,
	 * recharge_diamond:100,//充值钻石数或者充值金币数
	 * recharge_money:10,//充值金额
	 * remark:"",//充值备注
	 * send_diamond:10,//赠送钻石数
	 * recharge_type:1,//充值类型，1钻石，2金币
	 * operation_type:"app",//wap:公众号自助充值，app:游戏内充值,activity:活动赠送,other:其他
	 * ]
	 * @param string $apiUrl api url
	 * @param array $apiParams
	 * @param string $category 用于日志输出，默认为“app”
	 * @return boolean success or fail
	 */
	public static function PlayerRecharge($apiUrl, $apiParams, $category = "application")
	{
		if (empty($apiParams) || !is_array($apiParams)) {
			return false;
		}
		$operationTypes = [
			'wap' => 18,
			'app' => 19,
			'activity' => 20,
			'other' => 40,
		];
		
		if (isset($apiParams['operation_type']) && array_key_exists($apiParams['operation_type'], $operationTypes)) {
			$operationType = $operationTypes[$apiParams['operation_type']];
		} else {
			$operationType = $operationTypes['other'];
		}
		$param = "player_id=".$apiParams['player_id']."recharge_diamond=".$apiParams['recharge_diamond']."recharge_money=".$apiParams['recharge_money']."remark=".$apiParams['remark']."send_diamond=".$apiParams['send_diamond']."recharge_type=".$apiParams['recharge_type']."operation_type=".$operationType;
		$sign = md5($param."recharge_mdwl_1013");
		$params = "player_id=".$apiParams['player_id']."&recharge_diamond=".$apiParams['recharge_diamond']."&recharge_money=".$apiParams['recharge_money']."&remark=".$apiParams['remark']."&send_diamond=".$apiParams['send_diamond']."&recharge_type=".$apiParams['recharge_type']."&operation_type=".$operationType."&gameid=".$apiParams['gid'];
		$apiUrl .= $params."&sign=".$sign;
		
		Yii::warning("request api url: $apiUrl",$category);

		$result = \common\Common::curlReuqest($apiUrl,false);
		
		$res = @json_decode($result,true);
		if(!isset($res['errCode']) || $res['errCode'] == 1){
			$msg = "diamond api error: $result, api url [$apiUrl]";
			Yii::warning($msg,$category);
			$urlInfo = parse_url($apiUrl);
			$host = $urlInfo['scheme'] . '://' . $urlInfo['host'] . (isset($urlInfo['port']) ? (':' . $urlInfo['port']) : '') . '/';
			$path = trim($urlInfo['path'],'/');
			$apiLog = new \common\models\RechargeDiamondLog();
			$apiLog->load([
					'ORDER_ID' => $apiParams['order_id'],
					'HOST' => $host,
					'PATH' => $path,
					'PLAYER_ID' => $apiParams['player_id'],
					'RECHARGE_DIAMOND' => $apiParams['recharge_diamond'],
					'RECHARGE_MONEY' => $apiParams['recharge_money'],
					'REMARK' => $apiParams['remark'],
					'SEND_DIAMOND' => $apiParams['send_diamond'],
					'RECHARGE_TYPE' => $apiParams['recharge_type'],
					'OPERATION_TYPE' => $operationType,
					'GAME' => $apiParams['gid'],
					'CREATETIME' => date('Y-m-d H:i:s',time()),
					],'');
				
			if ($apiLog->save()) {
				Yii::warning("error log has inserted into `api_error_log`.`t_recharge_diamond_log`",$category);
			} else {
				Yii::warning("error log insert fail",$category);
			}
			return false;
		}
		return true;
	}
	
	/*public static function PlayerRechargeOld($apiUrl, $apiParams, $category = "application")
	{
		if (empty($apiParams) || !is_array($apiParams)) {
			return false;
		}
		$operationTypes = [
			'wap' => 18,
			'weixin_wap' => 18,
			'alipay_wap' => 18,
			'app' => 19,
			'weixin_app' => 19,
			'alipay_app' => 19,
			'activity' => 20,
			'other' => 40,
		];
	
		if (isset($apiParams['operation_type']) && array_key_exists($apiParams['operation_type'], $operationTypes)) {
			$operationType = $operationTypes[$apiParams['operation_type']];
		} else {
			$operationType = $operationTypes['other'];
		}
		
		if (!YII_ENV_PROD) {
			// 非生产环境下，充值金额向上取整
			$apiParams['recharge_money'] = ceil($apiParams['recharge_money']);
		}
		
		$param = "player_id=".$apiParams['player_id']."recharge_diamond=".$apiParams['recharge_diamond']."recharge_money=".$apiParams['recharge_money']."remark=".$apiParams['remark']."send_diamond=".$apiParams['send_diamond'];
		$sign = md5($param."recharge_mdwl_1013");
		$params = "player_id=".$apiParams['player_id']."&recharge_diamond=".$apiParams['recharge_diamond']."&recharge_money=".$apiParams['recharge_money']."&remark=".$apiParams['remark']."&send_diamond=".$apiParams['send_diamond']."&recharge_type=".$apiParams['recharge_type']."&operation_type=".$operationType."&gameid=".$apiParams['gid'];
		$apiUrl .= $params."&sign=".$sign;
		
		Yii::warning("request api url: $apiUrl",$category);
		
		$result = \common\Common::curlReuqest($apiUrl,false);
		$res = @json_decode($result,true);
		if(!isset($res['errCode']) || $res['errCode'] == 1){
			$msg = "diamond api error: $result, api url [$apiUrl]";
			Yii::warning($msg,$category);
			$urlInfo = parse_url($apiUrl);
			$host = $urlInfo['scheme'] . '://' . $urlInfo['host'] . (isset($urlInfo['port']) ? (':' . $urlInfo['port']) : '') . '/';
			$path = trim($urlInfo['path'],'/');
			$apiLog = new \common\models\RechargeDiamondLog();
			$apiLog->load([
					'ORDER_ID' => $apiParams['order_id'],
					'HOST' => $host,
					'PATH' => $path,
					'PLAYER_ID' => $apiParams['player_id'],
					'RECHARGE_DIAMOND' => $apiParams['recharge_diamond'],
					'RECHARGE_MONEY' => $apiParams['recharge_money'],
					'REMARK' => $apiParams['remark'],
					'SEND_DIAMOND' => $apiParams['send_diamond'],
					'RECHARGE_TYPE' => $apiParams['recharge_type'],
					'OPERATION_TYPE' => $operationType,
					'GAME' => $apiParams['gid'],
					'CREATETIME' => date('Y-m-d H:i:s',time()),
					],'');
	
			if ($apiLog->save()) {
				Yii::warning("error log has inserted into `api_error_log`.`t_recharge_diamond_log`",$category);
			} else {
				Yii::warning("error log insert fail",$category);
			}
			return false;
		}
		return true;
	}*/



	/**
	 * 新的充钻接口
	 * @param unknown $apiUrl
	 * @param unknown $apiParams
	 * @param string $category
	 * @return boolean
	 */
	public static function PlayerRechargeOld($apiUrl, $apiParams, $category = "application")
	{
		if (empty($apiParams) || !is_array($apiParams)) {
			return false;
		}
		$sourceTypes = [
			'wap' => 3,
			'weixin_wap' => 3,
			'alipay_wap' => 3,
			'app' => 2,
			'weixin_app' => 2,
			'alipay_app' => 2,
			'heepay_app' => 2,
			'zypay_app' => 2,
			'sftpay_app' => 2,
			'applepay_app' => 2,
			'iapppay_app' =>2,
			'beepay' =>2,
			'activity' => 4,
			'other' => 5,
		];
	
		if (isset($apiParams['operation_type']) && array_key_exists($apiParams['operation_type'], $sourceTypes)) {
			$sourceType = $sourceTypes[$apiParams['operation_type']];
		} else {
			$sourceType = $sourceTypes['other'];
		}
	
		if (!YII_ENV_PROD) {
			// 非生产环境下，充值金额向上取整
			$apiParams['recharge_money'] = ceil($apiParams['recharge_money']);
		}
		
		$sign = "";
		
		$data = [
			'userId' => $apiParams['player_id'],
			'gameId' => $apiParams['gid'],
			'orderId' => $apiParams['order_id'],
			'sourceType' => $sourceType,
			'operateType' => 1,
			'propsType' => $apiParams['recharge_type'],
			'count' => $apiParams['recharge_diamond'],
			'money' => $apiParams['recharge_money'],
			'remark' => $apiParams['remark'],
		];
	
		//$param = "userId=".$data['userId']."orderId=".$data['orderId']."count=".$data['count']."money=".$data['money']."remark=".$data['remark']."gameId=".$data['gameId']."sourceType=".$data['sourceType']."propsType=".$data['propsType']."operateType=".$data['operateType'];
		//$sign = md5($param."recharge_mdwl_1013");
		
		//$data['sign'] = $sign;
		$key = 'b8d92f63be1c5b0c';
        	$params_str = $data['userId'] . $data['orderId'] . $data['count'] . $data['money'] . $data['remark'] . $data['gameId'] . $data['sourceType'] . $data['propsType'] . $data['operateType'] . $key;
		$sign = md5($params_str);
		$data['sign'] = $sign;
		
		$body = 'msg=' . json_encode($data, JSON_UNESCAPED_UNICODE);
		file_put_contents('/tmp/sign.log', date('Y-m-d H:i:s', time()) . "\r\n" . $apiUrl . "\r\n" . print_r($body, 1) . "\r\n" . $params_str . "\r\n\r\n", FILE_APPEND);
		Yii::info("api request: $apiUrl, form data: $body",$category);
		
		$result = \common\Common::curlReuqest($apiUrl, true, $body, 5);		
		
		Yii::info("api response: $result",$category);
		
		$res = @json_decode($result,true);
       
		if(!isset($res['code']) || ($res['code'] != 100001 && $res['code'] != 0)){
			$msg = "diamond api error: $result, api url [$apiUrl]";
			Yii::warning($msg, $category);
			$urlInfo = parse_url($apiUrl);
			$host = $urlInfo['scheme'] . '://' . $urlInfo['host'] . (isset($urlInfo['port']) ? (':' . $urlInfo['port']) : '') . '/';
			$path = trim($urlInfo['path'],'/');
			$apiLog = new \common\models\RechargeDiamondLog();
			$apiLog->load([
					'ORDER_ID' => $apiParams['order_id'],
					'HOST' => $host,
					'PATH' => $path,
					'PLAYER_ID' => $apiParams['player_id'],
					'RECHARGE_DIAMOND' => $apiParams['recharge_diamond'],
					'RECHARGE_MONEY' => $apiParams['recharge_money'],
					'REMARK' => $apiParams['remark'],
					'SEND_DIAMOND' => $apiParams['send_diamond'],
					'RECHARGE_TYPE' => $apiParams['recharge_type'],
					'OPERATION_TYPE' => $sourceType,
					'GAME' => $apiParams['gid'],
					'CREATETIME' => date('Y-m-d H:i:s',time()),
					],'');
	
			if ($apiLog->save()) {
				Yii::warning("error log has inserted into `api_error_log`.`t_recharge_diamond_log`",$category);
			} else {
				Yii::warning("error log insert fail",$category);
			}
			return false;
		}
		return true;
	}


	/**
	 * 查询玩家的推荐人ID
	 * @param int $player_id
	 * @return int 推荐人ID
	 */
	public static function QueryRefererId($player_id)
	{
		
		$refererId = 0;
		try {
				
			$params = [
				'user_id' => $player_id,
				'r' => 'daili-accept-api/check-user-bind',
				'gid' => Yii::$app->params['gid'],
			];
				
			$key = Yii::$app->params['api']['daili_web_key'];

			//签名步骤一：按字典序排序参数
			ksort($params, SORT_STRING);
			$string = Common::toUrlParams($params);
			//签名步骤二：在string后加入KEY
			$string = $string . "&sign=".$key;
			//签名步骤三：MD5加密
			$string = md5($string);
			//签名步骤四：所有字符转为大写
			$params['sign'] = strtoupper($string);
			$formData = http_build_query($params);
			$apiUrl = Yii::$app->params['api']['daili_web_url'];
			Yii::info("api request: $apiUrl, form data:$formData", "api/" . __METHOD__);
			$result = \common\Common::curlReuqest($apiUrl.$formData, true, $formData);
			Yii::info("api response: $result", "api/" . __METHOD__);
			$res = @json_decode($result,true);
			if(!isset($res['ret_code']) || ($res['ret_code'] != 0 && $res['ret_code'] != 5993)){
				$msg = "api error: $result, api url [$apiUrl]";
				Yii::warning($msg, "api/" . __METHOD__);
			}
			
			if (isset($res['data']['daili_id'])) {
				$refererId = $res['data']['daili_id'];
			}
			
		} catch (\Exception $e) {
			Yii::error($e->getMessage(), "api/" . __METHOD__);
		}
		
		return $refererId;
	}

	/**
	 * 充值流水推送给代理系统
	 * @param int $player_id 玩家ID
	 * @param string $order_id 订单号
	 * @param double $buy_money 订单金额
	 * @return boolean
	 */
	public static function RechargeFlowingToAgentSystem($player_id, $order_id, $buy_money, $remark = null)
	{
		try {
			
			if (Yii::$app->redis->hget("recharge-flowing-to-agent-system", $order_id)) {
				return true;
			}
			
			$gid =  Yii::$app->params['gid'];
			$config = null;
			
			if (!is_null($remark)) {
				$remark = @json_decode($remark, true);
				if (is_array($remark) && isset($remark['gameid'])) {
					$config = Yii::$app->game->getConfig($remark['gameid']);
					$gid = $remark['gameid'];
				}
			}
			$gid = ($gid == 1114112)?524803:$gid;	
			$params = [
				'player_id' => $player_id,
				'order_id' => $order_id,
				'buy_money' => $buy_money,
				'time' => date("Y-m-d H:i:s"),
				'r' => 'daili-accept-api/accept-buy-order-info',
				'gid' => $gid,
			];
			
			//$key = $config['api']['recharge-flowing-to-agent-system']['checkSignatureKey'];
			$key = Yii::$app->params['api']['daili_web_key'];
			//签名步骤一：按字典序排序参数
			ksort($params, SORT_STRING);
			$string = Common::toUrlParams($params);
			//签名步骤二：在string后加入KEY
			$string = $string . "&sign=".$key;
			//签名步骤三：MD5加密
			$string = md5($string);
			//签名步骤四：所有字符转为大写
			$params['sign'] = strtoupper($string);
			$formData = http_build_query($params);
			//$apiUrl = $config['api']['recharge-flowing-to-agent-system']['url'];
			$apiUrl = Yii::$app->params['api']['daili_web_url'];

			Yii::info("api request: $apiUrl, form data:$formData", "api/" . __METHOD__);
			$result = \common\Common::curlReuqest($apiUrl.$formData, true, $formData);
			Yii::info("api response: $result", "api/" . __METHOD__);
			$res = @json_decode($result,true);
			if(!isset($res['ret_code']) || $res['ret_code'] != 0){
				$msg = "api error: $result, api url [$apiUrl]";
				Yii::warning($msg, "api/" . __METHOD__);
				return false;
			}
			Yii::$app->redis->hset("recharge-flowing-to-agent-system", $order_id, "1");
			return true;
		} catch (\Exception $e) {
			Yii::error($e->getMessage(), "api/" . __METHOD__);
			return false;
		}
	}
}
