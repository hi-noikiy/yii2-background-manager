<?php
/**
 * User: SeaReef
 * Date: 2018/11/25 15:53
 *
 * Heepay、汇付宝支付回调
 */
namespace app\controllers\api;

use app\common\Code;
use Yii;

class NotifyHeepayController extends NotifyBaseController
{
    /**
     * 初始化参数
     */
    protected function initParams()
    {
        $request = Yii::$app->request;

        $this->appid = $request->get('agent_id');
        $this->cp_oid = $request->get('agent_bill_id');
        $this->money = $request->get('pay_amt');
        $this->status = $request->get('result');
        $this->channel_oid = $request->get('jnet_bill_no');
        $this->sign = $request->get('sign');
        $this->real_money = $request->get('pay_amt');
        $this->pay_time = date('Y-m-d H:i:s');

        $this->params = $request->get();
    }

    /**
     * 验证返回状态
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
        $make_sign = md5('result=' . $this->status . '&agent_id=' . $data['agent_id'] . '&jnet_bill_no=' . $data['jnet_bill_no'] . '&agent_bill_id=' . $data['agent_bill_id'] . '&pay_type=' . $data['pay_type'] . '&pay_amt=' . $data['pay_amt'] . '&remark=' . $data['remark'] . '&key=' . $channel_info['appkey']);

        if ($make_sign == $this->sign) {
            return true;
        } else {
            $this->freedOrder();
            $this->writeResult(Code::CODE_ORDER_SIGN_ERROR);
        }
    }

    /**
     * 返回支付渠道消息
     */
    protected function noticeChannel($flag)
    {
        if ($flag) {
            echo 'ok';
        } else {
            echo 'error';
        }
        die();
    }
}
