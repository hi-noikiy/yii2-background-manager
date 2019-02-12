<?php
/**
 * User: jw
 * Date: 2018/7/27 0027
 */
namespace app\common\helpers;

use yii;

class Sms
{
    /**
     * 发送短信
     * @param $mobile
     * @param $content
     */
    public static function send($mobile,$content)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://sms-api.luosimao.com/v1/send.json");

        curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-'.Yii::$app->params['api-key']);

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $mobile,'message' => $content));

        $res = curl_exec( $ch );
        curl_close( $ch );

        return $res;
    }

    /**
     * 生成随机数
     * @param int $len
     * @return string
     */
    public static function randNumber($len = 6)
    {
        $key='';
        for($i=0; $i<$len; ++$i) {
            $key .= mt_rand(0,9);    // 生成php随机数
        }
        return $key;

    }
}