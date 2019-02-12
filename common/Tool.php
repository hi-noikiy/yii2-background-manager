<?php
/**
 * User: SeaReef
 * Date: 2018/8/29 20:51
 */
namespace app\common;

use Yii;
use yii\base\Curl;

class Tool
{
    /**
     * source_type、增加来源
     * 参数类型
     * 1.WEB/2.GAME/3.WECHAT/4.活动/5.其他/6.机器人/7.玩家充值
     */
    const RECHARGE_WEB = 1;

    const RECHARGE_GAME = 2;

    const RECHARGE_WECHAT = 3;

    const RECHARGE_ACTIVITY = 4;

    const RECHARGE_OTHER = 5;

    const RECHARGE_ROBOT = 6;

    const RECHARGE_PLAYER = 7;

    /**
     * operate_type
     * 元宝加减
     * 1.增加/2.减少
     */
    const GOLD_INCR = 1;

    const GOLD_DECR = 2;

    /**
     * props_type
     * 代币类型
     */
    const PROPS_TYPE = 3;

    /**
     * 游戏内元宝操作
     * 游戏内充值、sourceType必须为2、且订单号必须唯一、且必传money值
     *
     * @params sourceType、1、web/2、游戏内/3、微信公众号/4、活动赠送/5、其他/6、机器人/7、充值
     * @params propsType、3、元宝(现游戏内使用)/1、钻石/2、金币
     * @params count、代币数量
     * @params operateType、1、增加、2、减少
     * @params gameId、游戏id
     * @params userId、玩家id
     */
    public static function sendGold($source_type, $props_type, $count, $operate_type, $user_id, $game_id = '1114112', $remark = '', $order_id = '', $money = '')
    {
        $recharge_url = Yii::$app->params['recharge_Url'];
        $data = [
            'sourceType' => $source_type,
            'propsType' => $props_type,
            'count' => $count,
            'operateType' => $operate_type,
            'userId' => $user_id,
            'gameId' => $game_id,
            'remark' => $remark,
        ];
        if ($source_type == 2) {
            $data['orderId'] = $order_id;
            $data['money'] = $money;
        }

        $json_data = json_encode($data);
        $curl = new Curl();
        $response = $curl->setPostParams([
            'msg' => $json_data,
        ])
            ->post($recharge_url);
//        var_dump($recharge_url, $json_data);


        Yii::info('元宝操作' . $json_data . $response, 'application');
//        var_dump($response);

        return $response;
    }

    /**
     * 生成带二维码图片
     */
    public static function getQrImg($type = 1,$value,$file,$QR,$qrname,$errorCorrectionLevel='',$matrixPointSize='')
    {
        include '../vendor/phpqrcode/phpqrcode.php';
        $code = new \QRcode();
        $has = is_file($qrname);
        if(!$has){ //文件不存在的情况下生成一个二维码
            $errorCorrectionLevel = 'L';//容错级别
            $matrixPointSize = "6";//生成图片大小
            //生成二维码图片
            $code::png($value, $qrname, $errorCorrectionLevel, $matrixPointSize, 2);
        }
        $logo = $qrname;
        if ($logo !== FALSE){
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR) / 1.38;//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 2.2;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;

            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, 312,795,0,0,$logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

        }
        //输出图片
        if ($type == 1) {
            Header("Content-type: image/png");
            ob_clean();
            ImagePng($QR);
            exit;
        } else {
            imagepng($QR, $file);
            imagedestroy($QR);
            imagedestroy($logo);
            return $file;
        }
    }

    const OP_TYPE_MENGXIN = 1;

    const OP_TYPE_CHAPAI = 2;

    const OP_TYPE_ADD_AGENT = 3;

    const OP_TYPE_FOLLOW_RECORD = 4;

    const OP_TYPE_EDIT_RECORD = 5;

    /**
     * 通用操作日志
     */
    public static function LogOperation($username, $op_type, $op_content,$op_player_id=0)
    {
        $db = Yii::$app->db;
        $db->createCommand()->insert('log_operation', [
            'username' => $username,
            'op_type' => $op_type,
            'op_content' => $op_content,
            'op_player_id' => $op_player_id,
            'op_time' => date('Y-m-d H:i:s')
        ])->execute();
    }

    public static $payment_white_list = [
        30569527,
    ];

    /**
     * 检测充值方式白名单
     */
    public static function checkPaymentWhite($uid)
    {
        if (in_array($uid, self::$payment_white_list)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 求顶级代理
     * @return  -1/散户、parent_id/顶级ID
     */
    public static function top($player_id)
    {
        $redis = Yii::$app->redis;
        $parent = $redis->zscore(RedisKey::INF_AGENT_RELATION, $player_id);

        if (empty($parent)) {
            return -1;
        }

        if ($parent == '999') {
            return $player_id;
        } else {
            return self::top($parent);
        }
    }

    /**
     * 生成xml格式
     */
    public static function buildXml($data, $wrap = 'xml', $params = '')
    {
        $str = "<{$wrap}>";
        if (is_array($data)) {
            if (self::hasIndex($data)) {
                foreach ($data as $k => $v) {
                    $str .= self::buildXml($v, $k);
                }
            } else {
                foreach ($data as $v) {
                    foreach ($v as $k1 => $v1) {
                        $str .= self::buildXml($v1, $k1);
                    }
                }
            }
        } else {
            $str .= $data;
        }

	$str .= "</{$wrap}>";
	return str_replace("</xml>", '', str_replace("<xml>", '<?xml version="1.0" encoding="utf-8"?>', $str));
    }

    public static function hasIndex($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * RSA私钥签名
     */
    public static function genSign($toSign, $privateKey)
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

    /**
     * RSA公钥验证
     */
    public static function verifySign($data, $sign, $pubKey)
    {
        $sign = base64_decode($sign);

        $pubKey = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($pubKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";

        $key = openssl_pkey_get_public($pubKey);
        $result = openssl_verify($data, $sign, $key, OPENSSL_ALGO_SHA1) === 1;
        return $result;
    }
}
