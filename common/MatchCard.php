<?php
/**
 * Created by PhpStorm.
 * User: moyu
 * Date: 2018/9/3
 * Time: 15:34
 */

namespace app\common;

use Yii;

class MatchCard
{
    /**
     * 获取玩家手牌真实牌值
     *
     * @param $gameId
     * @param $cards
     * @return bool|string
     */
    public function getCarsName($gameId,$cards){
        if(empty($gameId) || empty($cards)){
            return false;
        }
        //获取牌型规则
        $cardRule = Yii::$app->params['checkTableInfo'][$gameId]['cardRule'];

        $cardValue = is_array($cards) ? $cards : explode(',',$cards);
        $resCards=[];
        foreach($cardValue as $key=>$value){
            $value = (int) $value;
            $v = (int) floor($value / $cardRule['num']);//向下取整为花色
            $c = $value % $cardRule['num'];//取余为牌值

            if($v == count($cardRule['color'])-1){
                $resValue = $cardRule['king'][$c] . $cardRule['color'][$v];
            }else{
                $resValue = $cardRule['color'][$v] . $cardRule['value'][$c];
            }

            $resCards[] = $resValue;
        }
        $strCards = implode($resCards,",");//返回字符串

        return $strCards;
    }
}