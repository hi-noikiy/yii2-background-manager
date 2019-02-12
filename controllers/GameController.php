<?php
/**
 * User: SeaReef
 * Date: 2018/8/2 14:23
 *
 * 游戏相关日志
 */
namespace app\controllers;

use Yii;
use yii\db\Query;
use app\common\MatchCard;

class GameController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 游戏战绩日志
     */
    public function actionGameLog()
    {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $page = $request['page'];
            $limit = $request['limit'];

            $where = '';
            if (isset($request['start_time']) && $request['start_time']) {
                $where[] = 'start_time >= '." ' ".$request['start_time']." ' ";
            }

            if (isset($request['end_time']) && $request['end_time']) {
                $where[] = 'end_time <= '." ' ".$request['end_time']." ' ";
            }
            if (isset($request['player_id']) && $request['player_id']) {
                $record_id = (new Query())
                    ->select('record_id')
                    ->from('log_game_player_record')
                    ->where('player_id = '. $request['player_id'])
                    ->all();
                $where[] = ' id in ('.implode(',',array_column($record_id,'record_id')).')';
            }

            if (isset($request['channel_id']) && $request['channel_id']) {
                $where[] = ' channel_id = '.$request['channel_id'];
            }

            if (isset($request['gid']) && $request['gid']) {
                $where[] = ' gid = '.$request['gid'];
            }

            if (isset($request['table_id']) && $request['table_id']) {
                $where[] = ' table_id = '.$request['table_id'];
            }
            if ($where) {
                $where = implode(' and ',$where);
            }
            $data = (new Query())
                ->select('*')
                ->from('log_game_record')
                ->where($where)
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->all();
            //查询方法二
//            $db = Yii::$app->db;
//            $data = $db->createCommand("SELECT * FROM `log_game_record` WHERE ".$where)->queryAll();

            $count = (new Query())
                ->select('*')
                ->from('log_game_record')
                ->where($where)
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->count();

            $this->writeLayui(self::CODE_LAYUI_OK, '', $count, $data);
        } else {
            return $this->render('game_log');
        }
    }

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
                ->where('record_id = '.$record_id)
                ->orderBy('created_time asc')
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->all();

            //转换牌型
            $games = Yii::$app->params['player_card_convert'];
            if(in_array($game_id,$games)){
                foreach ($rows as $key=>$value){
                    $matchCard = new MatchCard();
                    $rows[$key]['player_card'] = $matchCard->getCarsName($game_id,$value['player_card']);
                }
            }

            $count = (new Query())
                ->select('*')
                ->from('log_game_player_record')
                ->where('record_id = '.$record_id)
                ->count();
            $this->writeLayui(self::CODE_LAYUI_OK, '', $count, $rows?$rows:[]);
        } else {
            $this->writeResult(self::CODE_PARAMS_ERROR);
        }

    }

    /**
     * 金币日志
     */
    public function actionPlayerGoldLog()
    {
        $page = Yii::$app->request->post('page',1);
        $limit = Yii::$app->request->post('limit',10);
        $request = Yii::$app->request->post();
        $where []= 'id > 0';
        if (isset($request['player_id']) && $request['player_id']) {
            $where[] = 'player_id = '.$request['player_id'];
        }
        if (isset($request['date']) && $request['date']) {
            $where[] = 'unix_timestamp(create_time) >= '.strtotime($request['date']);
            $where[] = 'unix_timestamp(create_time) < '.(strtotime($request['date'])+86400);
        }
        $where = implode(' and ',$where);
        $rows = (new Query())
            ->select('*')
            ->from('player_log.t_lobby_player_log__'.date('Ymd',strtotime($request['date'])))
            ->where($where)
            ->orderBy('create_time desc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->all();
        $count = (new Query())
            ->select('*')
            ->from('player_log.t_lobby_player_log__'.date('Ymd',strtotime($request['date'])))
            ->where($where)
            ->count();
        $this->writeLayui(self::CODE_OK,'',$count,$rows?$rows:[]);
    }

    /**
     * 玩法参与统计
     */
    public function actionGamePlayIndex()
    {
        $games = Yii::$app->params['games'];
        return $this->render('game_play',['games'=>$games]);
    }

    /**
     *玩法参与统计
     */
    public function actionGamePlay()
    {
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        $request = Yii::$app->request->get();
        $where = [];
        if (isset($request['start_time']) && $request['start_time']) {
            $where[] = 'unix_timestamp(stat_date) >='. strtotime($request['start_time']);
        }
        if (isset($request['end_time']) && $request['end_time']) {
            $where[] = 'unix_timestamp(stat_date) <='.strtotime($request['end_time']);
        }
        if (isset($request['game_id']) && $request['game_id']) {
            $where[] = 'game_id = '.$request['game_id'];
        }
        $where = implode(' and ',$where);

        $rows = (new Query())
            ->select('*')
            ->from('stat_gameplay')
            ->where($where)
	    ->andWhere(['channel_id'=>$this->channel_id])
            ->orderBy('stat_date desc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->all();

        foreach ($rows as $key=>$val){
            $allExpend = $this->getAllGameExpend($val['stat_date']);
            if(!$allExpend){
                $allExpend=1;
            }
            $percentage = round($val['consume']/$allExpend,4);
            $rows[$key]['percentage'] = ($percentage*100).'%';
            if($val['ratio_number'] > 1 && $val['ratio_number'] != 0){
                $rows[$key]['ratio_number'] = $this->up.($val['ratio_number']*100).'%';
            }else{
                $rows[$key]['ratio_number'] = $this->down.($val['ratio_number']*100).'%';
            }
            if($val['ratio_times'] > 1 && $val['ratio_times'] != 0){
                $rows[$key]['ratio_times'] = $this->up.($val['ratio_times']*100).'%';
            }else{
                $rows[$key]['ratio_times'] = $this->down.($val['ratio_times']*100).'%';
            }

            if($val['ratio_times'] == 0){
                $rows[$key]['ratio_times'] = "0%";
            }
            if($val['ratio_number'] == 0){
                $rows[$key]['ratio_number'] = "0%";
            }

        }

        $count = (new Query())
            ->select('*')
            ->from('stat_gameplay')
            ->where($where)
	    ->andWhere(['channel_id'=>$this->channel_id])
            ->count();
        $this->writeLayui(0,'success',$count,$rows?$rows:[]);

    }
}
