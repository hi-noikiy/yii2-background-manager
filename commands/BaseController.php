<?php
/**
 * User: SeaReef
 * Date: 2018/12/6 19:34
 */
namespace app\commands;

use app\common\DailiCalc;
use Yii;
use yii\console\Controller;
use app\models\LobbyPlayer;
use app\models\Channel;

class BaseController extends Controller
{
    public function init()
    {
        $this->setMemory();
        $this->setTime();
    }

    /**
     * 设置内存大小
     *
     * @params string $size 内存大小
     */
    protected function setMemory($size = '128M')
    {
        ini_set('memory_limit', $size);
    }

    /**
     * 设置最大执行时间
     * @params int $size 执行时间
     */
    protected function setTime($size = 600)
    {
        set_time_limit($size);
    }

    public function getChannelUnderList($channel_id){
        $under_list = [];
        if($channel_id != 1){
            $channelModel = new Channel();
            $agentId = $channelModel->getDataByCon(['channel_id'=>$channel_id],'agent_id',3);
            $under_player_list = DailiCalc::getAgentList($agentId,'allUnderPlayer');
            $under_agent_list = DailiCalc::getAgentList($agentId,'allUnderDaili');
            $under_list = array_merge($under_player_list,$under_agent_list);
            if(!$under_list){
                $under_list=[1];
            }
        }

        return $under_list;
    }

    public function actionBaseInfo($start_time, $end_time, $channel_id){
        // Subclasses must be overwrite 子类需重写
    }

    public function actionPollChannel(){
        $channelModel = new Channel();
        $channelList = $channelModel->getDataByCon($con=[]);

        foreach ($channelList as $key=>$val){
            $this->actionBaseInfo('','',$val['channel_id']);
        }
    }
}