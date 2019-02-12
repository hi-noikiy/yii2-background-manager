<?php
/**
 * 
 * 回调基础类
 * @author widyhu
 *
 */
namespace common\wxpay\Lib;
use common\wxpay\Lib\WxPayApi;
use common\wxpay\Lib\WxPayNotifyReply;
use Yii;
use common\tools\Sms;

class WxPayNotify extends WxPayNotifyReply
{
	/**
	 * 
	 * 回调入口
	 * @param bool $needSign  是否需要签名输出
	 */
	final public function Handle($needSign = true)
	{
		$msg = "OK";

		$result = WxPayApi::notify(array($this, 'NotifyCallBack'), $msg);
		if($result == false){
			$this->SetReturn_code("FAIL");
			$this->SetReturn_msg($msg);
			$this->ReplyNotify(false);
			return;
		} else {
			$this->SetReturn_code("SUCCESS");
			$this->SetReturn_msg("OK");
		}

		$this->ReplyNotify($needSign);

	}
	
	/**
	 * 
	 * 回调方法入口，子类可重写该方法
	 * @param array $data 回调解释出的参数
	 * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
	 * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
	 */
	public function NotifyProcess($data, &$msg)
	{
		return true;
	}
	
	/**
	 * 
	 * notify回调方法，该方法中需要赋值需要输出的参数,不可重写
	 * @param array $data
	 * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
	 */
	final public function NotifyCallBack($data)
	{
		$msg = "OK";
		$result = $this->NotifyProcess($data, $msg);
		
		if($result == true){
			$this->SetReturn_code("SUCCESS");
			$this->SetReturn_msg("OK");
		} else {
			$this->SetReturn_code("FAIL");
			$this->SetReturn_msg($msg);
			//短信通知
			if (!Yii::$app->redis->exists('sms_remind_duration_limit_30min')) {
				$sms = new Sms(Yii::$app->params['sms']['api_key'],Yii::$app->params['sms']['use_ssl']);
				$res = $sms->send_batch(Yii::$app->params['sms']['mobile_list'],'[wxpay]'.substr($msg, 0, 100).Yii::$app->params['sms']['sign']);
				if ($res['error']!==0) {
					Yii::info("sms remind failed with message: {$res['msg']}",'wxpay');
				} else {
					//短信通知周期限制每30分钟1次
					Yii::$app->redis->set('sms_remind_duration_limit_30min', 'true');
					Yii::$app->redis->expire('sms_remind_duration_limit_30min',1800);
				}
			}
		}
		return $result;
	}
	
	/**
	 * 
	 * 回复通知
	 * @param bool $needSign 是否需要签名输出
	 */
	final private function ReplyNotify($needSign = true)
	{
		//如果需要签名

		if($needSign == true && 
			$this->GetReturn_code() == "SUCCESS")
		{
			$this->SetSign();
		}
		WxpayApi::replyNotify($this->ToXml());

	}
}