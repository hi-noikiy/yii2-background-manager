<?php
/**
 * User: jw
 * Date: 2018/9/10 0010
 */
namespace app\controllers;

use app\common\Code;
use app\common\DailiCalc;
use app\common\RedisKey;
use app\models\AuthAssignment;
use app\models\Channel;
use app\models\LobbyPlayer;
use yii;
use yii\db\Query;
use app\models\User;
use yii\db\Exception;
use app\models\OperUserExpendDay;
use app\models\DailiPlayer;
use app\models\Player;
use app\models\PlayerMember;

class CommonBaseController extends BaseController
{
    public $up = "↑";
    public $down = "↓";
    public $key = 'sms_code';//短信验证码session key
    public $daili_subordinate_key = 'daili_subordinate';//代理伞下id列表的redis key
    public $channel_under_list=array();
    public $channel_id=1;

    /**
     * 消息编码常量
     */
    const CODE_OK = 0;

    const CODE_ERROR = -1;

    /**
     * 支付中心错误码
     */
    const CODE_PARAM_NOT_ENOUGH = -201;

    const CODE_PARAM_ERROR = -202;

    const CODE_SIGN_ERROR = -203;

    /**
     * 轮播图设置
     */
    const CODE_IMG_EXTENSION_ERROR = -300;
    const CODE_IMG_SIZE_ERROR = -301;

    const CODE_PLAYER_NOT_FOUND = -302;

    /**
     * 权限rbac
     */
    const CODE_AUTH_CATEGORY_NOT_FOUND = -400;
    const CODE_AUTH_CATEGORY_EXISTS = -401;
    const CODE_USER_EXISTS = -402;
    const CODE_ROLE_USED = -403;

    /**
     * 消息编码信息
     */
    public $code_message = [
        self::CODE_OK => 'ok',
        self::CODE_ERROR => 'error',

        self::CODE_PARAM_NOT_ENOUGH => 'params not enough',
        self::CODE_PARAM_ERROR => 'params error',
        self::CODE_SIGN_ERROR => 'sign error',
        self::CODE_IMG_EXTENSION_ERROR => 'file is not img',
        self::CODE_IMG_SIZE_ERROR => 'file size too large',
        self::CODE_AUTH_CATEGORY_NOT_FOUND => 'category not found',
        self::CODE_AUTH_CATEGORY_EXISTS => 'permission category exists',
        self::CODE_USER_EXISTS => 'user exists',
        self::CODE_ROLE_USED => 'role is used',
    ];

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        $uid = Yii::$app->user->getId();
        if ($uid) {
            $model = User::findOne($uid);
        } else {
            return $this->redirect('/user/login');
        }

        $this->setChannelUnderList();

