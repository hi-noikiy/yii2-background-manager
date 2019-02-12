<?php
/**
 * User: SeaReef
 * Date: 2018/9/3 20:56
 *
 * 运营统计
 */
namespace app\controllers;

use app\common\Code;
use Yii;
use yii\db\Query;

class OperateController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 元宝淤积报警
     */
    public function actionGoldAlert()
    {
        return $this->render('gold_alert');
    }

    /**
     * 元宝淤积数据
     */
    public function actionGetGoldAlert()
    {
        $request = Yii::$app->request;
        $page = $request->get('page');
        $limit = $request->get('limit');

        $count = (new Query())
            ->from('log_gold_alert')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->count();

        $data = (new Query())
            ->select('*')
            ->from('log_gold_alert')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->orderBy('create_time DESC')
            ->all();

        $this->writeJson(1, self::CODE_OK, '', $count, $data);
    }


    /**
     * 返利透明化
     */
    public function actionRebateDetails()
    {
        return $this->render('rebate_details');
    }

    public function actionGetRebateDetails()
    {
        $request = Yii::$app->request;
        $page = $request->get('page');
        $limit = $request->get('limit');

        $count = (new Query())
            ->from('t_income_details')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->count();

        $data = (new Query())
            ->select('*, FROM_UNIXTIME(create_time) AS `create_time`')
            ->from('t_income_details')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->orderBy('create_time DESC')
            ->all();

        $this->writeJson(1, self::CODE_OK, '', $count, $data);
    }

    /**
     * 玩家金币日志
     */
    public function actionGoldLog()
    {
        return $this->render('gold_log');
    }

    /**
     * 获取玩家金币日志
     */
    public function actionGetGoldLog()
    {
        if(Yii::$app->request->isPost){
            if(Yii::$app->request->post()){
                $request = Yii::$app->request->post();
                $limit = $request['limit'];
                $page = $request['page'];
                if(isset($request['field']) && isset($request['order'])){
                    $field = $request['field'];
                    $orderType = $request['order'];
                }else{
                    $field = 'CREATE_TIME';
                    $orderType = 'desc';
                }

                $date = date("Ymd");
                $tableName = "t_lobby_player_log__".$date;
                if(isset($request['date']) && $request['date']){
                    $thisDate = date("Ymd",strtotime($request['date']));
                    $tableName = "t_lobby_player_log__".$thisDate;
                }
                $where="";
                if(isset($request['playerId']) && $request['playerId']){
                    $where = "PLAYER_ID = ".$request['playerId'];
                }
                try{
                    $count = (new Query())
                        ->from('player_log.'.$tableName)
                        ->where($where)
                        ->andFilterWhere(['in','PLAYER_ID',$this->channel_under_list])
                        ->count();

                    $data = (new Query())
                        ->select('*')
                        ->from('player_log.'.$tableName)
                        ->where($where)
                        ->andFilterWhere(['in','PLAYER_ID',$this->channel_under_list])
                        ->orderBy($field.' '.$orderType)
                        ->offset(($page - 1) * $limit)
                        ->limit($limit)
                        ->all();

                    $this->writeLayui(self::CODE_OK, 'success', $count, $data);
                }catch (\Exception $exception){
                    $this->writeLayui(self::CODE_OK, 'success', 0, []);
                }

            }
        }else{
            $this->writeLayui(self::CODE_ERROR, '请求失败！');
        }

    }

    /**
     * 玩家金币日志操作记录
     */
    public function actionOpGoldLog()
    {
        $request = Yii::$app->request;
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);

        $count = (new Query())
            ->from('oss.log_operation')
            ->count();

        $data = (new Query())
            ->select('*')
            ->from('oss.log_operation')
            ->offset(($page - 1) * $limit)
            ->limit($limit)
            ->all();

        $this->writeLayui(self::CODE_LAYUI_OK, '', $count, $data);
    }
}