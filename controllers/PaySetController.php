<?php
/**
 * User: SeaReef
 * Date: 2018/7/30 13:59
 *
 * 支付设置
 */
namespace app\controllers;

use app\common\Code;
use app\controllers\BaseController;
use app\models\PayChannel;
use Yii;
use yii\db\Query;

class PaySetController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    const RECHARGE_BLACK_LIST = 'recharge_black_list';

    /**
     * 充值黑名单
     */
    public function actionPayBlack()
    {
//        if (Yii::$app->request->isPost) {
//            $field = Yii::$app->request->post('field', 'reg_time');
//            $order = Yii::$app->request->post('order', 'DESC');
//
//            $redis = Yii::$app->redis_3;
//            $uid = $redis->smembers(self::RECHARGE_BLACK_LIST);
//            $count = $redis->scard(self::RECHARGE_BLACK_LIST);
//            $data = (new Query())
//                ->select(['weixin_open_id', 'nickname' => 'weixin_nickname', 'uid' => 'u_id', 'reg_time', 'last_login_time'])
//                ->from('login_db.t_lobby_player')
//                ->where(['in', 'u_id', $uid])
//                ->orderBy("$field $order")
//                ->all();
//
//            $this->writeLayui(self::CODE_LAYUI_OK, '', $count, $data);
//        } else {
//            return $this->render('pay_black');
//        }

        return $this->render('pay_black1');
    }

    public function actionPayBlackList()
    {
        $request = Yii::$app->request;
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);

        $count = (new Query())
            ->from('pay_black')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->count();
        $data = (new Query())
            ->select('*')
            ->from('pay_black')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();

        echo json_encode([
            'code' => 0,
            'msg' => '',
            'count' => $count,
            'data' => $data,
        ]);
    }

    /**
     * 删除黑名单
     */
    public function actionPayBlackDel()
    {
        $request = Yii::$app->request;
        $uid = $request->post('uid');
        $redis = Yii::$app->redis_3;

        $info = $redis->srem(self::RECHARGE_BLACK_LIST, $uid);
        if ($info) {
            $this->writeResult(Code::CODE_LAYUI_OK);
        }
    }

    /**
     * 添加黑名单
     */
    public function actionPayBlackAdd()
    {

    }

    /**
     * 充值白名单
     */
    public function actionPayWhite()
    {

    }

    /**
     * 充值宕机报警
     */
    public function actionPayAlert()
    {

    }

    /**
     * 充值渠道参数设置
     */
    public function actionPayChannel()
    {
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        $rows = (new Query())
            ->select('*')
            ->from('pay_channel')
            ->where('id>0')
            ->orderBy('id')
            ->limit($limit)
            ->offset(($page-1)*$limit)
            ->all();
        $count = (new Query())
            ->select('*')
            ->from('pay_channel')
            ->where('id>0')
            ->count();
        return $this->writeLayui(0,'',$count,$rows?$rows:[]);
    }

    /**
     * 充值渠道参数修改
     */
    public function actionPayChannelSet()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $model = PayChannel::findOne($request['id']);
        } else {
            $model = new PayChannel();
        }
        if($model->load($request,'') && $model->save()){
            return $this->writeResult(self::CODE_OK);
        } else {
            return $this->writeResult(self::CODE_ERROR);
        }
    }

    /**
     * 充值渠道参数删除
     */
    public function actionPayChannelDel()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $model = PayChannel::findOne($request['id']);
            $result = $model->delete();
            if($result){
                return $this->writeResult(self::CODE_OK);
            } else {
                return $this->writeResult(self::CODE_ERROR);
            }
        } else {
            return $this->writeResult(self::CODE_PARAMS_ERROR);
        }
    }

    /**
     * 微信充值渠道
     */
    public function actionWxChannel()
    {

    }

    /**
     * 支付宝充值渠道
     */
    public function actionZfbChannel()
    {

    }

    /**
     * 充值限额页面
     */
    public function actionPayLimitIndex()
    {
        $redis = Yii::$app->redis;
        $result = $redis->hgetall(Yii::$app->params['redisKeys']['money_client_config']);
        foreach ($result as $key=>$val) {
            if ($key%2 == 0) {
                $data[$val] = $result[$key+1];
            }
        }
        return $this->render('pay_limit',isset($data)?$data:[]);
    }

    /**
     * 充值限额
     */
    public function actionPayLimit()
    {
        $request = Yii::$app->request->post();
        $redis = Yii::$app->redis;
        foreach ($request as $key => $val) {
            $result = $redis->hset(Yii::$app->params['redisKeys']['money_client_config'],$key,$val);
            return $this->writeResult(self::CODE_OK);

        }
    }

    /**
     * 充值方式页面
     */
    public function actionPayModeIndex()
    {
        return $this->render('pay_type');
    }

    public function actionPayModeList()
    {
        $page = Yii::$app->request->get('pege',1);
        $limit = Yii::$app->request->get('limit',10);
        $rows = (new Query())
            ->select('*')
            ->from('t_payment')
            ->where('id > 0')
            ->orderBy('id asc')
            ->limit(10)
            ->all();
        $count = (new Query())
            ->select('id')
            ->from('t_payment')
            ->where('id > 0')
            ->count();
        return $this->writeLayui(0,'',$count,$rows);
    }

    public function actionPayMode()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $result = Yii::$app->db->createCommand()->update('t_payment',['status'=>$request['status']],'id = '.$request['id'])->execute();
            if ($result) {
                return $this->writeResult(self::CODE_OK);
            }
        }
        return $this->writeResult(self::CODE_ERROR);
    }

}