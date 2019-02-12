<?php
/**
 * User: moyu
 * Date: 2018/7/9 11:23
 * 公共类
 */
namespace app\commands;

use yii\console\Controller;
use Yii;
use yii\base\Curl;
use app\common\Tool;

class AppController extends Controller
{
    public $redis;

    public $config;

    public $tel;

    /**
     * 判断多维数组(二维或以上)是否存在值
     *
     * @param $value
     * @param $array
     * @return bool
     */
    public  function deep_in_array($value, $array) {
        foreach($array as $item) {
            if(!is_array($item)) {
                if ($item == $value) {
                    return true;
                } else {
                    continue;
                }
            }

            if(in_array($value, $item)) {
                return true;
            } else if($this->deep_in_array($value, $item)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 通知data服加减金币
     *
     * @param $playerId
     * @param $amount
     * @param $type - 处理金币方式 1增 2减
     * @return mixed
     */
    public function disposeGold($playerId,$amount,$type){
        $present_data = [
            'sourceType'=>Tool::RECHARGE_PLAYER,
            'propsType'=>3,//固定为元宝
            'count'=>$amount,
            'operateType'=>$type,//固定为减少
            'gameId'=>1114112,//固定为大厅的id
            'userId'=>$playerId
        ];
        $present_url  = Yii::$app->params['recharge_Url'];
        $curl = new Curl();
        $present_data = 'msg=' . json_encode($present_data, JSON_UNESCAPED_UNICODE);
        $info = $curl->get($present_url.'?'.$present_data);
        $info = json_decode($info,true);

        return $info;
    }

    /**
     * 生成签名，规则和微信的规则一样
     *
     * @param $data(值为空的过滤掉)
     * @return bool
     */
    public function getSign($data,$key=''){
        if(!is_array($data) && !empty($data)){
            return false;
        }
        /** 去空 */
        unset($data[array_search('',$data)]);

        ksort($data);
        $str = '';
        foreach ($data as $key=>$val){
            if($str){
                $str .= '&'.$key.'='.$val;
            }else{
                $str .= $key.'='.$val;
            }
        }

        if($key){
            $str .= '&key=' . $key;
        }

        $sign=strtoupper(md5($str));

        return $sign;
    }

    /**
     * 拼接请求微信接口数据
     *
     * @param $data
     * @return mixed
     */
    public function getWechatPostData($data){
        $sign = $this->getSign($data,$this->config['API_KEY']);

        $data["sign"] = $sign;//签名

        return $data;

    }

    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    //将XML转为array
    public function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

}