        if ($model->username == 'admin') {//判断超级管理员
            return true;
        } else {
            $controllerID = Yii::$app->controller->id;
            $actionID = Yii::$app->controller->action->id;
            if ($controllerID == 'index' && $actionID == 'index') {
                return true;
            }
            if ($controllerID == 'auth' && $actionID == 'manager-roles') {//左侧菜单
                return true;
            }
            if(Yii::$app->user->can('/' . $controllerID.'/'.$actionID))
            {
                return true;//如该用户能访问该请求，则进行返回
            } else {
                echo '没有访问权限';exit;
            }
        }
    }

    /**
     * 获取代理下所有玩家
     */
    public function getMemberTree($player)
    {
        $result = [];
        if($player){
            $res = [$player];
            $result = $this ->memberTree($res,[]);
        }
        return $result;
    }

    public function memberTree($res,$has,$level = 0)
    {
        // 如果子集不为空，比对当前要查询的数组和子集数组是否有交集，有交集删除交集
        if(!empty($has)){
            $intersect = array_intersect($res,$has);
            if(!empty($intersect)){
                foreach ($res as $key => $value) {
                    unset($res[$key]);
                }
            }
        }
        //合并数组
        if($level){
            $has = array_merge($has,$res);
        }
        $member_list = (new Query())->select('parent_id')->from('t_player_member')->where(['in', 'player_id', $res])->all();
        if(!empty($member_list)){
            $member_list = array_column($member_list,'parent_id');
            $has = $this -> memberTree($member_list,$has,$level+1);
        }
        return $has;
    }

    /**
     * 查询玩家自身元宝
     *
     */
    public function getGoldBar($playerId){
        $goldBar=0;
        if(!$playerId){
            return $goldBar;
        }
        $goldBar = Yii::$app->db->createCommand("SELECT gold_bar FROM login_db.t_lobby_player WHERE u_id=".$playerId)->queryScalar() ?: 0;

        return $goldBar;
    }

    /**
     * 计算玩家的总消耗
     *
     * @param $playerId
     * @param string $startDate
     * @param string $endDate
     * @return int
     */
    public function getExpend($playerId,$startDate="",$endDate="",$type=1){
        $model = new OperUserExpendDay();
        if($type == 1){
            $where = "PLAYER_INDEX = ".$playerId;
        }else{
            $where = "PLAYER_INDEX in (".$playerId.")";
        }

        if(!empty($startDate) || !empty($endDate)){
            if($startDate && $endDate){
                if($startDate > $endDate){
                    $where .= " and DAY >= '".$endDate."' and DAY <= '".$startDate."'";
                }else{
                    $where .= " and DAY <= '".$endDate."' and DAY >= '".$startDate."'";
                }
            }else{
                if($startDate){
                    $where .= " and DAY = '".$startDate."'";
                }
                if($endDate){
                    $where .= " and DAY = '".$endDate."'";
                }
            }
        }
        $num = $model->getDataByPlayerId($where,$type);

        return $num ?: 0;
    }

    /**
     * 计算玩家实时消耗
     *
     */
    public function getExpendRealTime($playerId){
        if(!$playerId){
            return 0;
        }
        $date = date('Ymd');
        $tableName = 't_gold_record__'. $date;

        $db      = Yii::$app->db;
        $sql     = "SELECT SUM(num) as num from ".$tableName." where TYPE = 1 and player_id=".$playerId;
        $expend = $db->createCommand($sql)->queryScalar() ?: 0;

        return $expend;
    }

    /**
     * 查询当日所有游戏总消耗
     *
     */
    public function getAllGameExpend($date){
        if(!$date){
            return 0;
        }
        $allExpends = Yii::$app->db->createCommand("SELECT SUM(consume) AS consume FROM stat_gameplay WHERE stat_date='{$date}'")->queryScalar();
        if(!$allExpends){
            $allExpends=0;
        }
        return $allExpends;
    }

    /**
     * 修改玩家上级id
     *
     */
    public function updateParentId($newParentId,$playerId){
        if(!$newParentId || !$playerId){
            return false;
        }
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try{
            Yii::info('修改代理表开始');
            $membeRes = $db->createCommand("UPDATE t_player_member SET parent_id='{$newParentId}' WHERE player_id='{$playerId}'")->execute();
            Yii::info('修改代理表结束');
            $res=true;

            $daili = $db->createCommand("SELECT id FROM t_daili_player WHERE player_id='{$playerId}'")->queryOne();
            if($daili){
                Yii::info('修改代理关系表');
                $res = $db->createCommand("UPDATE t_daili_player SET parent_index='{$newParentId}' WHERE player_id='{$playerId}'")->execute();
            }
            Yii::info('修改代理关系表结束');
            if($membeRes && $res){
                Yii::info('修改代理成功！');
                $transaction->commit();
                return true;
            }
        }catch (Exception $e){
            $transaction->rollBack();
        }

        return false;
    }

    /**
     * 获取玩家信息
     *
     */
    public function getPlayerInfo($playerId,$fields='*'){
        if(!$playerId){
            return '';
        }
        $data = Yii::$app->db->createCommand("SELECT ".$fields." FROM login_db.t_lobby_player WHERE u_id='{$playerId}'")->queryOne();

        return $data;
    }

    /**
     * session 操作
     *
     */
    public function sessionOperate($key, $type, $value=''){
        $session = Yii::$app->session;
        if (!$session->isActive){
            $session->open();
        }
        try{
            if($type=='get'){
                $val = $session->get($key);
                $session->close();
                return $val;
            }elseif($type == 'set'){
                $session->set($key, $value);
            }elseif($type == 'remove'){
                if($session->remove($key)){
                    $session->close();
                    return true;
                }
            }else{
                $session->close();
                return false;
            }
        }catch (Exception $e){
            $session->close();
            Yii::info("操作redis失败！");
        }

        return true;
    }

    /**
     * 获取下级详情
     *
     * @param $memberInfo @所有下级id
     * @param $parentIndex @用户id
     * @param $startDate @开始时间
     * @param $endDate @结束时间
     * @return array
     */
    public function getLowerLevelInfo($memberInfo,$parentIndex,$startDate="",$endDate=""){
        $data=[];
        if(!$memberInfo || !$parentIndex){
            return $data;
        }

        foreach ($memberInfo as $key=>$val){
            $fields = "player_id,name,parent_index";
            $con['player_id'] = $val['player_id'];
            $daiLi = new DailiPlayer();
            $daiLiInfo = $daiLi->getDataByCon($con,$fields);
            if(!$daiLiInfo){
                $player = new Player();
                $playerInfo = $player->getPlayerById($val['player_id'],"player_id,nickname");
                if($playerInfo){
                    $data[$key]["player_id"] = $playerInfo['player_id'];
                    $data[$key]["name"] = $playerInfo['nickname'];
                    $data[$key]["parent_index"] = $parentIndex;

                    $allMember = $this->getAllLowerPlayer($playerInfo['player_id']);
                    $lowerAllExpends= $allMember ? $this->getExpend($allMember,$startDate,$endDate,2) : 0;
                    $data[$key]['expend'] = $lowerAllExpends;
                }else{
                    $data[$key]["player_id"] = $val['player_id'];
                    $data[$key]["name"] = '';
                    $data[$key]["parent_index"] = $parentIndex;

                    $allMember = $this->getAllLowerPlayer($playerInfo['player_id']);
                    $lowerAllExpends= $allMember ? $this->getExpend($allMember,$startDate,$endDate,2) : 0;
                    $data[$key]['expend'] = $lowerAllExpends;
                }
            }else{
                $data[$key]["player_id"] = $daiLiInfo[0]['player_id'];
                $data[$key]["name"] = $daiLiInfo[0]['name'];
                $data[$key]["parent_index"] = $daiLiInfo[0]['parent_index'];

                $allMember = $this->getAllLowerPlayer($daiLiInfo[0]['player_id']);
                $lowerAllExpends= $allMember ? $this->getExpend($allMember,$startDate,$endDate,2) : 0;
                $data[$key]['expend'] = $lowerAllExpends;
            }
        }
        return $data;
    }

    /**
     * 判断指定用户为几级
     */
    public function getLevelNum($playerId){
        $where['player_id'] = $playerId;
        $parentIndex = "parent_id";
        $fields = "parent_id,player_id";
        $model = new PlayerMember();
        $res = $model->getDataByCon($where,$fields);
        $data = [];
        if($res){ $data = $res[0]; }
        if($data){
            $this->level++;
            self::getLevelNum($data[$parentIndex]);
        }
    }

    /**
     * 获取所有下级玩家
     *
     */
    public function getAllLowerPlayer($playerId){
        $redis = Yii::$app->redis_3;
        $allMember = $redis->hget($this->daili_subordinate_key,$playerId);

        return $allMember;
    }

    /**
     * 获取这个订单的系统充值记录
     * @Author   WKein
     * @DateTime 2018-02-08T15:44:28+0800
     * @param    [type]   $order_id [订单号]
     * @param    [type]   $time     [订单所在的时间]
     * @return   [type]   信息数组
     */
    public function getSystemLog($order_id,$time){

        $db   = Yii::$app->db;
        $time_str  = strtotime($time);
        if( ($time_str - 1517932800) < 0 ){
            return [];
        }

        $tablename = 'player_log.t_lobby_player_log__'.date('Ymd',$time_str);

        $sql = 'SELECT * FROM '.$tablename.' WHERE ORDER_ID = "'.$order_id.'"';
        $info = $db->createCommand($sql)->queryOne();

        if(!$info ){
            if( date('Ymd',$time_str) == date('Ymd') ){ //如果查询的日期就是今天
                return [];
            }else{
                $tablename = 'player_log.t_lobby_player_log__'.date('Ymd',($time_str+86400));
                $sql = 'SELECT * FROM '.$tablename.' WHERE ORDER_ID = "'.$order_id.'"';
                $info = $db->createCommand($sql)->queryOne();
                return $info ;
            }
        }
        return $info;
    }

    /******************************* 时间分割 ***************************/
    function Date_segmentation($start_date, $end_date)
    {
        //如果为空，则从今天的0点为开始时间
        if (!empty($start_date))
            $start_date = date('Y-m-d H:i:s', strtotime($start_date));
        else
            $start_date = date('Y-m-d 00:00:00', time());

        //如果为空，则以明天的0点为结束时间（不存在24:00:00，只会有00:00:00）
        if (!empty($end_date))
            $end_date = date('Y-m-d H:i:s', strtotime($end_date));
        else
            $end_date = date('Y-m-d 00:00:00', strtotime('+1 day'));

        //between 查询 要求必须是从低到高
        if ($start_date > $end_date) {
            $ttt = $start_date;
            $start_date = $end_date;
            $end_date = $ttt;
        } elseif ($start_date == $end_date) {
            echo '时间输入错误';
            die;
        }

        $time_s = strtotime($start_date);
        $time_e = strtotime($end_date);
        $seconds_in_a_day = 86400;

        //生成中间时间点数组（时间戳格式、日期时间格式、日期序列）
        $days_inline_array = array();
        $times_inline_array = array();

        //日期序列
        $days_list = array();

        //判断开始和结束时间是不是在同一天
        $days_inline_array[0] = $start_date;

        //初始化第一个时间点
        $times_inline_array[0] = $time_s;

        //初始化第一个时间点
        $days_list[] = date('Y-m-d', $time_s);

        //初始化第一天
        if (
            date('Y-m-d', $time_s) == date('Y-m-d', $time_e)) {
            $days_inline_array[1] = $end_date;
            $times_inline_array[1] = $time_e;
        } else {
            /**
             * A.取开始时间的第二天凌晨0点
             * B.用结束时间减去A
             * C.用B除86400取商，取余
             * D.用A按C的商循环+86400，取得分割时间点，如果C没有余数，则最后一个时间点 与 循环最后一个时间点一致
             */
            $A_temp = date('Y-m-d 00:00:00', $time_s + $seconds_in_a_day);
            $A = strtotime($A_temp);
            $B = $time_e - $A;
            $C_quotient = floor($B / $seconds_in_a_day);

            //商舍去法取整
            $C_remainder = fmod($B, $seconds_in_a_day);

            //余数
            $days_inline_array[1] = $A_temp;
            $times_inline_array[1] = $A;
            $days_list[] = date('Y-m-d', $A);

            //第二天
            for ($increase_time = $A, $c_count_t = 1; $c_count_t <= $C_quotient; $c_count_t++) {
                $increase_time += $seconds_in_a_day;
                $days_inline_array[] = date('Y-m-d H:i:s', $increase_time);
                $times_inline_array[] = $increase_time;
                $days_list[] = date('Y-m-d', $increase_time);
            }

            $days_inline_array[] = $end_date;
            $times_inline_array[] = $time_e;
        }
        return array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'days_list' => $days_list,
            'days_inline' => $days_inline_array,
            'times_inline' => $times_inline_array
        );
    }



    public function writeJsonOk($data){
        $this->writeJson(1, self::CODE_OK,'success', 0, $data);
    }
    public function writeJsonFalse($msg){
        $this->writeJson(2, self::CODE_ERROR,$msg, 0);
    }

    /**
     * 输出layui标准json信息
     *
     * @params $type 数据数据类型、1、layui模式、2、标准模式
     */
    public function writeJson($type = 1, $code = self::CODE_OK, $msg = '', $count = 0, $data = '')
    {
        if ($type == 1) {
            return $this->asJson([
                'code' => $code,
                'msg' => $msg ? : $this->code_message[$code],
                'count' => $count,
                'data' => $data,
            ]);
        }
        if ($type == 2) {
            return $this->asJson([
                'code' => $code,
                'msg' => $msg ? : $this->code_message[$code],
            ]);
        }
    }

    /**
     * 跑马灯操作redis
     *
     * @param $id
     * @param $startTime
     * @param int $type 1：需要start_time 2:不需要start_time
     * @return bool
     */
    public static function toPMDRedis($id,$startTime,$type=1){
        if(empty($id) || ($type==1 && empty($startTime))){
            Yii::info("操作redis参数不合法");
            return false;
        }
        if(!empty($startTime)){
            $startTime = strtotime($startTime);
        }
        try{
            $redis = Yii::$app->redis_3;
            $redis_key = Yii::$app->params['redisKeys']['gm_paoma_time'];

            $redis -> zadd($redis_key,$startTime,$id);
        }catch(yii\db\Exception $e){
            Yii::info("操作redis失败".$e);
            return false;
        }

        return true;
    }

    public function getNoLoginInfo($playerId){
        $unionid = Yii::$app->redis_2->hget('reserved_key_id', $playerId);

        $info = Yii::$app->redis->hget('share_scan_info', $unionid);
        $info = json_decode($info, 1);

        $info['wxinfo']['u_id'] = $playerId;
        $info['wxinfo']['no_login'] = 1;

        return $info;
    }

    /**
     * 获取玩家直属业绩
     *
     * @param int $code
     * @param string $msg
     * @param string $data
     */
    public function getDirectConsume($playerId,$date){
        $directList = $this->getDirectPlayers($playerId);

        $consume = 0;
        foreach ($directList as $k=>$v){
            $consume += $this->getConsumeByPlayerId($v['player_id'],2,$date);
        }

        return $consume;
    }

    protected function useCrfNew(){
        if ($this->enableCsrfValidation &&  !in_array(Yii::$app->request->method, ['GET', 'HEAD', 'OPTIONS'], true)) {
            Yii::$app->getRequest()->getCsrfToken(true);
        }
        return true;
    }

    public function writeLayui($code = Code::OK, $message = '', $count, $data)
    {
        echo json_encode([
            'code' => $code,
            'msg' => $message,
            'data' => $data,
            'count' => $count,
        ]);
        die();
    }

    public function setChannelUnderList(){
        $redis = Yii::$app->redis_3;

        $loginUserId = Yii::$app->user->id;
        Yii::info('当前登陆id---'.$loginUserId);

        $channel_id = $redis->get(RedisKey::CHANNEL_ID.$loginUserId) ?: 1;
        Yii::info('当前channelId---'.$channel_id);

        $under_list = [];
        $this->channel_id = $channel_id;

        if($channel_id != 1){
            Yii::info('非默认渠道！！');

            $channelModel = new Channel();
            $agentId = $channelModel->getDataByCon(['channel_id'=>$channel_id],'agent_id',3);
            $under_player_list = DailiCalc::getAgentList($agentId,'allUnderPlayer');
            $under_agent_list = DailiCalc::getAgentList($agentId,'allUnderDaili');
            $under_list = array_merge($under_player_list,$under_agent_list);

            Yii::info('该渠道伞下玩家数---'.count($under_list));
        }


        $session = Yii::$app->session;
        if(empty($under_list)){
            $AuthAssignmentModel = new AuthAssignment();
            $channelRuleName = Yii::$app->params['channel_rule_name'];
            $itemName = $AuthAssignmentModel->getDataByCon(['user_id'=>$session->get('__id')],'item_name',3);
            if($itemName == $channelRuleName || $channel_id != 1){
                return $this->channel_under_list = [1];
            }

        }

        $this->channel_under_list = $under_list;
    }

}