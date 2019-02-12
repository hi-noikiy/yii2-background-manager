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

class NotifyJpayController extends NotifyBaseController
{
    /**
     * 初始化参数
     */
    protected function initParams()
    {
        $request = Yii::$app->request;

        $this->appid = $request->post('p1_yingyongnum');
        $this->cp_oid = $request->post('p2_ordernumber');
        $this->money = $request->post('p3_money');
        $this->status = $request->post('p4_zfstate');
        $this->channel_oid = $request->post('p5_orderid');
        $this->sign = $request->post('p10_sign');
        $this->real_money = $request->post('p13_zfmoney');
        $this->pay_time = date('Y-m-d H:i:s');

        $this->params = $request->post();
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
        $compkey = $channel_info['appkey'];
        $p1_yingyongnum = $_REQUEST['p1_yingyongnum'];
        $p2_ordernumber  = $_REQUEST['p2_ordernumber'];
        $p3_money           = $_REQUEST['p3_money'];
        $p4_zfstate           = $_REQUEST['p4_zfstate'];
        $p5_orderid          = $_REQUEST['p5_orderid'];
        $p6_productcode  = $_REQUEST['p6_productcode'];
        $p7_bank_card_code= empty($_REQUEST['p7_bank_card_code']) ?  '' : $_REQUEST['p7_bank_card_code'];
        $p8_charset         = $_REQUEST['p8_charset'];
        $p9_signtype       = $_REQUEST['p9_signtype'];
        $p10_sign            = $_REQUEST['p10_sign'];
        $p11_pdesc         = empty($_REQUEST['p11_pdesc']) ? '' : $_REQUEST['p11_desc'];
        $presign = $p1_yingyongnum."&".$p2_ordernumber."&".$p3_money."&".$p4_zfstate."&".$p5_orderid."&".$p6_productcode."&".$p7_bank_card_code."&".$p8_charset."&".$p9_signtype."&".$p11_pdesc."&".$compkey;
        // echo $presign."<br/>";
        $sign =strtoupper(md5($presign));
        if ($sign == $_REQUEST['p10_sign']&&$_REQUEST['p4_zfstate'] == "1"){
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
            echo 'success';
        } else {
            echo 'error';
        }
        die();
    }
}
