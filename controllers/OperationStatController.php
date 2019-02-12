<?php
/**
 * User: SeaReef
 * Date: 2018/9/4 21:33
 *
 * 运营统计
 */
namespace app\controllers;

use app\common\Code;
use app\common\Common;
use app\common\RedisKey;
use app\models\LogDownload;
use Yii;
use yii\db\Query;
use app\common\MatchCard;
use app\models\StatConsume;
use app\models\OperUserDay;


class OperationStatController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 每日运营统计
     */
    public function actionDayOpStat()
    {
        if(Yii::$app->request->isPost){
            if(Yii::$app->request->post()){
                $request = Yii::$app->request->post();

//                $page = $request['page'];
//                $limit = $request['limit'];
//
//                $where=[];
//                if(isset($request['startTime']) && $request['startTime']){
//                    $where[] = "create_time >= '".$request['startTime']."'";
//                }
//                if(isset($request['endTime']) && $request['endTime']){
//                    $where[] = "create_time < '".$request['endTime']."'";
//                }
//
//                $where = implode(" and ",$where);
//                $model = new OperUserDay();
//                $data = $model->getAll($where,$page,$limit);
//
//                foreach ($data as $key=>$val){
//                    $data[$key]['ru1'] = ($val['ru1'] * 100).'%';
//                    //每日库收入
//                    $todayStar = $val['create_time'];
//                    $todayEnd = $todayStar.' 23:59:59';
//                    $condition = "date >= '{$todayStar}' and date <= '{$todayEnd}'";
//
//                    $libraryIncome = (new Query())
//                        ->select('income_gold')
//                        ->from('log_hundred_game_day_record')
//                        ->where($condition)
//                        ->scalar();
//                    $data[$key]['income_gold'] = $libraryIncome;
//                }
//
//                $count = $model->getAllCount($where);
                $page = $request['page'];
                $limit = $request['limit'];
                if(isset($request['field']) && isset($request['order'])){
                    $field = $request['field'];
                    $orderType = $request['order'];
                }else{
                    $field = 'stat_date';
                    $orderType = 'desc';
                }
                $where = [];
                if (isset($request['startTime']) && $request['startTime']) {
                    $where[] = "stat_date >= '".$request['startTime']."'";
                }
                if(isset($request['endTime']) && $request['endTime']){
                    $where[] = "stat_date < '".$request['endTime']."'";
                }

                //获取当前渠道id
                $channelId = Yii::$app->redis->get(RedisKey::CHANNEL_ID.Yii::$app->user->id);
                $table = $channelId == 1 ? 'view_oper_stat' : 'view_oper_stat_channel';
                $table = 'view_oper_stat';
                $filterWhere = [];
                if($channelId != 1){
                    $table = 'view_oper_stat_channel';
                    $filterWhere = ['channel_id'=>$channelId];
                }
                $where = implode(" and ",$where);

                $data = (new Query())
                    ->select('*')
                    ->from($table)
                    ->where($where)
		            ->andFilterWhere($filterWhere)
                    ->limit($limit)
                    ->offset(($page - 1) * $limit)
                    ->orderBy($field.' '.$orderType)
                    ->all();
                foreach ($data as $k => $v) {
                    $data[$k]['ru_1'] = $v['dnu'] == 0 ? '0%' : $v['ru_1'] . '人' . round(($v['ru_1'] / $v['dnu'] ? : 0) * 100, 2) . '%';
                    $data[$k]['ru_2'] = $v['dnu'] == 0 ? '0%' : $v['ru_2'] . '人' . round(($v['ru_2'] / $v['dnu'] ? : 0) * 100, 2) . '%';
                    $data[$k]['ru_7'] = $v['dnu'] == 0 ? '0%' : $v['ru_7'] . '人' . round(($v['ru_7'] / $v['dnu'] ? : 0) * 100, 2) . '%';
                }
                $count = (new Query())
                    ->from($table)
                    ->where($where)
		    ->andFilterWhere($filterWhere)
                    ->count();

                $this->writeLayui(Code::OK,'success',$count,$data);

            }else{
                $this->writeResult(self::CODE_ERROR,'请求错误！');
            }
        }

        return $this->render('day_op_stat');
    }

    /**
     * 代理开局统计
     */
    public function actionAgentOpen()
    {
        return $this->render('agent_open');
    }

    /**
     * 玩法参与统计
     */
    public function actionPlay()
    {
        return $this->render('play');
    }

    /**
     * 玩家输赢统计
     */
    public function actionWinLose()
    {
        $games = Yii::$app->params['games'];
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $page = $request['page'];
            $limit = $request['limit'];
            if(isset($request['field']) && isset($request['order'])){
                $field = $request['field'];
                $orderType = $request['order'];
            }else{
                $field = 'stat_date';
                $orderType = 'desc';
            }

            $where = [];
            if (isset($request['search_time']) && $request['search_time']) {
                $where[] = 'stat_date >= ' . "'" . $request['search_time'] . "'";
                $where[] = 'stat_date <= ' . "'" . $request['search_time'].' 23:59:59' . "'";
            }

            if (isset($request['gid']) && $request['gid']) {
                $where[] = 'game_id = ' . $request['gid'];
            }

            if (isset($request['player_id']) && $request['player_id']) {
                $where[] = 'player_id = ' . $request['player_id'];
            }

            if ($where) {
                $where = implode(' and ', $where);
            }
            $data = (new Query())
                ->select('*')
                ->from('t_win_lose')
                ->where($where)
                ->andFilterWhere(['in','player_id',$this->channel_under_list])
                ->orderBy($field.' '.$orderType)
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->all();

            foreach ($data as $key=>$val){
                $data[$key]['grossYield'] = abs($val['win']) - abs($val['lose']);;
            }

            $count = (new Query())
                ->select('*')
                ->from('t_win_lose')
                ->where($where)
                ->andFilterWhere(['in','player_id',$this->channel_under_list])
                ->count();

            $this->writeLayui(Code::OK, 'success', $count, $data);
        }
        return $this->render('win_lose',['games'=>$games]);
    }

    /**
     * 游戏战绩
     */
    public function actionGameLog()
    {
	ini_set ('memory_limit', '1024M');
        $games = Yii::$app->params['games'];

        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $page = $request['page'];
            $limit = $request['limit'];
            if(isset($request['field']) && isset($request['order'])){
                $field = 'log_game_record.'.$request['field'];
                $orderType = $request['order'];
            }else{
                $field = 'log_game_record.start_time';
                $orderType = 'desc';
            }

            $where = [];
            if (isset($request['start_time']) && $request['start_time']) {
                $where[] = 'start_time >= ' . " ' " . $request['start_time'] . " ' ";
            }

            if (isset($request['end_time']) && $request['end_time']) {
                $where[] = 'end_time <= ' . " ' " . $request['end_time'] . " ' ";
            }

            if (isset($request['player_id']) && $request['player_id']) {
                $record_id = (new Query())
                    ->select('record_id')
                    ->from('log_game_player_record')
                    ->where('player_id = ' . $request['player_id'])
                    ->andFilterWhere(['in','player_id',$this->channel_under_list])
                    ->all();

                if (count($record_id) != 0){
                    $where[] = 'id in (' . implode(',', array_column($record_id, 'record_id')) . ')';
                }else{
                    $this->writeLayui(Code::OK, 'success', 0, []);
                }
            }

            if (isset($request['channel_id']) && $request['channel_id']) {
                $where[] = 'channel_id = ' . $request['channel_id'];
            }

            if (isset($request['gid']) && $request['gid']) {
                $where[] = 'gid = ' . $request['gid'];
            }


            if (isset($request['table_id']) && $request['table_id']) {
                $where[] = 'table_id = ' . $request['table_id'];
            }

            if ($where) {
                $where = implode(' and ', $where);
            }

            $recordFilterWhere='';
            if($this->channel_under_list){
                $recordFilterWhere = "and player_id in(".implode(',',$this->channel_under_list).")";
            }
            $date = date("Y-m-d",strtotime($request['start_time']));
            $db = Yii::$app->db;
            $recordId = $db->createCommand("SELECT record_id FROM log_game_player_record WHERE DATE_FORMAT(updated_time,'%Y-%m-%d')='{$date}' {$recordFilterWhere} GROUP BY record_id")->queryAll();
//            $recordId = (new Query())
//                ->select('record_id')
//                ->from('log_game_player_record')
//                ->where($recordWhere)
//                ->filterWhere(['in','player_id',$this->channel_under_list])
//                ->groupBy('record_id')
//                ->all();
            $recordIdList = array_unique(array_column($recordId,'record_id'));
            $data = (new Query())
                ->select('id,channel_id,gid,table_id,dizhu,start_time,end_time,player_num')
                ->from('log_game_record')
                ->where($where)
                ->andWhere(['in','id',$recordIdList])
                ->orderBy($field.' '.$orderType)
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->all();

            $count = (new Query())
                ->select('id')
                ->from('log_game_record')
                ->where($where)
                ->andWhere(['in','id',$recordIdList])
                ->count();

            $this->writeLayui(Code::OK, 'success', $count, $data);
        }

        return $this->render('game_log', ['games' => $games]);
    }

    /**
     * 玩家战绩
     *
     */
    public function actionPlayerGameLog()
    {
        $request = Yii::$app->request->post();
        if (isset($request['record_id']) && $request['record_id']) {
            $record_id = $request['record_id'];
            $game_id = $request['game_id'];
            $page = $request['page'];
            $limit = $request['limit'];

            $rows = (new Query())
                ->select('*')
                ->from('log_game_player_record')
                ->where('record_id = ' . $record_id)
                ->orderBy('created_time asc')
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->all();

            //转换牌型
            $games = Yii::$app->params['player_card_convert'];
            if (in_array($game_id, $games)) {
                foreach ($rows as $key => $value) {
                    $matchCard = new MatchCard();
                    $rows[$key]['player_card'] = $matchCard->getCarsName($game_id, $value['player_card']);

                    $identity='';$tablePos='';
                    if($game_id == '524822'){//斗地主的地主，平民放在牌桌位置里面，十位数是1为底注 2为平民 个位数为本身牌桌位置
                        if($value['table_pos'] && $value['table_pos'] >= 10){
                            $identityNum = substr($value['table_pos'],0,1);//截取十位数
                            $tablePosNum = substr($value['table_pos'],1,1);//截取个位数
                            $identity = $identityNum == 1 ? '地主' : '平民';
                            $tablePos = $tablePosNum == 0 ? '房主' : '玩家'.$tablePosNum;
                        }
                    }else{
                        $tablePos = $value['table_pos'] == 0 ? '房主' : '玩家'.$value['table_pos'];
                    }

                    $rows[$key]['table_pos'] = $tablePos;
                    $rows[$key]['identity'] = $identity;
                }
            }

            $count = (new Query())
                ->select('*')
                ->from('log_game_player_record')
                ->where('record_id = ' . $record_id)
                ->count();
            $this->writeLayui(Code::OK, 'success', $count, $rows ? $rows : []);
        } else {
            $this->writeResult(Code::CODE_PARAMS_ERROR);
        }

    }

    /**
     * 游戏日报
     */
    public function actionGameDailyCount()
    {

        $data = array();
        $startTime = '';
        $endTime = '';
        $game_id = '';
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $where[] = "level != 'all' ";
            if (isset($request['startTime']) && $request['startTime']) {
                $startTime = $request['startTime'];
            } else {
                echo "<script>alert('开始时间不能为空！');</script>";
            }
            if (isset($request['endTime']) && $request['endTime']) {
                $endTime = $request['endTime'];
            } else {
                echo "<script>alert('结束时间不能为空！');</script>";
            }
            if (isset($request['game_id']) && $request['game_id']) {
                $game_id = $request['game_id'];
                $where[] = 'gid = '.$request['game_id'];
            } else {
                echo "<script>alert('游戏id不能为空！');</script>";
            }
            if($startTime == $endTime){
                $startTime = $startTime." 00:00:00";
                $endTime = $endTime." 23:59:59";
            }

            //区间内所有的日期
            $ret = $this->Date_segmentation($startTime,$endTime);
            $date = $ret['days_list'];

            //获取子游戏对应的redis底注
            $key = "stake_" . $request['game_id'];
            $dizhu = [];
            if (isset(Yii::$app->params[$key])) {
                $dizhu = Yii::$app->params[$key];
            } else {
                echo "<script>alert('不支持该游戏！');history.go(-1)</script>";
            }

            $info = [];
            foreach ($dizhu as $key => $val) {
                $info[$val]['active'] = array();//活跃人数
                $info[$val]['consume'] = array();//消耗
                $info[$val]['prop'] = array();//消耗占比
                $info[$val]['ring_ratio'] = array();//消耗环比

                $where[] = 'level='.$key;

                for ($i=0;$i<count($date);$i++) {
                    $where[] = "stat_date = '" . $date[$i] . "'";
                    $con = implode(' and ', $where);
                    $model = new StatConsume();
                    $return = $model->getOne($con);

                    if ($return) {
                        $info[$val]['active'][] = $return['active'];
                        $info[$val]['consume'][] = $return['consume'];
                        $info[$val]['prop'][] = $return['prop'];
                        if($return['ring_ratio'] > 1){
                            $ring_ratio = $this->up.($return['ring_ratio']*100)."%";
                        }else{
                            $ring_ratio = $this->down.($return['ring_ratio']*100)."%";
                        }
                        $info[$val]['ring_ratio'][] = $ring_ratio;
                    } else {
                        $info[$val]['active'][] = 0;
                        $info[$val]['consume'][] = 0;
                        $info[$val]['prop'][] = 0;
                        $info[$val]['ring_ratio'][] = "0%";
                    }

                    //重置查询日期
                    array_pop($where);
                }

                //重置查询底注
                array_pop($where);
            }

            $data['date'] = array_unique($date);
            $data['dizhu'] = $dizhu;
            $data['info'] = $info;
        }

        $games = Yii::$app->params['games'];

        return $this->render('game_daily_count', ['games' => $games, 'data' => $data, 'endTime' => $endTime, 'startTime' => $startTime, 'game_id' => $game_id]);
    }

    /**
     * 游戏周报
     */
    public function actionWeekReport()
    {
        return $this->render('week_report');
    }

    /**
     * 游戏月报
     */
    public function actionMonthReport()
    {
        return $this->render('month_report');
    }

    /**
     * 分享下载统计
     *
     */
    public function actionShareStat(){
        $request = $this->checkRequestWay(1);
        if($request){
            $common = new Common();
            $date = $common->disposeTemporalInterval($request['start_time'],$request['end_time']);

            $startTime = $date['startTime'];
            $endTime = $date['endTime'];

            $model = new LogDownload();
            $con[] = "create_time >='".$startTime."'";
            $con[] = "create_time <='".$endTime."'";

            $data = $model->getData($con,"*");

            $onlyLoginIos = 0;
            $onlyLoginAndroid = 0;
            $downloadIos = 0;
            $downloadAndroid = 0;
            foreach ($data as $key=>$val){
                if($val['op_type'] == 2){
                    if($val['termail'] == 'ios'){
                        $downloadIos++;
                    }
                    if($val['termail'] == 'android'){
                        $downloadAndroid++;
                    }
                }
                if($val['op_type'] == 1){
                    if($val['termail'] == 'ios'){
                        $onlyLoginIos++;
                    }
                    if($val['termail'] == 'android'){
                        $onlyLoginAndroid++;
                    }
                }
            }

            $data['onlyLoginIos'] = $onlyLoginIos;
            $data['downloadIos'] = $downloadIos;
            $data['onlyLoginAndroid'] = $onlyLoginAndroid;
            $data['downloadAndroid'] = $downloadAndroid;
            $data['typeName'] = array('ios浏览','ios下载','安卓浏览','安卓下载');

            $this->writeResult(Code::OK,'success',$data);
        }

        $this->render('share_stat');
    }


}
