<?php
/**
 * User: SeaReef
 * Date: 2018/12/7 0:11
 */
namespace app\commands;

use app\common\DailiCalc;
use Yii;
use yii\console\Controller;
use yii\db\Query;

class TestController extends Controller
{
    public function actionT1()
    {
        $parent_id = '30703385';
        $player_id = '30265776';
        $info = DailiCalc::updateParentId($parent_id, $player_id);
        var_dump($info);

//        INSERT INTO t_player_member VALUES(NULL, '30601818', '30462143', '2018-12-07 00:15:00');
    }

    /**
     * 计算循环绑定
     */
    public function actionT2()
    {
        $parent_id = (new Query())->select('parent_id')->from('t_player_member')->groupBy('parent_id')->column();
        var_dump($parent_id);
        foreach ($parent_id as $v) {
            $search_id = $this->searchParent($v);
        }
    }

    private function searchParent($parent_id)
    {
        $player_id = (new Query())->select('player_id')->from('t_player_member')->where(['parent_id' => $parent_id])->scalar();
        if ($player_id == 999) {
            return ;
            return false;
        } else {
            return $this->searchParent($player_id);
        }
    }

    public function actionT3()
    {
        $data = (new Query())->select(['player_id'])->from('t_player_member')->all();
        foreach ($data as $v) {
            file_put_contents('/tmp/top_parent.log', print_r([$v], 1), FILE_APPEND);
            $top = $this->top($v['player_id']);
            file_put_contents('/tmp/top_parent.log', print_r([$top], 1), FILE_APPEND);
        }
    }

    /**
     * 求玩家顶级上司
     */
    private function top($player_id)
    {
        $data = (new Query())->select(['player_id', 'parent_id'])->from('t_player_member')->where(['player_id' => $player_id])->one();

        if ($data == false) {
            return -1;
        }
        if ($data['parent_id'] == '999') {
            return $data['player_id'];
        } else {
            return $this->top($data['parent_id']);
        }
    }

    /**
     * 更新金币日志
     */
    public function actionT4()
    {
        $table_name = "player_log.t_lobby_player_log__20190116";
        $yuan_player_id = '30686795';
        $tihuan_player_id = '30386294';

        $log = (new Query())
            ->select('*')
            ->from($table_name)
            ->where(['PLAYER_ID' => 30686795])
            ->limit(10)
            ->all();

        $db = Yii::$app->db;
        foreach ($log as $k => $v) {
            $remark = str_replace($yuan_player_id, $tihuan_player_id, $v['REMARK']);
            $sql = "UPDATE {$table_name} SET PLAYER_ID = {$tihuan_player_id}, REMARK = '{$remark}'";
            $db->createCommand($sql)->execute();
        }
    }
}
