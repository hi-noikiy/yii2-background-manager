<?php
/**
 * User: SeaReef
 * Date: 2018/7/9 11:23
 *
 * 父类定时任务
 */
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\db\Query;

class CommonController extends AppController
{
    public $ids='';

    public $daili_subordinate_key = 'daili_subordinate';//代理伞下id列表的redis key

    /*
     * 设置最大执行内存
     */
    protected function setMemory($size = '1024M')
    {
        ini_set('memory_limit', $size);
    }

    /**
     * 最大执行时间
     */
    protected function setTime($size = 600)
    {
        set_time_limit($size);
    }

    /**
     * 统计每日运营数据
     */
    public function actionStatOperate($start_time = '', $end_time = '')
    {
        //设置最大内存、执行时间
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $redis = Yii::$app->redis;
        $start_time = $start_time ? : date('Y-m-d 00:00:00', time() - 86400);
        $end_time = $end_time ? : date('Y-m-d 23:59:59', time() - 86400);
        $stat_date = date('Y-m-d', strtotime($start_time));
        $stat_date_ru1 = date('Y-m-d', strtotime($start_time) - 86400);
        $s_start_time = strtotime($start_time);
        $s_end_time = strtotime($end_time);

        $data = [];
        $list = $this->actionPartherUser();
        $update_ru1 = array();
        foreach ($list as $k => $v) {
            $id_list = $v;
            array_push($id_list, $k);
            $str_id_list = implode(',', $id_list);
            $data[$k] = [
                'regist' => 0,
                'dnu' => 0,
                'all_user' => 0,
                'dau' => 0,
                'pay_user' => 0,
                'ru_1' => 0,
                'recharge' => 0,
                'give' => 0,
                'consume' => 0,
                'gold_bar' => 0,
                'arpu' => 0,
                'arppu' => 0,
                'fencheng' => 0,
                'tiqu' => 0,
            ];
            //新增注册
            $regist = Yii::$app->db->createCommand("SELECT COUNT(*) AS `regist` FROM t_player_member WHERE BIND_TIME BETWEEN '{$s_start_time}' AND '{$s_end_time}' AND PLAYER_INDEX IN ($str_id_list)")->queryScalar();
            if ($regist == false) {
                $regist = 0;
            }
            $data[$k]['regist'] = $regist;

            //新增登录
            $dnu = Yii::$app->db->createCommand("SELECT COUNT(u_id) AS `dnu` FROM login_db.t_lobby_player AS `a` LEFT JOIN t_player_member AS `b` ON a.u_id = b.MEMBER_INDEX WHERE b.PLAYER_INDEX IN ($str_id_list) AND a.reg_time BETWEEN '{$start_time}' AND '{$end_time}'")->queryScalar();
            if (!empty($dnu)) {
                $data[$k]['dnu'] = $dnu;
            } else {
                $data[$k]['dnu'] = 0;
            }

            //所有登录用户
            $all_user = Yii::$app->db->createCommand("SELECT COUNT(u_id) AS `all_user` FROM login_db.t_lobby_player AS `a` LEFT JOIN t_player_member AS `b` ON a.u_id = b.MEMBER_INDEX WHERE b.PLAYER_INDEX IN ($str_id_list)")->queryScalar();
            if (!empty($all_user)) {
                $data[$k]['all_user'] = $all_user;
            } else {
                $data[$k]['all_user'] = 0;
            }

            //DAU
            $all_user_day = Yii::$app->db->createCommand("SELECT COUNT(u_id) AS `dau` FROM login_db.t_lobby_player AS `a` LEFT JOIN t_player_member AS `b` ON a.u_id = b.MEMBER_INDEX WHERE a.last_login_time BETWEEN '{$start_time}' AND '{$end_time}' AND b.PLAYER_INDEX IN ($str_id_list)")->queryScalar();
            if (!empty($all_user_day)) {
                $data[$k]['dau'] = $all_user_day;
            } else {
                $data[$k]['dau'] = 0;
            }

            /** 次日留存 */
            $data['ru1'] = 0;
            $three_day = date('Y-m-d 00:00:00', time() - 86400 * 2);
            $three_day_end = date('Y-m-d 23:59:59', time() - 86400 * 2);
            $s_day = date('Y-m-d 00:00:00', time() - 86400);
            $e_day = date('Y-m-d 23:59:59', time() - 86400);
            $stat_date_ru1 = date('Y-m-d', strtotime($start_time) - 86400);
            //前天新增
            $yesterdayAdd = Yii::$app->db->createCommand("SELECT COUNT(*) FROM login_db.t_lobby_player WHERE reg_time BETWEEN '{$three_day}' AND '{$three_day_end}' AND u_id IN ($str_id_list)")->queryScalar();
            //前天新增增登陆，并且昨日日又登陆的用户数
            $todayLogin = Yii::$app->db->createCommand("SELECT COUNT(*) AS count FROM ( SELECT player_id FROM login_db.t_login AS a LEFT JOIN login_db.t_lobby_player AS b ON a.player_id = b.u_id WHERE a.create_time BETWEEN '{$s_day}' AND '{$e_day}' AND b.reg_time BETWEEN '{$three_day}' AND '{$three_day_end}' AND a.player_id IN ($str_id_list) GROUP BY player_id ) tmp")->queryScalar();
            //前天的次日留存率
            $morrowRetentionRate = $yesterdayAdd == 0 ? 0 : round($todayLogin/$yesterdayAdd,4);
            $update_ru1[] = [
                'ru1' => $morrowRetentionRate,
                'create_time' => $stat_date_ru1,
                'player_id' => $k
            ];

            //充值金额
            $recharge = Yii::$app->db->createCommand("SELECT SUM(f_price) AS `re` FROM lobby_daili.t_order WHERE f_created BETWEEN '{$start_time}' AND '{$end_time}' AND f_uid IN ($str_id_list) AND f_status=1 AND f_type=3")->queryScalar();
            if ($recharge == false) {
                $recharge = 0;
            }
            $data[$k]['recharge'] = $recharge;

            //系统增发
            $give = Yii::$app->db->createCommand("SELECT player_id, gold_num FROM t_service_recharge_log WHERE TIME BETWEEN '{$start_time}' AND '{$end_time}' AND player_id IN ({$str_id_list})")->queryAll();
            if ($give) {
                foreach ($give as $v) {
                    if (in_array($v['player_id'], $id_list)) {
                        $data[$k]['give'] += $v['gold_num'];
                    }
                }
            }else{
                $data[$k]['give']=0;
            }

            //台费消耗
            $table_name = 't_gold_record__' . date('Ymd', time() - 86400);
            $juge = Yii::$app->db->createCommand("show tables ")->queryAll();
            $cun =  $this->deep_in_array($table_name,$juge);
            if($cun){
                $goldRecord = Yii::$app->db->createCommand("SELECT SUM(num) FROM {$table_name} WHERE type = 1 AND player_id IN ($str_id_list)")->queryScalar();
                $goldRecord = $goldRecord ? : 0;
            }else{
                $goldRecord = 0;
            }
            $data[$k]['consume'] = $goldRecord;

            //元宝淤积
            $gold_bar = Yii::$app->db->createCommand("SELECT SUM(gold_bar) FROM login_db.t_lobby_player WHERE u_id IN ($str_id_list)")->queryScalar();
            if ($gold_bar == false) {
                $gold_bar = 0;
            }
            $data[$k]['gold_bar'] = $gold_bar;

            //付费用户
            $pay_user = Yii::$app->db->createCommand("SELECT COUNT(DISTINCT f_uid) AS `pay_user` FROM(SELECT f_uid FROM lobby_daili.t_order AS `a` LEFT JOIN t_player_member AS `b` ON a.f_uid = b.MEMBER_INDEX WHERE a.f_created BETWEEN '{$start_time}' AND '{$end_time}' AND b.PLAYER_INDEX IN ($str_id_list) AND f_status=1 AND f_type=3) AS `tmp`")->queryScalar();

            if (!empty($pay_user)) {
                $data[$k]['pay_user'] = $pay_user;
            } else {
                $data[$k]['pay_user'] = 0;
            }

            if ($data[$k]['pay_user'] == 0) {
                $data[$k]['arpu'] = 0;
            } else {
                $data[$k]['arpu'] = round($data[$k]['recharge'] / $data[$k]['pay_user'], 2);
            }
            if ($data[$k]['dau'] == 0) {
                $data[$k]['arppu'] = 0;
            } else {
                $data[$k]['arppu'] = round($data[$k]['recharge'] / $data[$k]['dau'], 2);
            }

            //代理分成
            $table = 't_income_details';
            $agentArr = Yii::$app->db->createCommand("SELECT SUM(father_num) AS `fencheng1`,SUM(gfather_num) AS `fencheng2`,SUM(ggfather_num) AS `fencheng3` FROM {$table} WHERE FROM_UNIXTIME(create_time) BETWEEN '{$start_time}' AND '{$end_time}' AND player_id IN ($str_id_list)")->queryOne();
            $agent = $agentArr['fencheng1']+$agentArr['fencheng2']+$agentArr['fencheng3'];
            if ($agent == false) {
                $agent = 0;
            }
            $data[$k]['fencheng'] = round($agent/100,2);

            //代理提现
            $tiqu = Yii::$app->db->createCommand("SELECT SUM(PAY_MONEY) AS `money` FROM t_pay_order WHERE CREATE_TIME BETWEEN '{$s_start_time}' AND '{$s_end_time}' AND PLAYER_INDEX IN ($str_id_list)  AND PAY_STATUS=1")->queryScalar();
            if ($tiqu == false) {
                $tiqu = 0;
            }
            $data[$k]['tiqu'] = round($tiqu/100,2);

            /** 新手赠送 */
            $new = Yii::$app->db->createCommand($sql = "SELECT COUNT(player_index) FROM player_log.player_activity_sign WHERE FROM_UNIXTIME(novice_gift_time) >= '{$start_time}' AND FROM_UNIXTIME(novice_gift_time) < '{$end_time}' AND novice_gift_sign = 2 AND gm_sign=1 AND  player_index IN ($str_id_list)")->queryScalar() * 50;
            $data[$k]['new_user'] = $new;

            /** 首冲礼包 */
            $first_recharge = Yii::$app->db->createCommand("SELECT SUM(f_num) FROM lobby_daili.t_order WHERE f_charge_id = 239 AND f_status = 1 AND f_type = 3 AND f_created >= '{$start_time}' AND f_created < '{$end_time}' AND f_uid IN ($str_id_list)")->queryScalar();
            $data[$k]['fist_recharge'] = $first_recharge;
        }

        foreach ($data as $k => $v) {
            Yii::$app->db->createCommand("INSERT INTO t_parther_stat VALUES(NULL, '{$k}', '{$stat_date}', '{$v['regist']}', '{$v['dnu']}', '{$v['ru_1']}', '{$v['all_user']}', '{$v['dau']}', '{$v['recharge']}', '{$v['give']}', '{$v['gold_bar']}', '{$v['consume']}', '{$v['pay_user']}', '{$v['arpu']}', '{$v['arppu']}', '{$v['fencheng']}', '{$v['tiqu']}', '{$v['new_user']}', '{$v['fist_recharge']}')")->execute();

            echo 'success';exit;
        }

        foreach ($update_ru1 as $v) {
            Yii::$app->db->createCommand($sql = "UPDATE t_parther_stat SET ru1 = '{$v['ru1']}' WHERE create_time = '{$v['create_time']}' AND u_id = '{$v['player_id']}'")->execute();
        }

    }

