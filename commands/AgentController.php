<?php
/**
 * User: SeaReef
 * Date: 2018/9/20 20:15
 *
 * 代理相关定时任务
 */
namespace app\commands;

use app\common\helpers\Sms;
use app\models\RechargeConf;
use Yii;
use yii\console\Controller;
use yii\db\Query;

class AgentController extends Controller
{
    /**
     * 准备支付状态
     */
    const READY_TO_PAY_STATUS = 0;

    /**
     * 支付成功
     */
    const SUCCESS_PAY_STATUS = 1;
    const SUCCESS_PAY_MESSAGE = '支付成功';

    /**
     * 支付失败，待重查
     */
    const RECHECK_PAY_STATUS = 2;
    const RECHECK_PAY_MESSAGE = '支付失败 待重新查询';

    /**
     * 支付失败
     */
    const FALSE_PAY_STATUS = 3;
    const FALSE_PAY_MESSAGE = '支付失败 解除冻结金额';

    /**
     * 支付成功 但更新订单状态失败；
     */
    const ERROR_FALSE_UPDATE_ORDER_STATUS = 4;
    const ERROR_FALSE_UPDATE_ORDER_MESSAGE = '支付成功 但更新订单状态失败';

    /**
     * 支付成功  但减少用户冻结金额失败;
     */
    const ERROR_FALSE_MINUS_FORZEN_MONEY = 5;
    const ERROR_FALSE_MINUS_FORZEN_MESSAGE = '支付成功  但减少用户冻结金额失败';

    /**
     * 转账失败 未成功，todo 具体处理待定
     */
    const TRANSFER_FALSE_MINUS_FORZEN_MONEY = 6;
    const TRANSFER_FALSE_MINUS_FORZEN_MESSAGE = '转账失败 未成功';

    /**
     * 处理中订单
     */
    const PROCESSING_FALSE_MINUS_FORZEN_MONEY = 7;
    const PROCESSING_FALSE_MINUS_FORZEN_MESSAGE = '处理中订单 需要重试';

    /**
     * 余额不足 单独处理
     */
    const NOTENOUGH_FALSE_MINUS_FORZEN_MONEY = 999;
    const NOTENOUGH_FALSE_MINUS_FORZEN_MSEEAGE = '余额不足 单独处理 人工跟进中';

    /**
     * 代理付款redisKey
     */
    const PAY_DAILI_MONEY = 'pay_daili_money';

    /**
     * 支付安全秘钥
     */
    const API_KEY = '7293edb63c09a81dbc6b2f6e3aacd9fc';

    /**
     * 企业付款接口地址
     */
    const RECHARGE_API = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';

    /**
     * 查看订单状态
     */
    const SEE_ORDER_API = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo';

    /**
     * 商户appid
     */
    const MCH_APPID = 'wx5223c60abfaaf719';

    /**
     * 商户号
     */
    const MCHID = '1515631881';

    /**
     * 失败提现队列redisKey
     */
    const FAIL_PAY_DAILI_MONEY = 'fail_pay_daili_money';


    /**
     * 每分钟读取数量
     */
    const READ_COUNT = 100;

    /**
     * 代理提现
     * 付款定时任务
     */
    public function actionPayDailiMoney() 
    {
//        if (!Yii::$app->params['pay_button_switch']) {
//            file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."提现定时任务接口已关闭",FILE_APPEND);
//            return false;
//        } else {
            $redis = Yii::$app->redis;
            for ($i = 0; $i < self::READ_COUNT; $i++) {
                $payInfo = $redis->rpop(self::PAY_DAILI_MONEY);
                if (!$payInfo) {
                    break;
                }
                $this->curl_post_ssl(self::RECHARGE_API,$payInfo);
            }
        //}
    }

    /**
     *   作用：使用证书，以post方式提交xml到对应的接口url
     *
     */
    private function curl_post_ssl($url, $vars, $second = 30)
    {
//        if (!Yii::$app->params['pay_button_switch']) {
//            file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."提现微信接口已关闭",FILE_APPEND);
//            return false;
//        } else {
            $vars = json_decode($vars,1);
            $vars_ = $this->arrayToXml($vars);
            $ch = curl_init();
            //超时时间　　
            curl_setopt($ch, CURLOPT_TIMEOUT, $second);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            /******* 此处必须为文件服务器根目录绝对路径 不可使用变量代替*********/
            curl_setopt($ch, CURLOPT_SSLKEY, "/data/wwwroot/yiquan/credential/apiclient_key.pem");
            curl_setopt($ch, CURLOPT_SSLCERT, "/data/wwwroot/yiquan/credential/apiclient_cert.pem");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $vars_);
            $data = curl_exec($ch);
            if ($data) {//通信成功
                $result = (array)simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);

