<?php
/**
 * User: SeaReef
 * Date: 2018/9/5 11:14
 *
 * 提现订单
 */
namespace app\controllers;

use app\common\Code;
use Yii;
use yii\db\Query;
use app\models\Order;
use yii\base\Curl;

class WithdrawController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 提现查询
     */
    public function actionCashOrder()
    {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $page = $request['page'];
            $limit = $request['limit'];
            if(isset($request['field']) && isset($request['order'])){
                $field = $request['field'];
                $orderType = $request['order'];
            }else{
                $field = 'CREATE_TIME';
                $orderType = 'desc';
            }

            $where = [];
            if (isset($request['start_time']) && $request['start_time']) {
                $where[] = 'CREATE_TIME >= '."'".strtotime($request['start_time'])."'";
            }

            if (isset($request['end_time']) && $request['end_time']) {
                $where[] = 'CREATE_TIME <= '."'".strtotime($request['end_time'])."'";
            }
            if (isset($request['PLAYER_INDEX']) && $request['PLAYER_INDEX']) {
                $where[] = 'PLAYER_INDEX = '.$request['PLAYER_INDEX'];
            }
            if (isset($request['ORDER_ID']) && $request['ORDER_ID']) {
                $where[] = 'ORDER_ID = '."'".$request['ORDER_ID']."'";
            }

            if (isset($request['PAY_STATUS']) && ($request['PAY_STATUS'] || $request['PAY_STATUS']==0) && $request['PAY_STATUS']!= '') {
                $where[] = 'PAY_STATUS = '.$request['PAY_STATUS'];
            }
            if ($where) {
                $where = implode(' and ',$where);
            }
            $data = (new Query())
                ->select('*')
                ->from('t_pay_order')
                ->where($where)
                ->andFilterWhere(['in','PLAYER_INDEX',$this->channel_under_list])
                ->orderBy($field.' '.$orderType)
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->all();

            foreach ($data as $key=>$value){
                $data[$key]['CREATE_TIME'] = date("Y-m-d H:i:s",$value['CREATE_TIME']);
                $data[$key]['UPDATE_TIME'] = date("Y-m-d H:i:s",$value['UPDATE_TIME']);
                if($value['PAY_STATUS'] == 0){
                    $data[$key]['status'] = '支付中';
                }else if($value['PAY_STATUS'] == 1){
                    $data[$key]['status'] = '成功';
                }else{
                    $data[$key]['status'] = '失败';
                }
                $data[$key]['PAY_MONEY'] = round($value['PAY_MONEY']/100,2);
            }

            $count = (new Query())
                ->select('*')
                ->from('t_pay_order')
                ->where($where)
                ->andFilterWhere(['in','PLAYER_INDEX',$this->channel_under_list])
                ->count();

            $this->writeLayui(Code::OK, 'ok', $count, $data);
        }

        return $this->render('cash_order');
    }

    /**
     * 充值查询
     */
    public function actionRechargeQuery()
    {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();

            $page = $request['page'];
            $limit = $request['limit'];
            if(isset($request['field']) && isset($request['order'])){
                $field = $request['field'];
                $orderType = $request['order'];
            }else{
                $field = 'create_time';
                $orderType = 'DESC';
            }

            $where = [];
            if (isset($request['date']) && $request['date']) {
                $where[] = 'player_create >= '."'".$request['date']."'";
            }
            if (isset($request['date']) && $request['date']) {
                $where[] = 'player_create <= '."'".$request['date']." :23:59:59'";
            }
            if (isset($request['userId']) && $request['userId']) {
                $where[] = 'player_id = '.$request['userId'];
            }
            if (isset($request['orderId']) && $request['orderId']) {
                $where[] = "order_id = '".$request['orderId']."'";
            }
            if (isset($request['channelOid']) && $request['channelOid']) {
                $where[] = 'channel_oid = "'. $request['channelOid'] . '"';
            }

            if (isset($request['payStatus']) && ($request['payStatus'] || $request['payStatus']==0) && $request['payStatus']!= '') {
                $where[] = 'status = '.$request['payStatus'];
            }
            if ($where) {
                $where = implode(' and ',$where);
            }

            $data = (new Query())
                ->select('*')
                ->from('t_order')
                ->where($where)
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->orderBy($field.' '.$orderType)
                ->all();

            $count = (new Query())
                ->select('*')
                ->from('t_order')
                ->where($where)
                ->count();

            $this->writeLayui(Code::OK, 'success', $count, $data);
        }

        return $this->render('recharge_query');
    }

    /**
     * 进行补单操作
     * @Author   WKein
     * @DateTime 2018-02-07T01:38:21+0800
     * @return   [type]                   [description]
     */
    public function actionDoFailOrder(){
        if(Yii::$app->request->isPost){
            if(Yii::$app->request->post()){
                $request = Yii::$app->request->post();
                $orderId   = $request['orderId'];
                $db = Yii::$app->db;

                $info  = $db->createCommand("SELECT * FROM lobby_daili.t_order WHERE f_order_id=".$orderId)->queryOne();
                if(!$info){
                    $this->writeResult(self::CODE_ERROR,'订单ID不存在，请联系管理员查询！');
                }else if($info['f_status'] == 0){
                    $this->writeResult(self::CODE_ERROR,'未支付的订单，补单失败！');
                }

                //查询是否已经含有官方充B信息
                $systeminfo = $this->getSystemLog($info['f_order_id'],$info['f_created']);
                if($systeminfo['ID']){
                    $this->writeResult(self::CODE_ERROR,'官方已经发放货币，补单失败！');
                }

                //订单状态是1，且没有发放货币记录 进行补单操作
                $present_data = ['sourceType'=>5,'propsType'=>$info['f_type'],'count'=>$info['f_num'],'operateType'=>1,'gameId'=>1114112,'userId'=>$info['f_uid']];
                $present_url  = Yii::$app->params['recharge_Url'];
                $present_data = 'msg=' . json_encode($present_data, JSON_UNESCAPED_UNICODE);
                $curl = new Curl();
                $sendinfo = $curl->CURL_METHOD($present_url,$present_data);
                $sendinfo = json_decode($sendinfo,true);

                if(isset($sendinfo['code'])){
                    if($sendinfo['success']){
                        //发货成功 插入数据记录
                        $tablename = 'player_log.t_lobby_player_log__'. date('Ymd',strtotime($info['f_created']));
                        var_dump($tablename);exit;
                        $insert = ['ORDER_ID'=>$info['f_order_id'],'PLAYER_INDEX'=>$info['f_uid'],'MONEY_TYPE'=>$info['f_type'],'SOURCE_TYPE'=>2,'RECHARGE_MONEY'=>$info['f_price'],'COUNT'=>$info['f_num'],'PRE_COUNT'=>'0','CREATE_TIME'=>$info['f_created']];
                        $insert['REMARK'] = '补单操作：为玩家【'.$info['f_uid'].'】补充丢失的'.$info['f_num'].'元宝';
                        $do = $db->createCommand()->insert($tablename,$insert)->execute();
                        $msg = $do?'补单成功！':'元宝发放成功，数据记录失败，请记录信息后联系管理员！';
                        $this->writeResult();
                    }else{
                        $this->writeResult(self::CODE_ERROR,'补单失败：'.$sendinfo['describe']);
                    }
                }else{
                    $this->writeResult(self::CODE_ERROR,'充值接口访问超时！');
                }
            }
        }else{
            $this->writeResult(self::CODE_ERROR,'非法请求');
        }

        return ;
    }
}