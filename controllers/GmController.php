<?php
/**
 * User: SeaReef
 * Date: 2018/7/2 15:52
 *
 * GM工具类
 */

namespace app\controllers;

use app\common\Code;
use app\models\ExchangeRecord;
use app\models\LobbyPlayer;
use Yii;
use yii\db\Query;
use yii\base\Curl;
use app\common\Tool;
use app\models\VipRechargeLog;

class GmController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    public $layout = 'common';

    /**
     * 邮件公告列表
     */
    public function actionMailNotice()
    {
        if (Yii::$app->request->isPost) {
            $count = (new Query())
                ->from('t_notice_mail')
                ->count();
            $data = (new Query())
                ->from('t_notice_mail')
                ->all();

            $this->writeResult(self::CODE_OK, '', $data);
        }

        return $this->render('mail_notice');
    }

    /**
     * 进入天降红包页
     * @return [type] [description]
     */
    public function actionSkyDrop(){
        //查询redis  看红包功能是否开启    0关闭  1开启
        $redis = Yii::$app->redis_3;
        $redisKey = Yii::$app->params['redisKeys']['platform_skydrop_switch'];
        $hbSwitch = $redis-> get($redisKey);//红包开关

        $data = [];
        $data["switch"] = $hbSwitch;

        //红包总次数
        $maxTimes = Yii::$app->db->createCommand("SELECT max(times) as maxTimes from t_hongbao") -> queryOne();
        $data["times"] = $maxTimes;

        if ($maxTimes['maxTimes'] == 0){
            $data["totalCost"] = 0;
            return $this->render('sky_drop',['data' => $data]);
        }


        if ($data["times"] == 0){
            return $this->render('sky_drop',['data' => $data]);
        }

        //最后一次红包记录
        $lastSql = "SELECT * FROM t_hongbao  where times = " . $maxTimes["maxTimes"] ." ORDER BY rank ASC;";
        $data["info"] = Yii::$app->db->createCommand($lastSql) -> queryAll();

        //最后一次红包记录总消耗
        $lastTotalSql = "SELECT SUM(gold) as currentGold FROM t_hongbao  where times = " . $maxTimes["maxTimes"];
        $currentTotal = Yii::$app->db->createCommand($lastTotalSql) -> queryOne();


        $data["currentTotal"] = $currentTotal["currentGold"];

        //总消耗
        $totalCost = Yii::$app->db->createCommand("SELECT SUM(gold) AS totalGold FROM t_hongbao") -> queryOne();
        $data["totalCost"] = $totalCost["totalGold"];

        return $this->render('sky_drop',['data' => $data]);
    }

    /**
     * 开启/关闭 红包
     */
    public function actionHongbaoOpen(){
        $hbSwitch = Yii::$app->request->get('hbSwitch');
        $redis = Yii::$app->redis_3;
        $redisKey = Yii::$app->params['redisKeys']['platform_skydrop_switch'];
        if ($hbSwitch == 'true'){
            $redis->set($redisKey, '1');
            return $this->writeJson(1,self::CODE_OK);
        }
        if ($hbSwitch == 'false'){
            $redis->set($redisKey, '0');
            //参数错误
            return $this->writeJson(1,self::CODE_OK);
        }
    }

    /**
     * 按次查询红包记录
     */
    public function actionQueryHongbao(){

        $hbTimes = Yii::$app->request->get('hbTimes');
        //最后一次红包记录
        $data = [];

        $lastSql = "SELECT * FROM t_hongbao  where times = " . $hbTimes ." ORDER BY rank ASC;";
        $info = Yii::$app->db->createCommand($lastSql) -> queryAll();

        $total = 0;
        for($i = 0; $i < count($info); $i++){
            if ($info[$i]['rank'] == 1){
                $info[$i]['rank'] = '一等奖';
            }
            if ($info[$i]['rank'] == 2){
                $info[$i]['rank'] = '二等奖';
            }
            if ($info[$i]['rank'] == 3){
                $info[$i]['rank'] = '三等奖';
            }
            if ($info[$i]['rank'] == 4){
                $info[$i]['rank'] = '参与奖';
            }
            $total += $info[$i]['gold'];
        }
        if (count($info) >= 1){
            $info[0]['total'] = $total;
        }
        $this->writeLayui($code = self::CODE_OK, $msg = '查询成功', 1, $info);
    }

    /**
     * 发奖
     */
    public function actionSendHongbao(){
        $redis = Yii::$app->redis_3;
        $redisKey = Yii::$app->params['redisKeys']['platform_skydrop_switch'];
        if ($redis->get($redisKey) == "0"){
            $this->writeResult(-1, '红包功能已关闭！');
            return;
        }
        $rank1["rank"] = 1;
        $rank1["uid"] = $_REQUEST['rank1uid'];
        $rank1["reward"] = $_REQUEST['rank1gold'];

        $rank2["rank"] = 2;
        $rank2["uid"] = $_REQUEST['rank2uid'];
        $rank2["reward"] = $_REQUEST['rank2gold'];

        $rank3["rank"] = 3;
        $rank3["uid"] = $_REQUEST['rank3uid'];
        $rank3["reward"] = $_REQUEST['rank3gold'];

        $rank4["rank"] = 4;
        $minYB = $_REQUEST['minYB'];
        $maxYB = $_REQUEST['maxYB'];
        $rank4["uid"] = $minYB ."~".$maxYB;
        $rank4["reward"] = $_REQUEST['rank4gold'];

        $data[0] = $rank1;
        $data[1] = $rank2;
        $data[2] = $rank3;
        $data[3] = $rank4;

        $url = Yii::$app->params['sky_drop_hongbao_url'];
        Yii::info('http_get地址：' . $url);
        $present_data = 'msg=' . json_encode($data, JSON_UNESCAPED_UNICODE);

        $curl = new Curl();
        $info = $curl->CURL_METHOD($url,$present_data);
        $info = json_decode($info,true);

        print_r($info);
        return;
    }

    /**
     * 创建邮件
     */
    public function actionCreateMail()
    {

        return $this->render('create_mail');
    }

    /**
     * 历史邮件
     */
    public function actionHistoryMail()
    {
        return $this->render('history_mail');
    }

    /**
     * 官方充值
     */
    public function actionInRecharge()
    {
        return $this->render('in_recharge');
    }

    /**
     * 解散房间
     */
    public function actionDismissRoom()
    {
        return $this->render('dismiss_room');
    }

    /**
     * 已封玩家列表
     */
    public function actionSealList()
    {
        $redisKey = Yii::$app->params['redisKeys']['black_id_list'];
        $redis = Yii::$app->redis;
        $info = $redis->hgetall($redisKey);
        $data = [];
        for ($i = 0; $i < count($info) - 1; $i++) {
            if ($i % 2 == 0) {
                $data[$info[$i]] = json_decode($info[$i + 1])->date;
            }
        }

        return $this->render('seal_list', ['data' => $data]);
    }

    /**
     * 封停和解封
     *
     */
    public function actionSeal()
    {
        //封停账号
        $redisKey = Yii::$app->params['redisKeys']['black_id_list'];
        $redis = Yii::$app->redis;
        $status = Yii::$app->request->post('status');//1封2解
        $player = Yii::$app->request->post('playerId');

        //判断用户是否存在
        $user = Yii::$app->db->createCommand('select id from login_db.t_lobby_player where u_id = :u_id')
            ->bindValue(':u_id', $player)
            ->queryOne();
        if (!$user) {
            $this->writeResult(self::CODE_PLAYER_NOT_FOUND);
        }

        if ($status == 1) {
            $url = Yii::$app->params['kicking_url'];
            $send_data = array(
                'type' => 2,
                'param' => $player,
            );
            $present_data = 'msg=' . json_encode($send_data, JSON_UNESCAPED_UNICODE);

            //通知服务器踢掉该玩家
            $curl = new Curl();
            $res = $curl->CURL_METHOD($url, $present_data);

            $data['date'] = date('Y-m-d');
            if ($redis->hset($redisKey, $player, json_encode($data))) {
                $this->writeResult();
            }
        } else if ($status == 2) {
            if ($redis->hdel($redisKey, $player)) {
                $this->writeResult();
            }
        } else {
            $this->writeResult(self::CODE_ERROR, 'false1');
        }

        $this->writeResult(self::CODE_ERROR, 'false2');

    }

    /**
     * VIP充值页面
     */
    public function actionRechargeIndex()
    {
        $proportion = Yii::$app->params['gold_transition_proportion'];
        preg_match_all("#((\d+)/(\d+))([-+/*]*)#", $proportion, $reg);
        $percentage = $reg[2][0] / $reg[3][0];

        //汇总数据
        $summarizing = $this->actionVipRechargeSummarizing();

        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->post()) {
                $request = Yii::$app->request->post();

                $playerId = preg_replace('# #','',$request['player_id']);
                $orderId = preg_replace('# #','',$request['order_id']);

                $keyVal = $playerId.$request['out_amount'];
                if($this->redis->get($keyVal)){
                    return $this->writeResult(Code::CODE_VIP_RECHARGE_ERROR,'存在未确认订单，请稍后！');//
                }else{
                    $this->redis->set($keyVal, 1);
                    $this->redis->expire($keyVal,60);
                }

                $lobbyModel = new LobbyPlayer();
                $session = Yii::$app->session;
                $user = $session->get("__name");//登陆账号

                Yii::info('充值金额：'.$request['out_amount']);
                $amount = $request['out_amount'];

                $player = $lobbyModel->getPlayer($playerId);
                if (!$player) {
                    $this->redis->del($keyVal);
                    $this->writeResult(Code::CODE_PLAYID_NOT_FOUND);
                } else {
                    //创建充值订单
                    $request['operate_user'] = $user;
                    $request['create_time'] = date("Y-m-d H:i:s");
                    $VipModel = new VipRechargeLog();
                    unset($request['nickname']);
                    $request['status'] = 0;

                    //判断是否存在重复订单
                    $rechargeModel = new VipRechargeLog();
                    $con[] = 'player_id='.$playerId;
                    $con[] = 'order_id='.$orderId;
                    $con[] = "create_time > '".date('Y-m-d')."'";
                    $con = implode(" and ",$con);
                    $record = $rechargeModel->getData($con,"id",3);
                    if($record){
                        return $this->writeResult(Code::CODE_VIP_RECHARGE_ERROR,'订单已存在!');
                    }

                    if($VipModel->createRecord($request)){
                        $this->writeResult(self::CODE_OK, '订单创建成功！');
                    }else{
                        $this->writeResult(self::CODE_ERROR, '订单创建失败！');
                    }
                }
            }
        }
        return $this->render('recharge_index', ['proportion' => $percentage, 'summarizing' => $summarizing]);
    }

    public function actionRecharge(){
        if(Yii::$app->request->isPost){
            $request = Yii::$app->request->post();
            $thisId = $request['id'];

            $VipModel = new VipRechargeLog();
            $record = $VipModel->getData(['id'=>$thisId],'*',2);

            $present_data = [
                'sourceType' => Tool::RECHARGE_PLAYER,
                'propsType' => 3,//固定为元宝
                'count' => $record['out_amount'],
                'operateType' => 1,//固定为增加
                'gameId' => 1114112,//固定为大厅的id
                'userId' => $record['player_id']
            ];
            $present_url = Yii::$app->params['recharge_Url'];
            $curl = new Curl();
            $present_data = 'msg=' . json_encode($present_data, JSON_UNESCAPED_UNICODE);
            $info = $curl->get($present_url.'?'.$present_data);
            $info = json_decode($info,true);
            Yii::info("data服返回：".json_encode($info));

            if (isset($info['code'])) {
                if ($info['success'] && $info['code'] != 2) {
                    $status = 1;
                } else {
                    $status = 2;
                }
            } else {
                $status = 3;
            }
            $statusCode = array(1 => self::CODE_OK, 2 => self::CODE_ERROR, 3 => Code::CODE_RECHARGE_TIMEOUT);
            if ($VipModel->updateRecord($thisId,$status)) {
                //设置充值时间间隔
                $msg='';
                if($status != 1){
                    $msg = "充值失败！";
                }
                $this->writeResult($statusCode[$status],$msg);
            } else {
                $this->writeResult(self::CODE_ERROR, '充值失败！');
            }
        }
    }

    /**
     * 查询玩家昵称
     *
     */
    public function actionCheckNickname()
    {
        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->post()) {
                $request = Yii::$app->request->post();
                $playerId = 0;
                if (isset($request['player_id']) && $request['player_id']) {
                    $playerId = $request['player_id'];
                }
                if ($playerId) {
                    $loginDB = new LobbyPlayer();
                    $info = $loginDB->checkUser($playerId);
                    if ($info && isset($info['nickname'])) {
                        $this->writeResult(self::CODE_OK, '', $info['nickname']);
                    } else {
                        $this->writeResult(self::CODE_OK, '', '(该玩家不存在或者没有昵称！)');
                    }
                } else {
                    $this->writeResult(self::CODE_ERROR, '玩家id错误！');
                }
            } else {
                $this->writeResult(self::CODE_ERROR, '请求错误！');
            }
        } else {
            $this->writeResult(self::CODE_ERROR, '请求错误！');
        }
    }

    /**
     * 充值记录
     *
     */
    public function actionVipRechargeRecord()
    {
        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->post()) {
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

                $where = [];
                $startTime = $endTime = '';
                if (isset($request['playerId']) && $request['playerId']) {
                    $playerId = preg_replace('# #','',$request['playerId']);
                    $where[] = 'player_id = ' . $playerId;
                }
                if (isset($request['startTime']) && $request['startTime']) {
                    $startTime = $request['startTime'];
                }
                if (isset($request['endTime']) && $request['endTime']) {
                    $endTime = $request['endTime'];
                }
                if ($startTime && $endTime) {
                    $endTime = $endTime . '23:59:59';
                    $where[] = "create_time >= '" . $startTime . "'";
                    $where[] = "create_time <= '" . $endTime . "'";
                }

                if ($where) {
                    $where = implode(' and ', $where);
                }

                $rechargeModel = new VipRechargeLog();
                $list = $rechargeModel->getAllLogByPage($limit, $page, $where,$field,$orderType);

                $lobbyModel = new LobbyPlayer();
                foreach ($list as $key => $val) {
                    $playerInfo = $lobbyModel->getPlayer($val['player_id']);
                    $nickname = $playerInfo['weixin_nickname'];
                    $list[$key]['nickname'] = $nickname;
                    if ($val['status'] == 1) {
                        $list[$key]['status'] = '充值成功';
                    } elseif ($val['status'] == 0) {
                        $list[$key]['status'] = '等待充值';
                    } elseif ($val['status'] == 2) {
                        $list[$key]['status'] = '充值失败';
                    }else {
                        $list[$key]['status'] = '充值失败';
                    }
                }
                $count = $rechargeModel->getRecordCount($where);

                $this->writeLayui(Code::OK, 'success', $count, $list);
            }
        }
    }

    /**
     * vip充值汇总(Summarizing 汇总)
     *
     */
    public function actionVipRechargeSummarizing()
    {
        $RechargeModel = new VipRechargeLog();

        $where['status'] = 1;
        $all = $RechargeModel->getSummarizing($where);

        $date = date('Y-m-d');
        $tomorrow = date('Y-m-d', time() + 86400);
        $where = "create_time >= '" . $date . "' and create_time < '" . $tomorrow . "' and status = 1";
        $today = $RechargeModel->getSummarizing($where);

        $data['all'] = $all;
        $data['today'] = $today;

        return $data;
    }

}
