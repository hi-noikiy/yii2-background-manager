<?php
/**
 * User: SeaReef
 * Date: 2018/7/19 19:27
 *
 * 支付中心
 */
namespace app\controllers;

use Yii;
use yii\base\Curl;
use yii\db\Query;

class PaymentCenterController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 页面功能
     * 使用有序集合进行存放数据
     */
    public function actionCenterOrder()
    {
        if (Yii::$app->request->isPost) {
            $count = (new Query())
                ->from('payment_center.center_order')
                ->count();
            $data = (new Query())
                ->select('*')
                ->from('payment_center.center_order')
                ->all();

            $this->writeJson(self::CODE_OK, '', $count, $data);
        } else {
            return $this->render('center_order');
        }
    }

    const CENTER_PAYMENT_CONFIG_LIST = 'center_payment_conf_list';

    const CENTER_PAYMENT_CONFIG = 'center_payment_conf';

    /**
     * 添加收款账号
     * 使用redis记录账号信息、实现分页效果
     * 条数数据存放到zset里边、剩下的数据存放到hash数据中、自己封装分页类
     */
    public function actionCenterAccount()
    {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $page = $request->get('page', 1);
            $limit = $request->get('limit', 10);
            $redis = Yii::$app->redis_3;

            $count = $redis->zcard(self::CENTER_PAYMENT_CONFIG_LIST);
            $mix = ($page - 1) * $limit;
            $max = $mix + $limit;
            $ids = $redis->zrevrange(self::CENTER_PAYMENT_CONFIG_LIST, $mix, $max);
            foreach ($ids as $k => $v)
                $data[] = json_decode($redis->hget(self::CENTER_PAYMENT_CONFIG, $v), 1);
        } else {
//            $this->writeLayui(self::CODE_LAYUI_OK, '', $count, $data);
        }
    }

    /**
     *
     */
}