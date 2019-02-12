<?php
/**
 * User: SeaReef
 * Date: 2018/12/5 17:53
 *
 * 光大渠道支付回调
 */
namespace app\controllers\api;

use app\common\Code;
use Yii;
use yii\db\Query;

class NotifyGuangdaController extends NotifyBaseController
{
    /**
     * 初始化参数
     */
    protected function initParams()
    {
        $request = Yii::$app->request;

        $this->appid = $this->getAppid($request->get('orderNo'));
        $this->cp_oid = $request->get('orderNo');
        $this->money = $request->get('orderPrice');
        $this->channel_oid = $request->get('trxNo');
        $this->sign = $request->get('sign');
        $this->real_money = $request->get('orderPrice');
        $this->pay_time = date('Y-m-d H:i:s');

        if ($request->get('tradeStatus') == 'SUCCESS') {
            $this->status = 1;
        }

        $this->params = $request->get();
    }

    private function getAppid($order_id)
    {
        $pay_channel = (new Query())
            ->select('pay_channel')
            ->from('t_order')
            ->where(['order_id' => $order_id])
            ->scalar();
        return (new Query())
            ->select('appid')
            ->from('conf_pay_channel')
            ->where(['channel_code' => $pay_channel])
            ->scalar();
    }

    /**
     * 验证返回值
     */
    protected function verifyReturn()
    {
        if ($this->status == 1) {
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
        $channel_info = $this->channelInfo($this->appid);

        //拼装加密串
        $argPreSign = "field1=".$data['field1'];  //扩展字段
        $argPreSign .= "&field2=".$data['field2']; //扩展字段
        $argPreSign .= "&field3=".$data['field3']; //扩展字段
        $argPreSign .= "&field4=".$data['field4']; //扩展字段
        $argPreSign .= "&field5=".$data['field5']; //扩展字段
        $argPreSign .= "&orderDate=".$data['orderDate']; //订单日期
        $argPreSign .= "&orderNo=".$data['orderNo'];  //订单号
        $argPreSign .= "&orderPrice=".$data['orderPrice'];   //订单金额
        $argPreSign .= "&orderTime=".$data['orderTime'];//下单时间
        $argPreSign .= "&payKey=".$channel_info['appid'];  //商户key
        $argPreSign .= "&payWayCode=".$data['payWayCode'];  //ALIPAY
        $argPreSign .= "&productName=".$data['productName'];  //商品名
        $argPreSign .= "&remark=".$data['remark'];  //备注
        $argPreSign .= "&tradeStatus=".$data['tradeStatus']; //状态 SUCCESS
        $argPreSign .= "&trxNo=".$data['trxNo'];  //平台交易号
        $argPreSign .= "&paySecret=".$channel_info['appkey'];  //商户密钥

        if (strtoupper(md5($argPreSign)) == $this->sign) {
            return true;
        } else {
            $this->freedOrder();
            $this->writeResult(Code::CODE_ORDER_SIGN_ERROR);
        }
    }

    /**
     * 支付渠道返回值
     */
    protected function noticeChannel($flag)
    {
        if ($flag) {
            $this->writeJson([
                'errno' => 0,
                'content' => 'success',
            ]);
        } else {
            $this->writeJson([
                'errno' => 1,
                'content' => 'error',
            ]);
        }
    }
}