                file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL.'微信返回结果：curl_data:'.print_r($data,1),FILE_APPEND);
                $db = Yii::$app->db;
                if ($result['return_code'] == 'SUCCESS') {//通信成功
                    if ($result['result_code'] == 'SUCCESS') {//成功更改订单状态
                        $payInfo = $this->getOrderDetail($result['partner_trade_no']);
                        file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL.'提现微信返回成功：订单信息:'.print_r($payInfo,1),FILE_APPEND);

                        $re_1 = $this->updatePayOrderStatus($result['partner_trade_no'],self::SUCCESS_PAY_STATUS,self::SUCCESS_PAY_MESSAGE);

                        file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL.'更新订单状态结果：'.$re_1,FILE_APPEND);

                        $re_2 = $this->updateFrozenMoney($payInfo['PAY_MONEY'],$payInfo['PLAYER_INDEX'],1);

                        file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL.'减去冻结金额结果：'.$re_2,FILE_APPEND);
                        if (!$re_1 || !$re_2) {//ERROR_FALSE_MINUS_FORZEN_MONEY
                            $re_1?null:$this->updatePayOrderStatus($result['partner_trade_no'],self::ERROR_FALSE_UPDATE_ORDER_STATUS,self::ERROR_FALSE_UPDATE_ORDER_MESSAGE);
                            $re_2?null:$this->updatePayOrderStatus($result['partner_trade_no'],self::ERROR_FALSE_MINUS_FORZEN_MONEY,self::ERROR_FALSE_MINUS_FORZEN_MESSAGE);

                        }
                    } else if ($result['err_code'] == 'NOTENOUGH') {//余额不足
                        file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL.'商户号余额不足',FILE_APPEND);
                        $this->updatePayOrderStatus($vars['partner_trade_no'],self::NOTENOUGH_FALSE_MINUS_FORZEN_MONEY,self::NOTENOUGH_FALSE_MINUS_FORZEN_MSEEAGE);
                        Sms::send('17610992185','提现账户余额不足,程序已自动退出【点动科技】');
                    } else {//业务结果未明确,查询订单状态
                        file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL.'提现订单状态未明确，加入失败队列重新查询',FILE_APPEND);
                        $this->updatePayOrderStatus($vars['partner_trade_no'],self::RECHECK_PAY_STATUS,self::RECHECK_PAY_MESSAGE);
                        $this->addFailPayMoneyList($vars['partner_trade_no']);
                    }
                } else {//通信失败
                    file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL.'curl_data_3:'.print_r($data,1),FILE_APPEND);
                }
                curl_close($ch);
                return $data;
            } else {
                $error = curl_errno($ch);
                echo "call faild, errorCode:$error\n";
                curl_close($ch);
                return false;
            }
        //}

    }

    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            /*if (is_numeric($val)) {
                $xml .= "<".$key.">".$val."</".$key.">";
            } else {
                $xml .= "<".$key."><![CDATA[".$val."]]></".$key.">";
            }*/
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 失败提现订单定时任务
     */
    public function actionDealDailiFailOrder()
    {
//        if (!Yii::$app->params['pay_button_switch']) {
//            file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."重查提现状态定时任务接口已关闭",FILE_APPEND);
//            return false;
//        } else {
            $redis = Yii::$app->redis;
            for ($i = 0; $i < self::READ_COUNT; $i++) {
                $order_id = $redis->rpop(self::FAIL_PAY_DAILI_MONEY);
                if (!$order_id) {
                    break;
                }
                $info = $this->getOrderDetail($order_id);
                if ($info['PAY_STATUS'] == self::SUCCESS_PAY_STATUS) {
                    continue;
                }
                $this->dealFailOrder($order_id);
            }
        //}
    }


    /**
     * 订单失败处理
     */
    private function dealFailOrder($order_id)
    {
        $checkResult = $this->checkOrder($order_id);
        $checkResult = (array)simplexml_load_string($checkResult, 'SimpleXMLElement', LIBXML_NOCDATA);
        file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL.'check_order_result:'.print_r($checkResult,1),FILE_APPEND);
        if ($checkResult['return_code'] == 'SUCCESS') {
            if ($checkResult['result_code'] == 'SUCCESS') {
                if ($checkResult['status'] == 'SUCCESS') {//更新订单状态和冻结金额
                    $this->updateOrderStatusMinusFrozenMoney($order_id);
                } else if ($checkResult['status'] == 'FAILED') {//支付失败，解除冻结金额，可提现金额，金额恢复后更改订单状态
                    $this->updatePayOrderStatus($order_id,self::TRANSFER_FALSE_MINUS_FORZEN_MONEY,self::TRANSFER_FALSE_MINUS_FORZEN_MESSAGE);
                    $this->rollbackForzenMoneyToUser($order_id)?$this->updatePayOrderStatus($order_id,self::FALSE_PAY_STATUS,self::FALSE_PAY_MESSAGE):null;

                } else if ($checkResult['status'] == 'PROCESSING') {//处理中
                    $this->updatePayOrderStatus($order_id, self::PROCESSING_FALSE_MINUS_FORZEN_MONEY, self::PROCESSING_FALSE_MINUS_FORZEN_MESSAGE);
                    $this->addFailPayMoneyList($order_id);
                }
            } else if ($checkResult['result_code'] == 'FAIL') {
                if ($checkResult['err_code'] == 'SYSTEMERROR') {
                    $this->updatePayOrderStatus($order_id, self::FALSE_PAY_STATUS, $checkResult['err_code_des']);
                    $this->addFailPayMoneyList($order_id);
                } else {
                    $this->updatePayOrderStatus($order_id, self::FALSE_PAY_STATUS, $checkResult['err_code_des']);
                    $this->rollbackForzenMoneyToUser($order_id)?$this->updatePayOrderStatus($order_id,self::FALSE_PAY_STATUS,self::FALSE_PAY_MESSAGE):null;
                }
            } else {
                \Yii::error($checkResult, '重查结果状态未知');
                Sms::send(17610992185, '警告： 发现订单重查结果状态未知,订单id: ' . $order_id);
            }
        }
    }

    /**
     * 确定订单支付状态接口
     */
    private function checkOrder($order_id, $second = 30)
    {
//        if (!Yii::$app->params['pay_button_switch']) {
//            file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."提现重查订单微信接口已关闭",FILE_APPEND);
//            return false;
//        } else {
            $new_data = [
                'partner_trade_no' => $order_id,
                'mch_id' => self::MCHID,
                'appid' => self::MCH_APPID,
                'nonce_str' => 'diandongkeji' . mt_rand(100, 999)
            ];
            //生成秘钥
            $str = 'appid=' . $new_data["appid"] . '&mch_id=' . $new_data["mch_id"] . '&nonce_str=' . $new_data["nonce_str"] . '&partner_trade_no=' . $new_data["partner_trade_no"] . '&key='.self::API_KEY;
            //md5加密 转换成大写
            $sign = strtoupper(md5($str));
            $new_data["sign"] = $sign;//签名
            file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL.'check_order_data:'.print_r($new_data,1),FILE_APPEND);
            //$new_data = json_decode($new_data,1);
            $new_data = $this->arrayToXml($new_data);
            $ch = curl_init();
            //超时时间　　
            curl_setopt($ch, CURLOPT_TIMEOUT, $second);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, self::SEE_ORDER_API);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            /******* 此处必须为文件服务器根目录绝对路径 不可使用变量代替*********/
            curl_setopt($ch, CURLOPT_SSLKEY, "/data/wwwroot/OSS/apiclient_key.pem");
            curl_setopt($ch, CURLOPT_SSLCERT, "/data/wwwroot/OSS/apiclient_cert.pem");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $new_data);
            return curl_exec($ch);
        //}

    }

    /**
     * 获取订单详情
     */
    private function getOrderDetail($order_id)
    {
         return (new Query())
            ->select('*')
            ->from('t_pay_order')
            ->where('ORDER_ID = "'.$order_id.'"')
            ->one();
    }

    /**
     * 更新冻结金额
     * type 1减金额，2加金额
     */
    private function updateFrozenMoney($frozen_money,$player_index,$type)
    {
        file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."减去冻结金额: player_index: $player_index ,frozen_money : ".$frozen_money,FILE_APPEND);
        if ($player_index) {
            if ($type == 1) {//减冻结金额
                return Yii::$app->db->createCommand("update t_daili_player set forzen_money = forzen_money-{$frozen_money} where player_id = {$player_index}")->execute();
            } else {//加冻结金额
                return Yii::$app->db->createCommand("update t_daili_player set forzen_money = forzen_money+{$frozen_money} where player_id = {$player_index}")->execute();
            }
        } else {
            return false;
        }
    }

    /**
     * 更新订单状态
     */
    private function updatePayOrderStatus($order_id,$status,$remark='',$reason='')
    {
        file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL.'更新订单状态：'.$status.','.$remark,FILE_APPEND);
        if ($order_id) {
            return Yii::$app->db->createCommand()->update('t_pay_order',['pay_status'=>$status,'update_time'=>time(),'remark'=>$remark,'api_desc'=>$reason],'order_id = "'.$order_id.'"')->execute();
        } else {
            return false;
        }

    }

    /**
     * 查询支付成功 更新订单状态，减少冻结金额
     */
    private function updateOrderStatusMinusFrozenMoney($order_id)
    {
        $order_data = $this->getOrderDetail($order_id);
        if ($order_data['PAY_STATUS'] == self::SUCCESS_PAY_STATUS) {
            //Yii::warning($order_data, '重试订单为成功订单');
            file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."重试订单为成功订单",FILE_APPEND);
            return false;
        }
        $updata_res = $this->updateFrozenMoney($order_data['PAY_MONEY'],$order_data['PLAYER_INDEX'], 1);//减少冻结金额;
        if ($updata_res) {
            file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."减少冻结金额结果：{$updata_res}",FILE_APPEND);
            $order_res = $this->updatePayOrderStatus($order_id, self::SUCCESS_PAY_STATUS, self::SUCCESS_PAY_MESSAGE);
            file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."更新订单状态结果：{$order_res}",FILE_APPEND);//更新订单状态
        } else {
            file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."减少冻结金额结果：{$updata_res}",FILE_APPEND);
            $order_res = $this->updatePayOrderStatus($order_id, self::ERROR_FALSE_MINUS_FORZEN_MONEY, self::ERROR_FALSE_MINUS_FORZEN_MESSAGE);  //更新订单状态
            file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."更新订单状态结果：{$order_res}",FILE_APPEND);//更新订单状态
        }
    }


    /**
     * 支付失败解除冻结金额,恢复可提现金额
     */
    public function rollbackForzenMoneyToUser($order_id)
    {
        $order_data = $this->getOrderDetail($order_id);
        $db = Yii::$app->db;
        $br = $db->beginTransaction();
        $for_res = $this->updateFrozenMoney($order_data['PAY_MONEY'],$order_data['PLAYER_INDEX'],1);
        file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."解除冻结金额:rollbackForzenMoneyToUser: order_id: $order_id ,解除结果 : $for_res",FILE_APPEND);

        $pay_back_res = $this->updatePayBackMoney($order_data['PAY_MONEY'],$order_data['PLAYER_INDEX']);
        file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."恢复可提现金额 :rollbackForzenMoneyToUser: order_id: $order_id ,恢复结果 : $pay_back_res",FILE_APPEND);

        $data = json_encode($order_data);
        if ($for_res && $pay_back_res) {
            file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."rollbackForzenMoneyToUser: order_id: $order_id ,order_data : ".print_r($data,1). ",result: SUCCESS",FILE_APPEND);
            $br->commit();
            $after_gold = Yii::$app->db->createCommand("select pay_back_gold from t_daili_player where player_id = {$order_data['PLAYER_INDEX']}")->queryScalar();
            file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."恢复后可提现金币 :$after_gold",FILE_APPEND);
            return true;
        }
        //Yii::info("rollbackForzenMoneyToUser: order_id: $order_id ,order_data : $data ,result: FALSE ");
        //Yii::warning( $order_id . '更新支付失败解除冻结金额');
        $br->rollBack();
        file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."恢复失败，回滚: order_id: $order_id ,order_data : ".print_r($data,1). ",result: FALSE",FILE_APPEND);
        return false;
    }

    /**
     * 更新可提现金额
     */
    private function updatePayBackMoney($frozen_money,$player_index)
    {
        $before_gold = Yii::$app->db->createCommand("select pay_back_gold from t_daili_player where player_id = {$player_index}")->queryScalar();
        file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."解冻前可提现金币: ".$before_gold,FILE_APPEND);
        file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL."updatePayBackMoney: player_index: $player_index ,frozen_money : ".$frozen_money,FILE_APPEND);
        if ($player_index) {
            return Yii::$app->db->createCommand("update t_daili_player set pay_back_gold = pay_back_gold+{$frozen_money} where player_id = {$player_index}")->execute();
        } else {
            return false;
        }
    }

    private function addFailPayMoneyList($order_id)
    {
        file_put_contents('/tmp/wx_pay.log',PHP_EOL.date('Y-m-d H:i:s',time()).PHP_EOL.'order_id:'.$order_id.',加入失败队列',FILE_APPEND);
        Yii::$app->redis->lpush(self::FAIL_PAY_DAILI_MONEY,$order_id);

    }


}