    /**
     * 获取渠道合伙人下所有用户信息
     */
    public function actionPartherUser()
    {
        $parther=array();
        $list = $this->actionChannelParther();
        foreach ($list as $user) {
            $parther[$user['uid']] = $this->getMemberTree($user['uid']);
        }

        return $parther;
    }

    /**
     * 获取渠道合伙人列表
     */
    public function actionChannelParther()
    {
        return (new Query())
            ->select('player_id AS uid')
            ->from('t_daili_player')
            ->where(['daili_level' => 1])
            ->all();
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
        $member_list = (new Query())->select('MEMBER_INDEX')->from('t_player_member')->where(['in', 'PLAYER_INDEX', $res])->all();
        if(!empty($member_list)){
            $member_list = array_column($member_list,'MEMBER_INDEX');
            $has = $this -> memberTree($member_list,$has,$level+1);
        }
        return $has;
    }

    /**
     * 缓存所有代理的伞下玩家列表
     *
     */
    public function actionCatchDailiMember(){
        $redis = Yii::$app->redis_3;
        $db = Yii::$app->db;
        $dailiCount = $db->createCommand("SELECT count(id) as count FROM t_daili_player")->queryScalar();

        $limit = 100;
        $page = ceil($dailiCount/$limit);

        for ($i=0;$i<$page;$i++){
            $daili = (new Query())
                ->select('player_id')
                ->from('t_daili_player')
                ->where('player_id != 999')
                ->limit($limit)
                ->offset(($i - 1) * $limit)
                ->all();

            if($daili){
                foreach ($daili as $k=>$val){
                    $this->getLowerStr($val['player_id'],'');
                    $ids = rtrim($this->ids,',');
                    $redis->hset($this->daili_subordinate_key,$val['player_id'],$ids);
                }
            }
        }
    }

    /**
     * 获取所有代理下级id
     *
     * @param $playerId
     * @param string $ids
     * @return string
     */
    public function getLowerStr($playerId,$ids){
        $this->ids = $ids;
        $con = 'PLAYER_INDEX='.$playerId;
        $lowers = (new Query())
            ->select('MEMBER_INDEX')
            ->from('t_player_member')
            ->where($con)
            ->all();
        if($lowers){
            foreach ($lowers as $key=>$v){
                $this->ids .= $v['MEMBER_INDEX'].',';
                self::getLowerStr($v['MEMBER_INDEX'],$this->ids);
            }
        }
    }
}