<?php
/**
 * User: jw
 * Date: 2018/8/7 0007
 */
namespace app\controllers;

use app\common\Code;
use Yii;
use yii\base\Curl;
use app\common\MatchCard;

class DissolveRoomController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 解散房间页面
     */
    public function actionDissolveRoomIndex()
    {
        $games = Yii::$app->params['games'];
        return $this->render('dissolve_room',['games'=>$games]);
    }
    /**
     * 查询房间信息
     *
     */
    public function actionSearchRoom()
    {
        $gameId  = Yii::$app->request->post('gameId');
        $tableId = Yii::$app->request->post('tableId');

        if($gameId && $tableId){
            $data['tableId'] = $tableId;
            $url  = Yii::$app->params['checkTableInfo'][$gameId]['url'];
            $present_data = 'msg=' . json_encode($data, JSON_UNESCAPED_UNICODE);
            $curl = new Curl();
            /** 返回消息字段释义：players:玩家列表（其中：card:玩家手牌值 playerId：玩家id goldBar：玩家金币） tableId：牌桌id state：查询状态（0：成功，无其他）*/
            $info = $curl->CURL_METHOD($url,$present_data);

            $info = json_decode($info,true);

//            添加查询的访问日志
            file_put_contents('/tmp/search_room.log', print_r([
                date('Y-m-d H:i:s'),
                $gameId,
                $tableId,
                $info,
            ], 1), FILE_APPEND);

            if($info['state'] != 0){
                    $res = array('code'=>0);
                    $this->writeResult(self::CODE_OK, 'success',$res);
                }else{
                    $resData['code'] = 1;
                    $resData['info'] = '';
                    $num = 1;
                    $match = new MatchCard();
                    foreach ($info['players'] as $k => $v) {
                        $cards = $match ->getCarsName($gameId,$v['card']) ?: "无";
                        $resData['info'] .= '<p>玩家'.$num.': id:'.$v['playerId'].';元宝:'.$v['goldBar'].'元;牌型:'.$cards.'</p>';
                        $num++;
                    }
                    $this->writeResult(Code::OK, 'success',$resData);
                }

        }else{
            $this->writeResult(4000,'参数错误！');
        }
    }

    /**
     * 强制解散房间
     *
     */
    public function actionDissolveRoom()
    {
        $gameId  = Yii::$app->request->post('gameId');
        $tableId = Yii::$app->request->post('tableId');

        if($gameId && $tableId){
            $data['tableId'] = $tableId;
            $url  = Yii::$app->params['dissolveTable'][$gameId];
            Yii::info('http_get地址：' . $url);
            $present_data = 'msg=' . json_encode($data, JSON_UNESCAPED_UNICODE);

            $curl = new Curl();
            $info = $curl->CURL_METHOD($url,$present_data);
            $info = json_decode($info,true);
            if($gameId == '524803'){
                if($info['result'][0] == 'success'){
                    $this->writeResult();
                }else{
                    $this->writeResult(self::CODE_ERROR,"解散房间出错");
                }
            }else{
                if($info['state'] == 1){
                    $this->writeResult(4002,'解散房间出错！');
                }else{
                    $this->writeResult();
                }
            }
        }else{
            $this->writeResult(4003,'参数错误！');
        }
    }

    /**
     * 强制解散房间
     *
     */
    public function actionForceDissolveRoom()
    {
        $gameId  = Yii::$app->request->post('gameId');
        $tableId = Yii::$app->request->post('tableId');

        if($gameId && $tableId){
            $data['tableId'] = $tableId;
            $url  = Yii::$app->params['dissolveTable'][$gameId];
            Yii::info('http_get地址：' . $url);
            $present_data = 'msg=' . json_encode($data, JSON_UNESCAPED_UNICODE);

            $curl = new Curl();
            $info = $curl->CURL_METHOD($url,$present_data);
            $info = json_decode($info,true);
            if($gameId == '524803'){
                if($info['result'][0] == 'success'){
                    $this->writeResult();
                }else{
                    $this->writeResult(self::CODE_ERROR,"解散房间出错");
                }
            }else{
                if($info['state'] == 1){
                    $this->writeResult(4002,'解散房间出错！');
                }else{
                    $this->writeResult();
                }
            }
        }else{
            $this->writeResult(4003,'参数错误！');
        }
    }
}