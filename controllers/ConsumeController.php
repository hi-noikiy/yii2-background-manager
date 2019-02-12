<?php
/**
 * User: SeaReef
 * Date: 2018/9/4 21:05
 *
 * 消费分析
 */
namespace app\controllers;

use app\common\Code;
use Yii;
use yii\db\Query;

class ConsumeController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    public $layout = 'layui';

    /**
     * 鲸鱼用户
     */
    public function actionWhaleUser()
    {
        $stat_date = date('Y-m-d', time() - 86400);

        return $this->render('whale_user', [
            'stat_date' => $stat_date,
        ]);
    }

    /**
     * 鲸鱼用户接口
     */
    public function actionWhaleUserApi()
    {
        $request = Yii::$app->request;
        $stat_date = $request->get('stat_date') ? : date('Y-m-d', time() - 86400);
        $limit = $request->get('limit') ? : 10;
        $page = $request->get('page', 1);
        $field = $request->get('field', 'consume');
        $order = $request->get('order', 'desc');

        $data = (new Query())
            ->select(["CONCAT(top_id, '~', top_name) AS top", "CONCAT(parent_id, '~', parent_name) AS parent", "CONCAT(player_id, '~', player_name) AS player", 'consume', 'recharge', 'duihuan', 'sz', 'br_ttz', 'ps', 'ttz', 'regist', 'stat_date','win_lose'])
            ->from('log_consume_rank')
            ->where(['stat_date' => $stat_date])
            ->andFilterWhere(['in','player_id',$this->channel_under_list])
            ->orderBy("$field $order")
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();

        $count = (new Query())
            ->from('log_consume_rank')
            ->where(['stat_date' => $stat_date])
            ->andFilterWhere(['in','player_id',$this->channel_under_list])
            ->count();

        $this->writeLayui(Code::OK, '', $count, $data);
    }

    /**
     * 子游戏消耗
     */
    public function actionSubGame()
    {
        $start_date = date('Y-m-d', time() - 86400 * 30);
        $end_date = date('Y-m-d', time() - 86400);

        return $this->render('sub_game', [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'channel_id'=>$this->channel_id
        ]);
    }

    /**
     * 子游戏消耗接口
     */
    public function actionSubGameApi()
    {
        $request = Yii::$app->request;
        $start_date = $request->get('start_date') ? $request->get('start_date') . ' 00:00:00' : date('Y-m-d 00:00:00', time() - 86400 * 30);
        $end_date = $request->get('end_date') ? $request->get('end_date') . ' 23:59:59' : date('Y-m-d 23:59:59', time() - 86400);
        $limit = $request->get('limit') ? : 10;
        $page = $request->get('page', 1);
        $field = $request->get('field', 'stat_date');
        $order = $request->get('order', 'desc');

        $data = (new Query())
            ->select(['stat_date', 'consume', 'br_ttz', 'sz', 'ps', 'ttz','br_ttz_player_number', 'sz_player_number', 'ps_player_number', 'ttz_player_number'])//, 'gg'
            ->from('stat_sub_consume')
            ->where(['and', "stat_date >= '{$start_date}'", "stat_date < '{$end_date}'"])
            ->andWhere(['channel_id'=>$this->channel_id])
            ->orderBy("$field $order")
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();

        foreach ($data as $key=>$val){
            $yesDate = date('Y-m-d',strtotime($val['stat_date'])-86400);
            $yesData = (new Query())->select(['br_ttz', 'sz', 'ps', 'ttz','br_ttz_player_number', 'sz_player_number', 'ps_player_number', 'ttz_player_number'])
                ->from('stat_sub_consume')
                ->where(['stat_date'=>$yesDate])
                ->andWhere(['channel_id'=>$this->channel_id])
                ->one();
            if($yesData){
                $data[$key]['br_ttz_consume_contrast']  = $this->disContrast($val['br_ttz'],$yesData['br_ttz']);
                $data[$key]['ps_consume_contrast']  = $this->disContrast($val['ps'],$yesData['ps']);
                $data[$key]['sz_consume_contrast']  = $this->disContrast($val['sz'],$yesData['sz']);
                $data[$key]['ttz_consume_contrast']  = $this->disContrast($val['ttz'],$yesData['ttz']);

                $data[$key]['ps_number_contrast']  = $this->disContrast($val['ps_player_number'],$yesData['ps_player_number']);
                $data[$key]['br_ttz_number_contrast']  = $this->disContrast($val['br_ttz_player_number'],$yesData['br_ttz_player_number']);
                $data[$key]['sz_number_contrast']  = $this->disContrast( $val['sz_player_number'],$yesData['sz_player_number']);
                $data[$key]['ttz_number_contrast']  = $this->disContrast($val['ttz_player_number'],$yesData['ttz_player_number']);
            }else{
                $data[$key]['ps_consume_contrast']  = 0;
                $data[$key]['br_ttz_consume_contrast']  = 0;
                $data[$key]['sz_consume_contrast']  = 0;
                $data[$key]['ttz_consume_contrast']  = 0;
                $data[$key]['ps_number_contrast']  = 0;
                $data[$key]['br_ttz_number_contrast']  = 0;
                $data[$key]['sz_number_contrast']  = 0;
                $data[$key]['ttz_number_contrast']  = 0;
            }

        }
        $count = (new Query())
            ->from('stat_sub_consume')
            ->where(['and', "stat_date >= '{$start_date}'", "stat_date < '{$end_date}'"])
            ->andWhere(['channel_id'=>$this->channel_id])
            ->count();

        $this->writeLayui(Code::OK, '', $count, $data);
    }


    public function disContrast($d1,$d2,$n=4){
        $d2 = $d2 ?: 1;
        $str = ($d1-$d2)/$d2;
        $num = Number_format(explode('.',$str*10000)[0]/10000,$n);
        return ($num*100)."%";
    }
}