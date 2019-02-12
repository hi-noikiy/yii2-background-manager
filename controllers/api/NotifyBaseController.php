<?php
/**
 * User: SeaReef
 * Date: 2018/9/26 20:55
 *
 * 回调发货父类
 *
 * 需要子类重写的方法
 * initParams()
 *
 */
namespace app\controllers\api;

use app\common\Code;
use app\common\Tool;
use app\models\Activity;
use app\models\LogUserActivity;
use app\models\Order;
use Yii;
use app\controllers\BaseController;
use yii\db\Query;

class NotifyBaseController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * 应用id
     */
    protected $appid;

    /**
     * cp订单号
     */
    protected $cp_oid;

    /**
     * 订单金额
     */
    protected $money;

    /**
     * 支付结果
     */
    protected $status;

    /**
     * 渠道订单号
     */
    protected $channel_oid;

    /**
     * 签名
     */
    protected $sign;

    /**
     * 实际金额
     */
    protected $real_money;

    /**
     * 渠道返回时间
     */
    protected $pay_time;

    /**
     * 所有参数
     */
    protected $params;



    /**
     * redis锁
     */
    private $lock_key;


    public function init()
    {
        Yii::info(print_r($_REQUEST, 1), '充值回调');
//        file_put_contents('/tmp/notify_params.log', print_r($_REQUEST, 1), FILE_APPEND);

//        1、初始化参数
        $this->initParams();

        $this->actionIndex();
    }

    /**
     * 初始化参数、子类继承重写、以竣付通为示例描述
     * 子类必须重写
     */
    protected function initParams()
    {
        $request = Yii::$app->request;
//        $this->appid = $request->post('p1__yingyongnum');
//        $this->cp_oid = $request->post('p2_ordernumber');
//        $this->money = $request->post('p3_money');
//        $this->status = $request->post('status');
//        $this->channel_oid = $request->post('p5_orderi');
//        $this->sign = $request->post('p9_signtyp');
//        $this->real_money = $request->post('p13_zfmone');
//        $this->pay_time = $request->post('pay_time') ? : date('Y-m-d H:i:s', time());

        $this->params = $request->get();
    }

    /**
     * 设置redis锁
     */
    private function lockOrder()
    {
        $this->lock_key = '__ORDER_KEY_' . $this->cp_oid;
        $redis = Yii::$app->redis;
        if ($redis->get($this->lock_key)) {
            $this->writeResult(Code::CODE_ORDER_PROCCESS);
        } else {
            $redis->set($this->lock_key, 1);
            $redis->expire($this->lock_key, 3);
        }
    }

    /**
     * 释放redis锁
     */
    protected function freedOrder()
    {
        $redis = Yii::$app->redis;
        $redis->pexpire($this->lock_key, 1);
    }

    protected function actionIndex()
    {
//        2、添加redis锁
        $this->lockOrder();

//        3、验证返回状态
        $this->verifyReturn();

//        4、验证返回签名
        $this->verifySign($this->params);

//        5、验证订单状态
        $this->verifyOrder();
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
     * 验证签名
     */
    protected function verifySign($data)
    {
        $channel_info = $this->channelInfo($this->appid);
        $make_sign = strtoupper(md5($data['p1_yingyongnum'] . '&' . $data['p2_ordernumber'] . '&' . $data['p3_money'] . '&' . $data['p4_zfstate'] . '&' . $data['p5_orderid'] . '&' . $data['p6_productcode'] . '&' . $data['p7_bank_card_code'] . '&' . $data['p8_charset'] . '&' . $data['p9_signtype'] . '&' . $data['p11_pdesc'] . '&' . $data['p13_zfmoney'] . $channel_info['appkey']));

        if ($make_sign == $this->sign) {
            return true;
        } else {
            $this->freedOrder();
            $this->writeResult(Code::CODE_ORDER_SIGN_ERROR);
        }
    }

    /**
     * 获取渠道信息
     */
    protected function channelInfo($appid)
    {
        return (new Query())
            ->select('*')
            ->from('conf_pay_channel')
            ->where(['appid' => $appid])
            ->one();
    }

    /**
     * 验证订单信息
     */
    private function verifyOrder()
    {
        Yii::info("验证订单开始");
        $order = Order::findOne(['order_id' => $this->cp_oid]);
//        验证订单是否存在
        if (!$order) {
            $this->freedOrder();
            $this->writeResult(Code::CODE_ORDER_NOT_FOUND);
        }
        Yii::info("验证订单存在".$this->cp_oid);
//        验证订单完成状态
        if ($order->status == Order::ORDER_FINISHED) {
            $this->freedOrder();
            $this->writeResult(Code::CODE_ORDER_FINISHED);
        }
        Yii::info("验证订单完成状态".$this->cp_oid);

//        验证订单金额
        if ($this->money != $order->goods_price) {
            $this->freedOrder();
            $this->writeResult(Code::CODE_ORDER_MONEY_ERROR);
        }
        Yii::info("验证订单金额".$this->cp_oid);

        $transaction = Order::getDb()->beginTransaction();
        $t = date('Y-m-d H:i:s', time());
        Yii::info("验证订单完成".$this->cp_oid);
        try {
            $order->channel_oid = $this->channel_oid;
            $order->status = 1;
            $order->pay_time = $t;
            $order->finish_time = $t;

//            发货
            $res = Tool::sendGold(Tool::RECHARGE_PLAYER, Tool::PROPS_TYPE, $order->goods_num, Tool::GOLD_INCR, $order->player_id);

            if (!$res) {
                $flag = 0;
                $this->freedOrder();
                $transaction->rollBack();
            } else {
                $res = $order->save();

//                验证活动信息、object $order
                $this->checkActivity($order);
                $flag = 1;
                $transaction->commit();
            }

        } catch (\Exception $e) {
            $this->freedOrder();
            $transaction->rollBack();
            $flag = 0;
        }

        $this->noticeChannel($flag);
    }

    /**
     * 检测活动记录日志
     */
    private function checkActivity($order)
    {
        $activity = (new Query())
            ->select('*')
            ->from('conf_recharge')
            ->where(['id' => $order->goods_id])
            ->one();
//        var_dump($activity);

        if ($activity['is_activity']) {
            $db = Yii::$app->db;
            $t = date('Y-m-d H:i:s');
            $db->createCommand()->insert('log_user_activity', [
                'player_id' => $order->player_id,
                'activity_id' => $activity['activity_id'],
                'operate_type' => LogUserActivity::OPERATE_TYPE_RECEIVE,
                'is_operate' => LogUserActivity::OPERATE_FINISHED,
                'operate_count' => 1,
                'operate_time' => $t,
                'last_operate' => $t,
            ])->execute();
        }
    }

    /**
     * 通知渠道信息
     */
    protected function noticeChannel($flag)
    {
        if ($flag) {
            echo 'success';
        } else {
            echo 'error';
        }
    }

    /**
     * 数组转xml
     *
     * @param $arr
     * @return string
     */
    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }
}