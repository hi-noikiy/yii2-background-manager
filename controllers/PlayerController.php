<?php
/**
 * User: jw
 * Date: 2018/8/24 0024
 *
 * 玩家分析
 */
namespace app\controllers;

use app\models\Player;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii;
use yii\db\Query;

class PlayerStatController extends CommonBaseController
{
    /**
     * 玩家实名认页面
     * @return string
     */
    public function actionPlayerAuthIndex()
    {
        return $this->render('player_auth');
    }

    /**
     * 玩家实名认证列表
     */
    public function actionPlayerAuth()
    {
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        $request = Yii::$app->request->get();

        if (isset($request['arrFile'])) {//表单请求
            $request = json_decode($request['arrFile'],true);
            $page = $request['page'];
            $limit = $request['limit'];
        }
        $where[] = ' status = 1 and unix_timestamp(auth_time) != 0';
        if (isset($request['time']) && $request['time']) {
            $where[] = 'unix_timestamp(auth_time) >='.strtotime($request['time']).' and unix_timestamp(auth_time) <'.(strtotime($request['time'])+86400);
        }
        if (isset($request['player_id']) && $request['player_id']) {
            $where[] = ' player_id = '.$request['player_id'];
        }
        $where = implode(' and ',$where);
        $rows = (new Query())
            ->select('*')
            ->from('t_player')
            ->where($where)
            ->orderBy('id asc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->all();
        if (isset($request['type']) && $request['type'] == 1) {//导出数据
            $spreadsheet = (new Player())->exportPlayers($rows);
            $filename = time().'.xls';
            ob_end_clean();
            header("Expires: 0");
            header("Content-Type:application/vnd.ms-execl");
            header('Content-Disposition:attachment;filename=' . $filename . '');

            $write = IOFactory::createWriter($spreadsheet,'Xls');
            $write->save('php://output');
            exit;
        } else {
            $count = (new Query())
                ->select('id')
                ->from('t_player')
                ->where($where)
                //->orderBy()
                ->count();
            $this->writeLayui(0,'',$count,$rows?$rows:[]);
        }
    }

    public function actionExportPlayer()
    {

    }


    /**
     * 新增玩家
     */
    public function actionNewPlayer()
    {
        return $this->render('new_player');
    }

    /**
     * 新增玩家
     */
    public function actionActivePlayer()
    {
        return $this->render('active_player');
    }

    /**
     * 留存
     */
    public function actionRetainPlayer()
    {
        return $this->render('retain_player');
    }

    /**
     * 流失
     */
    public function actionLossPlayer()
    {
        return $this->render('loss_player');
    }

    /**
     * 付费玩家
     */
    public function actionPayPlayer()
    {
        return $this->render('pay_player');
    }

    /**
     * 设备相关（玩家设备分析）
     */
    public function actionDevicePlayer()
    {
        return $this->render('device_player');
    }
}