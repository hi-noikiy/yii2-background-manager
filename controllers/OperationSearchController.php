<?php
/**
 * User: SeaReef
 * Date: 2018/9/5 11:14
 *
 * 运营查询
 */
namespace app\controllers;

use app\common\Code;
use app\models\Exchange;
use app\models\ExchangeRecord;
use app\models\LobbyPlayer;
use Yii;
use app\models\PlayerReport;

class OperationSearchController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 提现查询
     */
    public function actionWithdraw()
    {
        return $this->render('withdraw');
    }

    /**
     * 提现查询
     */
    public function actionRecharge()
    {
        return $this->render('recharge');
    }

    /**
     * 战绩详情
     */
    public function actionRecord()
    {
        return $this->render('record');
    }

    /**
     * 举报统计
     */
    public function actionReport()
    {
        if(Yii::$app->request->isPost){
            $request = Yii::$app->request->post();
            $limit = $request['limit'];
            $page = $request['page'];
            if(isset($request['field']) && isset($request['order'])){
                $field = $request['field'];
                $orderType = $request['order'];
            }else{
                $field = 'create_time';
                $orderType = 'desc';
            }

            $type = 0;
            if(isset($request['type']) && $request['type']){
                $type = $request['type'];
            }

            $where=[];$filterWhere=['or',['in','playerid',$this->channel_under_list],['in','be_report',$this->channel_under_list]];
            if(isset($request['playerId']) && $request['playerId']){
                //根据类型判断是根据举报人id查还是根据被举报人id查
                if($type == 1){
                    $where['playerid'] = $request['playerId'];
                    $filterWhere = ['in','playerid',$this->channel_under_list];
                }else{
                    $where['be_report'] = $request['playerId'];
                    $filterWhere = ['in','be_report',$this->channel_under_list];
                }
            }

            $model = new PlayerReport();

            //所有符合条件的数据列表
            $rows = $model->getAll($where,$page,$limit,$field,$orderType,$filterWhere);

            $data = $this->dispose($rows);
            $count = $model->getCount($where,$filterWhere);
            $this->writeLayui(Code::OK,'success',$count,$data);

        }
        return $this->render('report');
    }

    public function dispose($data){
        //子游戏id对应的子游戏名称
        $games = Yii::$app->params['games'];

        foreach ($data as $key=>$val){
            if(isset($games[$val['gid']])){
                $data[$key]['gameName'] = $games[$val['gid']];
            }else{
                $data[$key]['gameName'] = $val['gid'];
            }

            if($val['option1'] != 0){
                $data[$key]['reportedType'] = "外挂嫌疑";
            }
            if($val['option2'] != 0){
                $data[$key]['reportedType'] = "合伙作弊";
            }
            if($val['option3'] != 0){
                $data[$key]['reportedType'] = "言语辱骂/地域歧视";
            }
            if($val['option4'] != 0){
                $data[$key]['reportedType'] = "恶意刷屏";
            }

            //举报文字
            $data[$key]['reportContent'] = $val['option5'];

            //是否被封
            $redisKey = Yii::$app->params['redisKeys']['black_id_list'];
            $redis = Yii::$app->redis;
            $isSeal=0;
            if($redis->hget($redisKey,$val['be_report'])){
                $isSeal = 1;
            }
            $data[$key]['isSeal'] = $isSeal;
        }

        return $data;
    }

    /**
     * 举报统计详情
     *
     */
    public function actionReportDetail(){
        if(Yii::$app->request->isPost){
            $request = Yii::$app->request->post();
            if (isset($request['id'])){
                $id = $request['id'];
                $model = new PlayerReport();
                $player = $model->getOne(['id'=>$id],'be_report,gid');
                $fields = "count(id) as report_time";
                $con['be_report'] = $player['be_report'];
                //总的举报次数
                $time = $model->getReportTime($con,$fields);

                //外挂举报次数
                $con['option1'] = "1";
                $waiGuaTime = $model->getReportTime($con,$fields);

                //合伙作弊举报次数
                array_pop($con);
                $con['option2'] = "1";
                $zuoBiTime = $model->getReportTime($con,$fields);

                //言语辱骂/地域歧视举报次数
                array_pop($con);
                $con['option3'] = "1";
                $ruMaTime = $model->getReportTime($con,$fields);

                //恶意刷屏举报次数
                array_pop($con);
                $con['option4'] = "1";
                $shuaPingTime = $model->getReportTime($con,$fields);


                $player['time'] = $time['report_time'] ?: 0;
                $player['waiGuaTime'] = $waiGuaTime['report_time'] ?: 0;
                $player['zuoBiTime'] = $zuoBiTime['report_time'] ?: 0;
                $player['ruMaTime'] = $ruMaTime['report_time'] ?: 0;
                $player['shuaPingTime'] = $shuaPingTime['report_time'] ?: 0;
                $player['statisticsData'] = date('Y-m-d H:i:s');//统计时间

                $this->writeLayui(Code::OK,'',1,[$player]);

            }else{
                $this->writeResult(self::CODE_ERROR,'参数错误！');
            }
        }else{
            $this->writeResult(self::CODE_ERROR,'请求错误！');
        }
    }

    /**
 * 直兑记录
 *
 */
    public function actionExchange(){
        if(Yii::$app->request->isPost){
            if(Yii::$app->request->post()){
                $request = Yii::$app->request->post();
                $limit = $request['limit'];
                $page = $request['page'];
                if(isset($request['field']) && isset($request['order'])){
                    $field = $request['field'];
                    $orderType = $request['order'];
                }else{
                    $field = 'create_time';
                    $orderType = 'desc';
                }

                $where=[];
                if(isset($request['playerId']) && $request['playerId']){
                    $where[] = 'player_id = '.$request['playerId'];
                }

                if(isset($request['orderId']) && $request['orderId']){
                    $where[] = "order_id = '".$request['orderId']."'";
                }

                $startTime = $endTime = '';
                if(isset($request['startTime']) && $request['startTime']){
                    $startTime = $request['startTime'];
                }

                if(isset($request['endTime']) && $request['endTime']){
                    $endTime = $request['endTime'];
                }

                if($startTime && $endTime){
                    if($startTime == $endTime){
                        $endTime = $endTime.'23:59:59';
                    }
                    $where[] = "create_time >= '".$startTime."'";
                    $where[] = "create_time <= '".$endTime."'";
                }

                if($where){
                    $where = implode(" and ",$where);
                }

                $exchangeModel = new ExchangeRecord();
                $list = $exchangeModel->getRecordByPage($page, $limit, $where,$field,$orderType,$this->channel_under_list);

                $lobbyModel = new LobbyPlayer();
                $exchange = new Exchange();
                if(!empty($list)){
                    foreach ($list as $key=>$val){
                        $playerInfo = $lobbyModel->getPlayer($val['player_id']);
                        $exchangeInfo = $exchange->getOne($val['player_id'],$val['type']);

                        $list[$key]['nickname'] = $playerInfo['weixin_nickname'];
                        $list[$key]['name'] = $exchangeInfo['name'];
                        if($val['type'] == 1){
                            $list[$key]['type']     = '支付宝';
                            $list[$key]['terrace']  = '支付宝';
                        }elseif($val['type'] == 2){
                            $list[$key]['type']     = '银行卡';
                            $list[$key]['terrace']  = '汇付宝';
                        }elseif ($val['type'] == 3){
                            $list[$key]['type']     = '微信';
                            $list[$key]['terrace']  = '微信';
//                        $list[$key]['code'] = $playerInfo['weixin_nickname'];
//                        $list[$key]['name'] = $playerInfo['weixin_nickname'];
                        }
                        switch($val['status']){
                            case 0:
                                $list[$key]['status'] = '直兑中';
                                break;
                            case 1:
                                $list[$key]['status'] = '直兑成功';
                                break;
                            case 2:
                                $list[$key]['status'] = '直兑中';
                                break;
                            case 3:
                                $list[$key]['status'] = '直兑失败';
                                break;
                            case 210:
                                $list[$key]['status'] = '订单超时';
                                break;
                            default:
                                $list[$key]['status'] = '未知状态:'.$val['status'];
                                break;
                        }

                    }
                }

                $count = $exchangeModel->getRecordCount($where,$this->channel_under_list);

                $this->writeLayui(self::CODE_OK,'success',$count,$list);
            }
        }

        return $this->render('exchange-record');
    }
}