<?php
/**
 * User: SeaReef
 * Date: 2018/12/5 17:53
 *
 * 一麻袋支付
 */
namespace app\controllers\api;

use app\common\Code;
use Yii;
use yii\db\Query;
use app\common\Tool;

class NotifyEcpssController extends NotifyBaseController

{
    /**
     * 初始化参数
     */
    protected function initParams()
    {
        $request = Yii::$app->request;

        $this->appid = $request->post('MerNo');
        $this->cp_oid = $request->post('BillNo');
        $this->money = $request->post('Amount');
        $this->channel_oid = $request->post('OrderNo');
        $this->sign = $request->post('SignInfo');
        $this->real_money = $request->post('Amount');
        $this->pay_time = date('Y-m-d H:i:s');

        if ($request->post('Succed') == '88') {
            $this->status = 1;
        }

        $this->params = $request->post();
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
        $argPreSign = "MerNo=".$data['MerNo'];  //扩展字段
        $argPreSign .= "&BillNo=".$data['BillNo']; //扩展字段
        $argPreSign .= "&OrderNo=".$data['OrderNo']; //扩展字段
        $argPreSign .= "&Amount=".$data['Amount']; //扩展字段
        $argPreSign .= "&Succeed=".$data['Succeed']; //扩展字段

        $res = Tool::verifySign($argPreSign, $this->sign, RechargeController::ecpss_info($this->appid)['public_key']);
        return $res;
    }

    /**
     * 支付渠道返回值
     */
    protected function noticeChannel($flag)
    {
        if ($flag) {
            echo 'ok';
        } else {
            echo 'fail';
        }
    }
}