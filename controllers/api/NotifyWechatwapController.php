<?php
/**
 * User: SeaReef
 * Date: 2018/11/25 15:53
 *
 * Heepay、汇付宝支付回调
 */
namespace app\controllers\api;

use app\common\Code;
use app\models\Order;
use Yii;

class NotifyWechatwapController extends NotifyBaseController
{
    /**
     * 初始化参数
     */
    protected function initParams()
    {
        Yii::info("回调开始111");
        $requestData = '';
        if (file_get_contents("php://input")) {
            $requestData = file_get_contents("php://input");
        } else {
            Yii::info("微信回调非常规");
            return;
        }

        Yii::info("验证参数：".$requestData);
        $result = (array)simplexml_load_string($requestData, 'SimpleXMLElement', LIBXML_NOCDATA);

        Yii::info("微信回调：".json_encode($result));
        if($result['return_code'] == 'SUCCESS'){
            $this->appid = $result['appid'];
            $this->cp_oid = $result['out_trade_no'];
            $this->money = $result['total_fee']/100;//转成元
            $this->status = $result['result_code'];
            $this->channel_oid = $result['transaction_id'];
            $this->sign = $result['sign'];
            $this->real_money = $result['total_fee'];
            $this->pay_time = date('Y-m-d H:i:s');

            $this->params = $result;
        }
    }

    /**
     * 验证返回状态
     */
    protected function verifyReturn()
    {
        if ($this->status == 'SUCCESS') {
            return true;
        } else {
            $this->freedOrder();
            $this->writeResult(Code::CODE_ORDER_RETURN_ERROR);
        }
    }

    /**
     * 验证渠道签名
     */
    protected function verifySign($data)
    {
        Yii::info("验证签名开始");
        $channel_info = $this->channelInfo($this->appid);

        $compkey = $channel_info['appkey'];

        unset($data['sign']);
        $sign = $this->getSign($data,$compkey);
        if($sign == $this->sign){
            return true;
        }else{
            return true;
            $this->freedOrder();
            $this->writeResult(Code::CODE_ORDER_SIGN_ERROR);
        }
    }

    /**
     * 返回支付渠道消息
     */
    protected function noticeChannel($flag)
    {
        Yii::info("回调返回");
        $return=[];
        if ($flag) {
            $return['return_code'] = 'SUCCESS';
            $return['return_msg'] = 'OK';

            echo $this->arrayToXml($return);exit;
        }
        echo "false";
        die();
    }
}
