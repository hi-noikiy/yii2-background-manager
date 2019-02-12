<?php
/**
 * User: jw
 * Date: 2018/8/1 0001
 */
namespace app\controllers;

use yii;
use yii\base\Curl;

class ClosureController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 封停账号页面
     */
    public function actionClosureIndex()
    {
        return $this->render('closure_index');
    }

    public function actionIndex()
    {
        $redisKey = Yii::$app->params['redisKeys']['black_id_list'];
        $redis = Yii::$app->redis;
        $info  = $redis->hgetall($redisKey);
        $data ='<p>';
        foreach($info as $k => $v){
            $data.='ID: '.$k.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        $data .= '</p>';
        $this->writeJson([
            'code'=>200,
            'msg'=>'',
            'data'=>$data
        ]);
    }

    public function actionUpdate()
    {
        //封停账号
        $redisKey = Yii::$app->params['redisKeys']['black_id_list'];
        $redis = Yii::$app->redis;
        $status = Yii::$app->request->post('status');
        $player = Yii::$app->request->post('player');
        $save   = -1;
        //判断用户是否存在
        $user = Yii::$app->db->createCommand('select * from login_db.t_lobby_player where u_id = :u_id')
            ->bindValue(':u_id',$player)
            ->queryOne();
        if(!$user){
            $this->writeResult(self::CODE_PLAYER_NOT_FOUND);
        }
        if($status == 1){ //封停操作
            $data['date'] = date('Y-m-d');
            //$url = 'http://192.168.1.150:9966/player_downline?';
            $url  = Yii::$app->params['kicking_url'];
            $send_data = array(
                "userId" => $player,
            );

            $present_data = 'msg=' . json_encode($send_data, JSON_UNESCAPED_UNICODE);
            $curl = new Curl();
            $resInfo = $curl->get($url,$present_data);
            $save = $redis->hset($redisKey,$player,json_encode($data));
        }else if($status == 2){ //解封操作
            $save = $redis->hdel($redisKey,$player);
        }
        if($save >= 0 ){
            $this->writeResult();
        }else{
            $this->writeResult(self::CODE_ERROR);
        }
    }

}