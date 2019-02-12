<?php
namespace app\common\wxpay;

use app\common\wxpay\Lib\WxPayApi;
use common\wxpay\Lib\WxPayException;
use common\wxpay\Lib\WxPayJsApiPay;
use app\common\wxpay\Lib\WxPayConfig;
use yii\base\Exception;
use yii\web\Cookie;
use Yii;

class JsApiPay
{
    
    private static  function getConfig()
    {
       return WxPayConfig::getInstance();
    }
    
    /**
     * 获取OpenId
     * @param string $getFromCookieIfExists 若为true，且cookie中已保存openid，则直接返回cookie中的openid
     * @return string openid
     */
	public function GetOpenid($getFromCookieIfExists = true)
	{
	    $apiConfig = self::getConfig();
	    
	    if (Yii::$app->request->cookies->get('openid') && $getFromCookieIfExists) {
	    	return Yii::$app->request->cookies->get('openid');
	    }
	    
		//通过code获得openid
		if (!isset($_GET['code'])){
			//触发微信返回code码
			//$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
			$req = Yii::$app->request;
			$baseUrl = urlencode($req->hostInfo.$req->url);
			
			//在测试环境下，因微信网页授权域名限制，跳转到正式环境做网页授权
			if (!YII_ENV_PROD && isset($apiConfig['oauth_proxy'])) {
				$url = $apiConfig['oauth_proxy'] . "?appid={$apiConfig['appid']}&redirect_uri=$baseUrl&scope=snsapi_base";
				Header("Location: $url");
				exit();
			}
			
			$url = $this->__CreateOauthUrlForCode($baseUrl,$apiConfig);
			Header("Location: $url");
			exit();
		} else {
			//获取code码，以获取openid
		    $code = $_GET['code'];
			$openid = $this->getOpenidFromMp($code,$apiConfig);
			Yii::$app->response->cookies->add(new Cookie([
			'name' => 'openid',
			'value' => $openid,
			'expire' => null,
			]));
			return $openid;
		}
	}
	
	/**
	 * Get user info by wechat oauth2 api
	 * return demo
	 * [
	 * 		"openid":" OPENID"," nickname": NICKNAME,"sex":"1","province":"PROVINCE","city":"CITY","country":"COUNTRY","headimgurl":"http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1d6","privilege":[ "PRIVILEGE1" "PRIVILEGE2"],"unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL" 
	 * ] 
	 * @return array UserInfo
	 */
	public function GetUserInfo()
	{
		$apiConfig = self::getConfig();
		 
		//通过code获得openid
		if (!isset($_GET['code'])){
			//触发微信返回code码
			//$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
			$req = Yii::$app->request;
			$baseUrl = urlencode($req->hostInfo.$req->url);
			
			//在测试环境下，因微信网页授权域名限制，跳转到正式环境做网页授权
			if (!YII_ENV_PROD && isset($apiConfig['oauth_proxy'])) {
				$url = $apiConfig['oauth_proxy'] . "?appid={$apiConfig['appid']}&redirect_uri=$baseUrl&scope=snsapi_userinfo";
				Header("Location: $url");
				exit();
			}
			
			$url = $this->__CreateOauthUrlForCode($baseUrl,$apiConfig,'snsapi_userinfo');
			Header("Location: $url");
			exit();
		} else {
			//获取code码，以获取用户信息
			$code = $_GET['code'];
			$userInfo = $this->GetUserInfoFromMp($code,$apiConfig);
			if (is_array($userInfo) && !empty($userInfo)) {
				isset($userInfo['openid']) && Yii::$app->response->cookies->add(new Cookie([
					'name' => 'openid',
					'value' => $userInfo['openid'],
					'expire' => null,
				]));
				isset($userInfo['unionid']) && Yii::$app->response->cookies->add(new Cookie([
					'name'=>'unionid',
					'value'=>$userInfo['unionid'],
					'expire'=>null
				]));
			}
			
			return $userInfo;
		}
	}
	
