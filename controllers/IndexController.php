<?php
/**
 * User: SeaReef
 * Date: 2018/6/14 21:01
 */
namespace app\controllers;

use app\common\Code;
use app\common\RedisKey;
use app\common\Tool;
use app\models\Channel;
use app\models\Index;
use app\models\PartherStat;
use Yii;
use app\models\User;
use yii\db\Query;
use app\models\DailiPlayer;

class IndexController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    public $layout = 'layui';

    public function actionIndex()
    {
        $uid = Yii::$app->user->id;
        $data = User::getMenu($uid);
        $user = User::find()->select('*')->where(['id' => $uid])->asArray()->one();

        $channelModel = new Channel();
        $channelId = $channelModel->getDataByCon(['agent_id'=>$user['username']],'channel_id',3);
        if($channelId){
            $redis = Yii::$app->redis_3;
            $redis->set(RedisKey::CHANNEL_ID.$uid,$channelId);
        }

        $redis = Yii::$app->redis_3;
        $loginUserId = Yii::$app->session->get('__id');
        $thisChannelId = $redis->get(RedisKey::CHANNEL_ID.$loginUserId);

        $channelModel = new Channel();
        $channelList = $channelModel->getDataByCon([]);

        return $this->render('index', ['title' => array_keys($data), 'data' => $data, 'user' => $user,'channelList'=>$channelList,'thisChannelId'=>$thisChannelId,'uid'=>$uid]);
    }

    public function actionSetChannel(){
        if(Yii::$app->request->isPost){
            $request = Yii::$app->request->post();
            $channelId = $request['channelId'];

            $redis = Yii::$app->redis_3;
            $loginUserId = Yii::$app->session->get('__id');

            if($redis->set(RedisKey::CHANNEL_ID.$loginUserId,$channelId)){
                return $this->writeResult(Code::OK,'设置成功');
            }else{
                return $this->writeResult(Code::ERROR,'设置失败');
            }
        }
    }

    public function actionWelcome()
    {
        $session = Yii::$app->session;
        $userId = $session->get("__name");

        if ($userId == 'zifan') {

        } elseif ($userId && $userId != 'admin') {
//            return $this->redirect('/channel-partner/today_overview');
        }

//        今日概况
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');
        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        $suffix = date('Ymd');
        $gold_record_table = 't_gold_record__' . $suffix;

//        今日官方充值
        $today_recharge = (new Query())->from('t_order')->where(['and', 'status = 1', "create_time > '{$start}'", "create_time < '{$end}'"])->andFilterWhere(['in','player_id',$this->channel_under_list])->sum('goods_price') ? : 0;
//        今日活动充值
        $today_activity_recharge = (new Query())->from('t_order')->where(['and', 'status = 1', 'goods_price = 8', "create_time > '{$start}'", "create_time < '{$end}'"])->andFilterWhere(['in','player_id',$this->channel_under_list])->sum('goods_price') ? : 0;
//        今日vip充值
        $today_vip_recharge = (new Query())->from('t_vip_recharge_log')->where(['and', 'status = 1', "create_time > '{$start}'", "create_time < '{$end}'"])->andFilterWhere(['in','player_id',$this->channel_under_list])->sum('amount') ? : 0;
//        今日系统增发
        $incr_system = (new Query())->from('t_service_recharge_log')->where(['and', "use_type = 1", "time >= '{$start}'", "time < '{$end}'", 'status = 1'])->andFilterWhere(['in','player_id',$this->channel_under_list])->sum('gold_num') ? : 0;
        $decr_system = (new Query())->from('t_service_recharge_log')->where(['and', "use_type = 2", "time >= '{$start}'", "time < '{$end}'", 'status = 1'])->andFilterWhere(['in','player_id',$this->channel_under_list])->sum('gold_num') ? : 0;
        $today_system_recharge = ($incr_system - $decr_system) / 100 ? : 0;

//        总充值
        $total_recharge = (new Query())->from('t_order')->where(['status' => 1])->andFilterWhere(['in','player_id',$this->channel_under_list])->sum('goods_price') ? : 0;
//        总活动充值
        $total_activity_recharge = (new Query())->from('t_order')->where(['and', 'status = 1', 'goods_price = 8'])->andFilterWhere(['in','player_id',$this->channel_under_list])->sum('goods_price') ? : 0;
//        总vip充值
        $total_vip_recharge = (new Query())->from('t_vip_recharge_log')->where(['status' => 1])->andFilterWhere(['in','player_id',$this->channel_under_list])->sum('amount') ? : 0;
//        总增发
        $incr_system = (new Query())->from('t_service_recharge_log')->where(['and', "use_type = 1", 'status = 1'])->andFilterWhere(['in','player_id',$this->channel_under_list])->sum('gold_num') ? : 0;
        $decr_system = (new Query())->from('t_service_recharge_log')->where(['and', "use_type = 2", 'status = 1'])->andFilterWhere(['in','player_id',$this->channel_under_list])->sum('gold_num') ? : 0;
        $total_system_recharge = ($incr_system - $decr_system) / 100 ? : 0;

//        玩家提现
        $today_player_pay = (new Query())->from('t_exchange_record')->where(['and', 'status = 1', "create_time > '{$start}'", "create_time < '{$end}'"])->andFilterWhere(['in','player_id',$this->channel_under_list])->sum('amount - service_charge') / 100 ? : 0;
//        总提现
        $total_player_pay = (new Query())->from('t_exchange_record')->where(['status' => 1])->andFilterWhere(['in','player_id',$this->channel_under_list])->sum('amount - service_charge') / 100 ? : 0;

//        代理可提现金额
        $agent_pay = (new Query())->from('t_daili_player')->filterWhere(['in','player_id',$this->channel_under_list])->sum('pay_back_gold') / 110 ? : 0;
//        代理总返利
        $all_daili_pay = (new Query())->from('t_daili_player')->filterWhere(['in','player_id',$this->channel_under_list])->sum('all_pay_back_gold') / 110 ? : 0;


//        今日元宝消耗
        $consume = (new Query())->from($gold_record_table)->filterWhere(['in','player_id',$this->channel_under_list])->sum('num') ? : 0;
//        总元宝消耗
        $c = (new Query())->from('stat_base_consume')->where(['channel_id'=>$this->channel_id])->sum('consume');
        $all_consume = ($consume + $c) ? : 0;

//        元宝淤积/机器人淤积
        $deposit = (new Query())->from('login_db.t_lobby_player')->filterWhere(['in','u_id',$this->channel_under_list])->sum('gold_bar');
        $redis = Yii::$app->redis;
        $robot_hundred = $redis->hget('br_table_config_524821', 'totalGoldPool') ? : 0;
        $deposit = ($deposit + $robot_hundred) ? : 0;

//        今日提现
        $today_agent_pay = (new Query())->from('t_pay_order')->where(['and', 'PAY_STATUS = 1', "CREATE_TIME > '{$start_ts}'", "CREATE_TIME < '{$end_ts}'"])->andFilterWhere(['in','PLAYER_INDEX',$this->channel_under_list])->sum('PAY_MONEY') / 110 ? : 0;
//        库收入
        $robot = (new Query())->select('income_gold')->from('log_hundred_game_day_record')->where(['and', "date >= '{$start}'", "date < '{$end}'"])->scalar() ? : 0;

//        监控
        $input = $output = 0;
        $res = $input - $output;

        //今日概况
        $data = [
            'today_recharge' => $today_recharge,
            'today_activity_recharge' => $today_activity_recharge,
            'today_vip_recharge' => $today_vip_recharge,
            'today_system_recharge' => $today_system_recharge,

            'total_recharge' => $total_recharge,
            'total_activity_recharge' => $total_activity_recharge,
            'total_vip_recharge' => $total_vip_recharge,
            'total_system_recharge' => $total_system_recharge,

            'today_player_pay' => round($today_player_pay, 2),
            'total_player_pay' => round($total_player_pay, 2),

            'agent_pay' => round($agent_pay, 2),
            'all_agent_pay' => round($all_daili_pay, 2),

            'today_consume' => round($consume / 100, 2),
            'all_consume' =>  round($all_consume / 100, 2),

            'deposit' => round($deposit / 100, 2),
            'deposit_root' => round($robot_hundred / 100, 2),

            'today_agent_pay' => $today_agent_pay,
            'robot' => round($robot / 100, 2),

            'monitor' => $res,
            'input' => $input,
            'output' => $output,
            'channel_id'=>$this->channel_id
        ];

        return $this->render('welcome',['data' => $data]);
    }

    /**
     * 实时在线人数
     */
    public function actionTodayPlayer()
    {
        $today = strtotime('today');
        $yesterday = $today - 86400;          //一天前
        $yesterday_1 = $yesterday - 86400;    //二天前
        $yesterday_2 = $yesterday_1 - 86400;  //三天前
        $yesterday_3 = $yesterday_2 - 86400;  //四天前
        $yesterday_4 = $yesterday_3 - 86400;  //五天前
        $yesterday_5 = $yesterday_4 - 86400;  //六天前
        $model = new Index();
        $player[] = $model->convertOnlinePlayer($today,'',$this->channel_id);
        $player[] = $model->convertOnlinePlayer($yesterday, $today,$this->channel_id);
        $player[] = $model->convertOnlinePlayer($yesterday_1, $yesterday,$this->channel_id);
        $player[] = $model->convertOnlinePlayer($yesterday_2, $yesterday_1,$this->channel_id);
        $player[] = $model->convertOnlinePlayer($yesterday_3, $yesterday_2,$this->channel_id);
        $player[] = $model->convertOnlinePlayer($yesterday_4, $yesterday_3,$this->channel_id);
        $player[] = $model->convertOnlinePlayer($yesterday_5, $yesterday_4,$this->channel_id);

        $new_data = [];
        foreach ($player as $key => $val) {
            $new_data[$key]['time'] = array_column($val, 'time', 10000);
            $new_data[$key]['num'] = array_column($val, 'num');
        }

        return $this->writeResult(self::CODE_OK, '', $new_data);

    }

    /**
     * 服务器实际在线人数
     */
    public function actionRealOnline()
    {
        $num = 0;
        $redis = Yii::$app->redis_1;
        if($this->channel_id == 1){
            $num = $redis->hlen('online_user_info') ? : 0;
        }else{
            $redisAllNum = $redis->hkeys('online_user_info');
            $num = count(array_intersect($redisAllNum,$this->channel_under_list));
        }

        $this->writeResult(self::CODE_OK, '', ['data' => $num]);
    }

    /**
     * 新增用户统计
     * (今天注册的用户)
     */
    public function actionNewPeople()
    {
        $start_time = date('Y-m-d', time() - 86400 * 30);
        $end_time = date('Y-m-d', time());

        $info = (new Query())
            ->select(['stat_date', 'dnu'])
            ->from('stat_base_player')
            ->where(['and', "stat_date > '{$start_time}'", "stat_date < '{$end_time}'"])
            ->andWhere(['channel_id'=>$this->channel_id])
            ->orderBy('stat_date DESC')
            ->all();

        $data['time'] = array_column($info, 'stat_date');
        $data['num'] = array_column($info, 'dnu');

        return $this->writeResult(self::CODE_OK, '', $data);
    }

    /**
     * 活跃用户统计
     * (今天登陆的用户)
     */
    public function actionActivePeople()
    {
        $start_time = date('Y-m-d', time() - 86400 * 30);
        $end_time = date('Y-m-d', time());

        $info = (new Query())
            ->select(['stat_date', 'dau'])
            ->from(['stat_base_player'])
            ->where(['and', "stat_date > '{$start_time}'", "stat_date < '{$end_time}'"])
            ->andWhere(['channel_id'=>$this->channel_id])
            ->orderBy('stat_date DESC')
            ->all();

        $data['time'] = array_column($info, 'stat_date');
        $data['num'] = array_column($info, 'dau');

        return $this->writeResult(self::CODE_OK, '', $data);
    }

    /**
     * 充值额度
     * (当天份时间段的充值额度)
     *
     */
    public function actionRechargeRental()
    {
        $start_time = date('Y-m-d', time() - 86400 * 30);
        $end_time = date('Y-m-d', time());

        $info = (new Query())
            ->select(['stat_date', 'amt'])
            ->from('stat_base_recharge')
            ->where(['and', "stat_date > '{$start_time}'", "stat_date < '{$end_time}'"])
            ->andWhere(['channel_id'=>$this->channel_id])
            ->orderBy('stat_date DESC')
            ->all();

        $data['time'] = array_column($info, 'stat_date');
        $data['num'] = array_column($info, 'amt');

        return $this->writeResult(self::CODE_OK, '', $data);
    }

}