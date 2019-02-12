<?php
/**
 * User: moyu
 * Date: 2018/9/4 21:33
 *
 * 运营统计
 */
namespace app\controllers;

use app\common\Code;
use app\models\AgentBusinessList;
use app\models\Channel;
use app\models\DailiPlayer;
use app\models\ViewOperStatChannel;
use Yii;
use yii\db\Query;
use app\models\PartherStat;
use app\models\PlayerMember;

class ChannelPartnerController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 今日概况
     */
    public function actionTodayOverview()
    {
        $session = Yii::$app->session;
        $userId = $session->get("__name");
        $partner = new PartherStat();
        if($userId == 'admin'){//此处待优化，如果是管理员，跳转到总的今日概况
            return $this->redirect("/index/welcome");
        }

        $DailiPlayer = new DailiPlayer();
        $con['player_id'] = $userId;
        $con['daili_level'] = 1;
        $fields = "id";
        $partnerInfo = $DailiPlayer->getDataByCon($con,$fields);

        if(!$partnerInfo){
            echo "<script>alert('该渠道合伙人不存在！');</script>";exit;
        }

        $partner = new PartherStat();
        $where['u_id'] = $userId;
        $where['create_time'] = date('Y-m-d');
        $fields = "sum(recharge) as recharge,sum(consume) as consume,sum(deposit) as deposit,sum(agent_ti) as agent_ti";
        $data = $partner->getInfoByUid($where,$fields);

        if(!$data['recharge'] && !$data['consume'] && !$data['deposit'] && !$data['agent_ti']){
            echo "<script>alert('暂无数据！');</script>";exit;
        }

        return $this->render('today_overview',['data'=>$data]);
    }

    /**
     * 每日运营统计
     */
    public function actionDayOpStat()
    {
        $session = Yii::$app->session;
        $userId = $session->getId();

        $channelModel = new Channel();
        $channelList = $channelModel->getDataByCon([]);

        if(Yii::$app->request->isPost){
            if(Yii::$app->request->post()){
                $request = Yii::$app->request->post();

                $session = Yii::$app->session;
                $userId = $session->get("__name");

                $page = $request['page'];
                $limit = $request['limit'];

                $where=[];
                if($userId != "admin"){
                    $where[] = "u_id = ".$userId;
                }
                if(isset($request['startTime']) && $request['startTime']){
                    $where[] = "stat_date >= '".$request['startTime']."'";
                }
                if(isset($request['endTime']) && $request['endTime']){
                    $where[] = "stat_date <= '".$request['endTime']."'";
                }
                if(isset($request["channelId"]) && $request["channelId"]){
                    $where[] = "channel_id = ".$request["channelId"];
                }

                $where = implode(" and ",$where);

                $model = new ViewOperStatChannel();
                $data = $model->getDataByCon($where,'*',4,$limit,$page);

                $count = $model->getDataByCon($where,'id',5);

                $this->writeLayui(Code::OK,'success',$count,$data);

            }else{
                $this->writeResult(self::CODE_ERROR,'请求错误！');
            }
        }

        return $this->render('day_op_stat',['userId'=>$userId,'channelList'=>$channelList]);
    }

    /**
     * 渠道玩家列表
     *
     */
    public function actionMemberList(){
        return $this->render('member-list');
    }

    /**
     * 渠道代理经营列表
     *
     */
    public function actionAgentManageList(){
        $channelModel = new Channel();
        $channelList = $channelModel->getDataByCon([]);

        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $page = $request['page'];
            $limit = $request['limit'];
            if(isset($request['field']) && isset($request['order'])){
                $field = $request['field'];
                $orderType = $request['order'];
            }else{
                $field = 'day_under_consume';
                $orderType = 'desc';
            }

            if (!isset($request['startTime']) || !isset($request['endTime'])) {
                $this->writeResult(self::CODE_ERROR, '时间必填');
            }
            $start = $request['startTime'];
            $end = $request['endTime'];
            if(isset($request['agentId']) && $request['agentId']){
                $where[] = 'agent_id='.$request['agentId'];
                $where[] = "stat_date >= '".$start."'";
                $where[] = "stat_date <= '".$end."'";
            }else{
                $where[] = "stat_date='".$start."'";
            }

            $where = implode(" and ",$where);
            $agentBusinessModel = new AgentBusinessList();
            $data = $agentBusinessModel->getData($where,"*",$limit,$page,$orderType,$field);

            foreach ($data as $key=>$val){
                $data[$key]['idName'] = $val['agent_id']."<br/>".$val['nickname'];
                $data[$key]['parentIdName'] = $val['parent_id']."<br/>".$val['parent_name'];
                $data[$key]['topIdName'] = $val['top_id']."<br/>".$val['top_name'];
                $data[$key]['telTrueName'] = $val['tel']."<br/>".$val['true_name'];
            }

            $count = $agentBusinessModel->getDataByCon($where,'id',5);

            return $this->writeLayui(Code::OK, 'ok', $count, $data);
        } else {
            return $this->render('agent-manage-list',['channelList'=>$channelList]);
        }
    }

}