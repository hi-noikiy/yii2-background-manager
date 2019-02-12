<?php
/**
 * User: SeaReef
 * Date: 2018/7/3 17:31
 *
 * 测试功能
 */
namespace app\controllers;

use app\models\User;
use Yii;
use yii\base\Curl;
use yii\db\Query;
use yii\web\Controller;
use app\common\MatchCard;

class FutureController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionT101()
    {
        if (Yii::$app->request->isPost) {
            $count = (new Query())
                ->from('t_gold_record')
                ->count();
            $data = (new Query())
                ->select('*')
                ->from('t_gold_record')
                ->all();

            return $this->asJson([
                'code' => 0,
                'msg' => '',
                'count' => $count,
                'data' => $data,
            ]);
        } else {
            return $this->render('t101');
        }
    }

    public function actionT102($type = 0)
    {
        if (Yii::$app->request->isPost) {
            if ($type == 1) {
                var_dump($_GET);
                var_dump($_POST);
                die();
            }
            $count = (new Query())
                ->from('t_recharge_conf')
                ->count();
            $data = (new Query())
                ->select('*')
                ->from('t_recharge_conf')
                ->all();

            return $this->asJson([
                'code' => 0,
                'msg' => '',
                'count' => $count,
                'data' => $data,
            ]);
        } else {
            return $this->render('t102');
        }
    }

    public function actionT103()
    {
        return $this->render('t103');
    }

    public function actionT104()
    {
        $db = Yii::$app->db;
        var_dump($db);
        $db->createCommand()->queryAll();
    }

    public function actionT105()
    {
        if (Yii::$app->request->isPost) {
            echo '<pre>';
            var_dump($_FILES);
        }

        return $this->render('t105');
    }

    /**
     * 查询牌型功能
     */
    public function actionQueryCard()
    {
//        echo '<pre>';
        $request = Yii::$app->request;
//        查询的房间、用户名
        $table_id = $request->get('t');
        $name = $request->get('u');
        $room_id = 2010;
        $game_id = 524803;

        if ($table_id && $name) {
            $data['tableId'] = $table_id;
            $data['roomId'] = $room_id;
            $url = "http://10.0.4.131:19938/tableInformation";
//            $present_data = 'msg=' . json_encode($data, JSON_UNESCAPED_UNICODE);
//            $info = $this->http_post($url,$present_data);

            $curl = new Curl();
            $info = $curl->setPostParams([
                'msg' => json_encode($data, JSON_UNESCAPED_UNICODE),
            ])
                ->post($url);
//            var_dump($info);

//            file_put_contents('/tmp/query_card.log', print_r([$data, 1]), FILE_APPEND);
        }
$info = json_decode($info, 1);
        if($info['state'] != 0){
            $res = array('code'=>0);
            var_dump($res);
        }else{
            $resData['code'] = 1;
            $resData['info'] = '';
            $num = 1;
            $match = new MatchCard();
            foreach ($info['players'] as $k => $v) {
                $cards = $match ->getCarsName('524816',$v['card']) ?: "无";
                $resData['info'] .= '<p>玩家'.$num.': id:'.$v['playerId'].';元宝:'.$v['goldBar'].'元;牌型:'.$cards.'</p>';
                $num++;
            }
            var_dump($resData);
        }
    }

//    private $ZJHColorList = [0=>'方片',1=>'梅花',2=>'红桃',3=>'黑桃',4=>'王'];//炸军花花色
//    private $ZJHColorList = [0=>'♢',1=>'♧',2=>'♡',3=>'♤',4=>'♔'];//炸军花花色
//    private $ZJHColorList = [0=>'<span style="color:#ff0000">♢</span>',1=>'<span style="color:black">♧</span>',2=>'<span style="color:#ff0000">♡</span>',3=>'<span style="color:black">♤</span>',4=>'<span style="color:#ff0000">♔</span>'];//炸军花花色
    private $ZJHColorList = [0=>'<span style="color:#ff0000">♦</span>',1=>'<span style="color:black">♣</span>',2=>'<span style="color:#ff0000">♥</span>',3=>'<span style="color:black">♠</span>',4=>'<span style="color:#ff0000">♔</span>'];//炸军花花色

    private function getCard($card)
    {
        if (!strstr($card, '===')) {
            return '旁观';
        } else {
            $list = explode('===', $card);

            $res = '';
            foreach ($list as $key => $value) {
                $res .= $this -> ZJHMatch($value).'_';
            }
            $res = rtrim($res,'_');
            return $res;
        }
    }

    private function ZJHMatch($card){
        $key = floor($card/16);
        $value = ($card % 16) + 1;
        if($key < 0 || $key > 4){
            return false;
        }
        if($value < 2 || $value > 15){
            return false;
        }
        if($value == 14){
            $value = 1;
        }else if($value == 15){
            $value = 2;
        }

        if ($value == 1) {
            $value = 'A';
        }
        if ($value == 11) {
            $value = 'J';
        }
        if ($value == 12) {
            $value = 'Q';
        }
        if ($value == 13) {
            $value = 'K';
        }

        return  $this -> ZJHColorList[$key].$value;
    }

    /**
     * RBAC权限验证功能
     */
    public function actionT200()
    {
//        $identity = Yii::$app->user->identity;
//        $id = Yii::$app->user->id;
//        var_dump($identity, $id);3

        $identity = User::findOne(['username' => 'admin']);
        echo '<pre>';
        var_dump($identity);
        $info = Yii::$app->user->login($identity);
        var_dump($info);

    }

    public function actionT201()
    {
        Yii::$app->user->logout();
    }
}
