<?php
/**
 * User: SeaReef
 * Date: 2018/12/25 19:46
 *
 * 玩家统计分析
 */
namespace app\controllers;

use app\common\Code;
use Yii;
use yii\db\Query;

class PlayerStatController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    public $layout = 'layui';

    /**
     * 玩家留存分析
     */
    public function actionRu()
    {
        $start_date = date('Y-m-d', time() - 86400 * 30);
        $end_date = date('Y-m-d', time() - 86400);

        return $this->render('ru', [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    /**
     * 留存分析接口
     */
    public function actionRuApi()
    {
        $request = Yii::$app->request;
        $start_date = $request->get('start_date') ? $request->get('start_date') . ' 00:00:00' : date('Y-m-d 00:00:00', time() - 86400 * 30);
        $end_date = $request->get('end_date') ? $request->get('end_date') . ' 23:59:59' : date('Y-m-d 23:59:59', time() - 86400);
        $limit = $request->get('limit') ? : 10;
        $page = $request->get('page', 1);
        $field = $request->get('field', 'stat_date');
        $order = $request->get('order', 'desc');

        $data = (new Query())
            ->select(['stat_date', 'all_user', 'dnu', "CONCAT(ru_1, '~', ROUND(ru_1/dnu*100, 2), '%') AS ru_1", "CONCAT(ru_2, '~', ROUND(ru_2/dnu*100, 2), '%') AS ru_2", "CONCAT(ru_3, '~', ROUND(ru_3/dnu*100, 2), '%') AS ru_3", "CONCAT(ru_4, '~', ROUND(ru_4/dnu*100, 2), '%') AS ru_4", "CONCAT(ru_5, '~', ROUND(ru_5/dnu*100, 2), '%') AS ru_5", "CONCAT(ru_6, '~', ROUND(ru_6/dnu*100, 2), '%') AS ru_6", "CONCAT(ru_7, '~', ROUND(ru_7/dnu*100, 2), '%') AS ru_7", "CONCAT(ru_14, '~', ROUND(ru_14/dnu*100, 2), '%') AS ru_14", "CONCAT(ru_30, '~', ROUND(ru_30/dnu*100, 2), '%') AS ru_30", "CONCAT(ru_60, '~', ROUND(ru_60/dnu*100, 2), '%') AS ru_60"])
            ->from('stat_base_player')
            ->where(['and', "stat_date >= '{$start_date}'", "stat_date < '{$end_date}'"])
            ->filterWhere(['in','channel_id',$this->channel_id])
            ->orderBy("$field $order")
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();

        $count = (new Query())
            ->from('stat_base_player')
            ->where(['and', "stat_date >= '{$start_date}'", "stat_date < '{$end_date}'"])
            ->filterWhere(['in','channel_id',$this->channel_id])
            ->count();

        $this->writeLayui(Code::OK, '', $count, $data);
    }

    /**
     * 玩家LTV分析
     */
    public function actionCu()
    {
        $start_date = date('Y-m-d', time() - 86400 * 30);
        $end_date = date('Y-m-d', time() - 86400);

        return $this->render('cu', [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

    /**
     * 留存分析接口
     */
    public function actionCuApi()
    {
        $request = Yii::$app->request;
        $start_date = $request->get('start_date') ? $request->get('start_date') . ' 00:00:00' : date('Y-m-d 00:00:00', time() - 86400 * 30);
        $end_date = $request->get('end_date') ? $request->get('end_date') . ' 23:59:59' : date('Y-m-d 23:59:59', time() - 86400);
        $limit = $request->get('limit') ? : 10;
        $page = $request->get('page', 1);
        $field = $request->get('field', 'stat_date');
        $order = $request->get('order', 'desc');

        $data = (new Query())
            ->select(['stat_date', 'regist', 'ltv', 'c_0_avg', 'c_1_avg', 'c_2_avg', 'c_3_avg', 'c_4_avg', 'c_5_avg', 'c_6_avg', 'c_7_avg', 'c_8_avg', 'c_9_avg', 'c_10_avg', 'c_14_avg', 'c_30_avg', 'c_60_avg'])
            ->from('stat_base_ltv')
            ->where(['and', "stat_date >= '{$start_date}'", "stat_date < '{$end_date}'"])
            ->filterWhere(['in','channel_id',$this->channel_id])
            ->orderBy("$field $order")
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();
        $count = (new Query())
            ->from('stat_base_ltv')
            ->where(['and', "stat_date >= '{$start_date}'", "stat_date < '{$end_date}'"])
            ->filterWhere(['in','channel_id',$this->channel_id])
            ->count();

        $this->writeLayui(Code::OK, '', $count, $data);
    }
}