	public function GetJsApiParameters($UnifiedOrderResult)
	{
		if(!array_key_exists("appid", $UnifiedOrderResult)
		|| !array_key_exists("prepay_id", $UnifiedOrderResult)
		|| $UnifiedOrderResult['prepay_id'] == "")
		{
			throw new Exception("参数错误");
		}
		$jsapi = new WxPayJsApiPay();
		$jsapi->SetAppid($UnifiedOrderResult["appid"]);
		$timeStamp = time();
		$jsapi->SetTimeStamp($timeStamp);
		$jsapi->SetNonceStr(WxPayApi::getNonceStr());
		$jsapi->SetPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);
		$jsapi->SetSignType("MD5");
		$jsapi->SetPaySign($jsapi->MakeSign());
		$parameters = json_encode($jsapi->GetValues());
		return $parameters;
	}
	
	public function GetOpenidFromMp($code,$apiConfig)
	{
		$url = $this->__CreateOauthUrlForOpenid($code,$apiConfig);
		//初始化curl
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if($apiConfig['curl_proxy_host'] != "0.0.0.0" 
			&& $apiConfig['curl_proxy_port'] != 0){
			curl_setopt($ch,CURLOPT_PROXY, $apiConfig['curl_proxy_host']);
			curl_setopt($ch,CURLOPT_PROXYPORT, $apiConfig['curl_proxy_port']);
		}
		$res = curl_exec($ch);
		curl_close($ch);
		//取出openid
		$data = @json_decode($res,true);
		if (is_array($data) && isset($data['openid'])) {
			return $data['openid'];
		}
	}
	
	public function GetUserInfoFromMp($code,$apiConfig)
	{
		//Step 1: fetch `access_token` and `openid`
		$url = $this->__CreateOauthUrlForOpenid($code,$apiConfig);
		$res = $this->__Curl($url, $apiConfig);
		
		//convert to json data
		$data = @json_decode($res,true);
		
		//Step 2: fetch `userinfo` with `access_token` and `openid` which we have fetched at step 1
		if (!is_array($data) || !isset($data['access_token']) || !isset($data['openid'])) {
			return [];
		}
		$url = $this->__CreateOauthUrlForUserInfo($data['access_token'], $data['openid']);
		$res = $this->__Curl($url, $apiConfig);
		
		$userInfo = @json_decode($res,true);
		
		return $userInfo;
	}
	
	private function ToUrlParams($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			if($k != "sign"){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}
	
	private function __CreateOauthUrlForCode($redirectUrl,$apiConfig,$scope='snsapi_base')
	{	    
		$urlObj["appid"] = $apiConfig['appid'];
		$urlObj["redirect_uri"] = "$redirectUrl";
		$urlObj["response_type"] = "code";
		$urlObj["scope"] = $scope;
		$urlObj["state"] = "STATE"."#wechat_redirect";
		$bizString = $this->ToUrlParams($urlObj);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
	}
	
	private function __CreateOauthUrlForOpenid($code,$apiConfig)
	{
		$urlObj["appid"] = $apiConfig['appid'];
		$urlObj["secret"] = $apiConfig['appsecret'];
		$urlObj["code"] = $code;
		$urlObj["grant_type"] = "authorization_code";
		$bizString = $this->ToUrlParams($urlObj);
		return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
	}
	
	private function __CreateOauthUrlForUserInfo($accessToken,$openid)
	{
		$urlObj["access_token"] = $accessToken;
		$urlObj["openid"] = $openid;
		$urlObj["lang"] = 'zh_CN';
		$bizString = $this->ToUrlParams($urlObj);
		return "https://api.weixin.qq.com/sns/userinfo?".$bizString;
	}
	
	private function __Curl($url,$apiConfig)
	{
		//初始化curl
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if($apiConfig['curl_proxy_host'] != "0.0.0.0"
				&& $apiConfig['curl_proxy_port'] != 0){
			curl_setopt($ch,CURLOPT_PROXY, $apiConfig['curl_proxy_host']);
			curl_setopt($ch,CURLOPT_PROXYPORT, $apiConfig['curl_proxy_port']);
		}
		//运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		return $res;
	}
	
}