<?php
/**
 * User: SeaReef
 * Date: 2018/7/3 17:31
 *
 * 游戏内系统设置
 */
namespace app\controllers;

use app\common\Code;
use app\models\ActivityEdit;
use app\common\helpers\Sms;
use app\models\AgentActivity;
use app\models\Broadcast;
use app\models\Channel;
use app\models\ConfRebateRatio;
use app\models\GeneralRobot;
use app\models\GeneralRobotCharacter;
use app\models\GeneralRobotGoldPool;
use app\models\LobbyPlayer;
use app\models\LogAgentActivity;
use app\models\Operation;
use app\models\RechargeConf;
use app\models\Robot;
use app\models\HundredRobot;
use app\models\UploadFile;
use Yii;
use yii\base\Curl;
use yii\base\ErrorException;
use yii\db\Exception;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use app\models\Activity;
use app\models\LogGeneralRobotGoldPool;

class GameSetController extends CommonController

{
    /** 萌新redis键 */
    const MENGXIN_KEY = 'mengxin';

    const MENGXIN_JUSHU = 'jushu';

    const MENGXIN_STATUS = 'status';

    const MENGXIN_MAX_TIME = 'max_time';

    const MENGXIN_PROBABILITY = 'probability';

    const ROBOT_PROBABILITY = 'robot_common_probability';

    /**
     * 差牌redis相关配置
     */
    const BAD_POCKER_SET = 'table_heimingdan';

    const BAD_POCKER_LIST = 'heimingdan_list';

    const BAD_STATUS = 'table_status';

    const BAD_PROBABILITY = 'table_probability';

    public $enableCsrfValidation = false;

//    初始化操作
    public function init()
    {
        if (empty(Yii::$app->user->id)) {
            $this->redirect('/user/login');
        }
    }

    /**
     * 萌新设置
     */
    public function actionMengxin($type = 0)
    {
        $redis = Yii::$app->redis;

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $status = empty($post['status']) ? '1': '0';

//            添加操作日志
            $op = new Operation();
            $op->log([
                'id' => Yii::$app->user->id,
                'op_type' => 1,
                'op_content' => "更新萌新状态：概率{$post['probability']}、局数{$post['jushu']}、时长{$post['max_time']}、状态{$status}",
            ]);

            $data = $redis->hmset(self::MENGXIN_KEY, self::MENGXIN_JUSHU, $post['jushu'], self::MENGXIN_STATUS, $status, self::MENGXIN_MAX_TIME, $post['max_time'], self::MENGXIN_PROBABILITY, $post['probability']);
        }


        $jushu = $redis->hget(self::MENGXIN_KEY, self::MENGXIN_JUSHU);
        $status = $redis->hget(self::MENGXIN_KEY, self::MENGXIN_STATUS);
        $max_time = $redis->hget(self::MENGXIN_KEY, self::MENGXIN_MAX_TIME);
        $probability = $redis->hget(self::MENGXIN_KEY, self::MENGXIN_PROBABILITY);

        $start_time = date('Y-m-d', time() - 86400 * 30);
        $end_time = date('Y-m-d', time() - 86400);


        return $this->render('mengxin', [
            'redis_data' => [
                'jushu' => $jushu,
                'status' => $status,
                'max_time' => $max_time,
                'probability' => $probability,
            ],
            'date' => [
                'start_time' => $start_time,
                'end_time' => $end_time,
            ]
        ]);
    }

    /**
     * 萌新统计
     */
    public function actionStatMengxin()
    {
        $request = Yii::$app->request;
        $start_time = $request->get('startTime', date('Y-m-d', time() - 86400 * 30));
        $end_time = $request->get('endTime', date('Y-m-d', time()));
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        $field = $request->get('field', 'stat_date');
        $order = $request->get('order', 'desc');

        $count = (new Query())
            ->from('stat_mengxin')
            ->where(['between', 'stat_date', $start_time, $end_time])
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->count();

        $data = (new Query())
            ->from('stat_mengxin')
            ->where(['between', 'stat_date', $start_time, $end_time])
            ->orderBy("$field $order")
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();

        $this->writeJson(1, self::CODE_OK, '', $count, $data);
    }

    /**
     * 萌新异常警告
     */
    public function actionMengxinAlert()
    {
        $request = Yii::$app->request;
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $count = (new Query())
            ->select('*')
            ->from('log_mengxin_alert')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->count();
        $data = (new Query())
            ->select('*')
            ->from('log_mengxin_alert')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();

        $this->writeJson(1, self::CODE_OK, '', $count, $data);
    }

    /**
     * 萌新操作记录
     */
    public function actionOpMengxin()
    {
        $request = Yii::$app->request;
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $count = Operation::find()->where(['op_type' => 1])->limit($limit)->offset(($page-1) * $limit)->count();
        $data = Operation::find()->select(['username', 'op_time', 'op_content'])->where(['op_type' => 1])->limit($limit)->offset(($page-1) * $limit)->orderBy('op_time DESC')->asArray()->all();

        $this->writeJson(1, self::CODE_OK, '', $count, $data);
    }

    /**
     * 清除萌新状态
     */
    public function actionClearMengxin()
    {
        $request = Yii::$app->request;
        $gid = $request->post('gameID');
        $player_id = $request->post('playerID');

        $redis = Yii::$app->redis_1;
        $key = 'mengxin_jushu_uid_' . $gid;
        $data = $redis->hset($key, $player_id, 0);
//        var_dump($data);

        echo json_encode([
            'code' => $data
        ]);
    }

    /**
     * 差牌黑名单
     */
    public function actionBadPockerBlack()
    {
        $redis = Yii::$app->redis;

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $status = empty($post['status']) ? '1': '0';

//            添加操作日志
            $op = new Operation();
            $op->log([
                'id' => Yii::$app->user->id,
                'op_type' => 2,
                'op_content' => "更改黑名单：概率{$post['probability']}、状态{$status}",
            ]);

            $data = $redis->hmset(self::BAD_POCKER_SET, self::BAD_STATUS, $status, self::BAD_PROBABILITY, $post['probability']);
        }

        $status = $redis->hget(self::BAD_POCKER_SET, self::BAD_STATUS);
        $probability = $redis->hget(self::BAD_POCKER_SET, self::BAD_PROBABILITY);
//        $list = $redis->hgetall(self::BAD_POCKER_LIST);

        return $this->render('bad_pocker_black', [
            'redis_data' => [
                'status' => $status,
                'probability' => $probability,
            ],
        ]);
    }

    /**
     * 差牌黑名单操作记录
     */
    public function actionOpBadPocker()
    {
        $request = Yii::$app->request;
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $count = Operation::find()->where(['op_type' => 2])->limit($limit)->offset(($page-1) * $limit)->count();
        $data = Operation::find()->select(['username', 'op_time', 'op_content'])->where(['op_type' => 2])->limit($limit)->offset(($page-1) * $limit)->orderBy('op_time DESC')->asArray()->all();

        $this->writeJson(1, self::CODE_OK, '', $count, $data);
    }

    /**
     * 添加差牌黑名单
     */
    public function actionAddBadPocker()
    {
        if (Yii::$app->request->isPost) {
            $redis = Yii::$app->redis;
            $request = Yii::$app->request;
            $uid = $request->post('uid');
            $level = $request->post('level');
//            file_put_contents('d:/11.log', print_r($uid, 1), FILE_APPEND);

            $data = $redis->hset(self::BAD_POCKER_LIST, $uid, $level);
        } else {
            return $this->render('add_bad_pocker');
        }
    }

    /**
     * 差牌黑名单列表
     */
    public function actionBadPockerList()
    {
        $redis = Yii::$app->redis;
        $keys = $redis->hkeys(self::BAD_POCKER_LIST);
        $values = $redis->hvals(self::BAD_POCKER_LIST);
        $data = [];
        foreach ($keys as $k => $v) {
            $data[] = ['uid' => $v, 'level' =>$values[$k]];
        }
        $count = count($data);

        $this->writeJson(1, self::CODE_OK, '', $count, $data);
    }


    /**
     * 删除差牌黑名单
     */
    public function actionBadPockerDel()
    {
        $uid = Yii::$app->request->post('uid');
        $redis = Yii::$app->redis;

        $info = $redis->hdel(self::BAD_POCKER_LIST, $uid);

        return $this->asJson(['res' => $info]);
    }

    /**
     * 普通场机器人
     * 需要设置的redis键值有：
     * 下注、跟注、操作间隔、抢庄（4/3/2/1）、叫分（4/3/2/1）、等待、观战离桌概率、看牌率、开牌率、弃牌率、跟注、加注、加牌
     */
    public function actionRobotCommon()
    {
//        初始化的开关状态
        $redis = Yii::$app->redis_2;
//        开关状态
        $switch = $redis->hget(self::ROBOT_PROBABILITY, 'switch');
        $zjh_switch = $redis->hget(self::ROBOT_PROBABILITY, 'zjh_switch');
        $max_jushu = $redis->hget(self::ROBOT_PROBABILITY, 'max_jushu');
//        获取下一个id、生成随机player_id
        $id = (new Query())->select('id')->from('common_robot')->orderBy('id DESC')->scalar();
        $player_id = 9;
        for ($i = 1; $i <= 8; $i++) {
            $player_id .= rand(0, 9);
        }
        return $this->render('robot_common', [
            'switch' => $switch,
            'id' => $id + 1,
            'player_id' => $player_id,
            'zjh_switch' => $zjh_switch,
            'max_jushu' => $max_jushu,
        ]);
    }

    /**
     * 普通场机器人配置设置
     * type 1/机器人开关
     */
    public function actionRobotSet($type)
    {
        $redis = Yii::$app->redis_2;
        $request = Yii::$app->request;

//        机器人开关
        if ($type == 1) {
            $switch = $request->get('value');
            if ($switch == 'true') {
                $redis->hset(self::ROBOT_PROBABILITY, 'switch', 1);
            }
            if ($switch == 'false') {
                $redis->hset(self::ROBOT_PROBABILITY, 'switch', 0);
            }

            $this->writeJson(2, self::CODE_OK, '');
        }

//        机器人概率控制
        if ($type == 2) {
            $count = $redis->hlen(self::ROBOT_PROBABILITY);
            $data = $redis->hgetall(self::ROBOT_PROBABILITY);

//        整理reids格式
            foreach ($data as $k => $v) {
                if ($k % 2 == 0) {
                    $key[] = $v;
                } else {
                    $value[] = $v;
                }
            }
            $d = array_combine($key, $value);

            $this->writeJson(1, self::CODE_OK, '', $count / 2, [$d]);
        }

//        修改机器人配置的某一个个键值
        if ($type == 3) {
            $field = $request->get('field');
            $value = $request->get('value');
            $redis->hset(self::ROBOT_PROBABILITY, $field, $value);

            $this->writeJson(2, self::CODE_OK);
        }
    }

    /**
     * 普通场机器人信息
     */
    public function actionRobotInfo()
    {
        $request = Yii::$app->request->get();
        if (!isset($request['limit']) || !isset($request['page'])) {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
        $offset = ($request['page']-1)*$request['limit'];
        $count = (new Query())
            ->select('id')
            ->from(Robot::tableName())
            ->limit($request['limit'])
            ->offset($offset)
            ->count();
        $rows = (new Query())
            ->select('*')
            ->from(Robot::tableName())
            ->orderBy('id')
            ->limit($request['limit'])
            ->offset($offset)
            ->all();
        //TODO::携带与当前元宝是否修改，充值统计是否添加条件
        if ($rows) {
            //统计信息
            $d = date('Ymd', time());
            $record_person_table = "mdwl_activity.t_game_record_person_log" . $d;
            $record_table = 'mdwl_activity.t_game_record_log' . $d;
            $recharge_table = 'player_log.t_lobby_player_log__' . $d;
            $player_log = Yii::$app->player_log;
            $db = Yii::$app->db;
            foreach ($rows as $key => $val) {
                //当前元宝数
                $current = $db->createCommand("select player_gold_new from {$record_person_table} where player_id like '%{$val['player_id']}' order by id desc")->queryScalar();
                $val['dangqian'] = $current?$current:$val['dangqian'];
                if ($player_log->createCommand('show tables like'."'t_lobby_player_log__".$d."'")->execute()) {
                    //充值次数
                    $val['recharge'] += $db->createCommand("select count(id) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id like '%{$val['player_id']}'")->queryScalar();
                    //补充总额
                    $val['all_recharge'] += $db->createCommand("select sum(count) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id like '%{$val['player_id']}'")->queryScalar();
                    //赢元宝数
                    $val['win_yuanbao'] += $db->createCommand("select sum(`count`) as num from {$recharge_table} where player_id like '%{$val['player_id']}' and operation_type = 1 and source_type = 0")->queryScalar();
                    //输元宝数
                    $val['lose_yuanbao'] += $db->createCommand("select sum(`count`) as num from {$recharge_table} where player_id like '%{$val['player_id']}' and operation_type = 2 and source_type = 0")->queryScalar();
                }

                //游戏场次
                $val['game_count'] += $db->createCommand("select count(id) as num from {$record_person_table} where player_id like '%{$val['player_id']}'")->queryScalar();
                //赢场次
                $val['win_count'] += $db->createCommand("select count(id) as num from {$record_person_table} where player_id like '%{$val['player_id']}' and win_gold>0")->queryScalar();
                //输场次
                $val['lose_count'] += $db->createCommand("select count(id) as num from {$record_person_table} where player_id like '%{$val['player_id']}' and win_gold<0")->queryScalar();

                if ($val['lose_count'] == 0) {
                    $val['win_lose'] = 1*100;
                } else {
                    $val['win_lose'] = round($val['win_count']/($val['lose_count'] + $val['win_count']),5)*100;
                }
                $val['win_lose'] = $val['win_lose'].'%';
                $rows[$key] = $val;
            }
        }
        $this->writeJson(1,Code::OK,'success',$count,$rows?$rows:[]);
    }

    /**
     * 添加机器人
     * 1、上传头像
     * 2、新建
     */
    public function actionRobotAdd()
    {

        /*if ($type == 1) {

        }

        if ($type = 2) {*/
        /*file_put_contents('e:/1.log', print_r([$_REQUEST, $_POST, $_GET], 1), FILE_APPEND);
        $this->writeJson(1, self::CODE_OK, '');*/
        $request = Yii::$app->request->post();

        if ($request) {
            $is_create = 1;//是否新建
            if (isset($request['id']) && $request['id']) {
                $is_create = 0;
                $model = Robot::findOne($request['id']);
                $model->updated_time = date('Y-m-d H:i:s',time());
            } else {
                $model = new Robot();
            }
            if ($model->load($request,'')) {
                if ($model->save()) {
                    if ($model->player_id) {//更新机器人信息表和redis
                        (new LobbyPlayer())->updateRobotInfo(['weixin_nickname'=>$model->nickname,'head_img'=>$model->img_url,'ip'=>$model->ip],$model->player_id);
                        $model->updateUserRedis($model->attributes);
                    }
                    if (!$is_create) {//更新机器人时，更新机器人redis
                        $model->updateRedis();
                    }
                    $this->writeJson(2);
                } else {
                    //var_dump($model->getErrors());exit;
                    $this->writeJson(2,self::CODE_ERROR);
                }
            } else {
                //var_dump($model->getErrors());exit;
                $this->writeJson(2,self::CODE_ERROR);
            }
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 机器人详情
     */
    public function actionRobotDetail()
    {
        $request = Yii::$app->request->get();
        if (isset($request['id']) && $request['id']) {
            $row = Robot::findOne($request['id']);
            $this->writeJson(1,self::CODE_OK,'',1,$row?$row->attributes:[]);
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 删除机器人
     */
    public function actionRobotDel()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $result = Yii::$app->db->createCommand()->delete(Robot::tableName(),'id = '.$request['id'])->execute();
            if ($result) {
                (new Robot())->updateRedis();
                $this->writeJson(2);
            } else {
                $this->writeJson(2,self::CODE_ERROR);
            }
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 机器人启用和停用
     */
    public function actionRobotStatus()
    {
        $request = Yii::$app->request->get();
        if (isset($request['id']) && $request['id']) {
            $result = Yii::$app->db->createCommand()->update(Robot::tableName(),['status'=>intval($request['status'])],'id = '.$request['id'])->execute();
            if ($result) {
                (new Robot())->updateRedis();
                $this->writeJson(2);
            } else {
                $this->writeJson(2,self::CODE_ERROR);
            }
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 上传图片
     */
    public function actionRobotImgUpload()
    {
        $result = UploadFile::UploadToWeb('robot');
        if ($result['code'] == 0) {
            $this->writeJson(1,self::CODE_OK,'',0,$result['url']);
        } else {
            $this->writeJson(2,self::CODE_ERROR);
        }
    }

    /**
     *轮播图页面
     */
    public function actionBroadcast()
    {
        return $this->render('broadcast');
    }

    /*
     * 轮播图列表
     */
    public function actionBroadcastIndex()
    {
        $request = Yii::$app->request->get();
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        $offset = ($page-1)*$limit;
        $query = new Query();
        $count = $query
            ->select('*')
            ->from('t_lunbo')
            ->count();
        $rows = $query
            ->orderBy('id')
            ->offset($offset)
            ->limit($limit)
            ->all();
        $this->writeJson(1,0,"",$count,$rows);
    }

    /**
     * 轮播图的设置
     */
    public function actionBroadcastCreate()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $model = Broadcast::findOne($request['id']);
        } else {
            $model = new Broadcast();
        }
        if ($model->load($request,'') && $model->save()) {
            $this->writeJson(2);
        } else {
            $this->writeJson(2,self::CODE_ERROR);
        }
    }

    /**
     * 删除轮播图
     */
    public function actionBroadcastDel()
    {
        $request = Yii::$app->request->post();
        if ($request['id']) {
            if (Yii::$app->db->createCommand()->delete(Broadcast::tableName(), 'id = '.$request['id'])->execute()) {
                $this->writeJson(2);
            } else {
                $this->writeJson(2,self::CODE_ERROR);
            }
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }

    }

    /**
     * 设置轮播图播放的速度和停机信息
     */
    public function actionBroadcastRedis()
    {
        $request = Yii::$app->request->post();
        $redis = Yii::$app->redis_3;
        if ($request) {
            if (isset($request['interval'])) {
                $redis->set(Yii::$app->params['redisKeys']['lunbo_interval'],$request['interval']);
            }
            if (isset($request['downtime'])) {
                $redis->hset(Yii::$app->params['redisKeys']['downtime'],'time',$request['downtime']['time']);
                $redis->hset(Yii::$app->params['redisKeys']['downtime'],'info',$request['downtime']['info']);
            }
            $this->writeJson(2);
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 获取轮播图播放时间间隔和停机维护信息
     */
    public function actionIntervalDowntime()
    {
        $redis = Yii::$app->redis_3;
        $row['interval'] = $redis->get(Yii::$app->params['redisKeys']['lunbo_interval']);
        $row['downtime']['time'] = $redis->hget(Yii::$app->params['redisKeys']['downtime'],'time');
        $row['downtime']['info'] = $redis->hget(Yii::$app->params['redisKeys']['downtime'],'info');
        $this->writeJson(1,self::CODE_OK,'',1,$row);
    }

    /**
     * 上传图片
     * @return \yii\web\Response
     */
    public function actionUploadImg()
    {
        $file = UploadedFile::getInstanceByName('file');
        if ($file->error != 0) {
            return $this->writeJson(2,self::CODE_ERROR);
        }
        if (!in_array($file->getExtension(),['jpg','png','jpeg','gif'])) {
            return $this->writeJson(2,self::CODE_IMG_EXTENSION_ERROR);
        }
        if ($file->size > 10*1024*1000) {
            return $this->writeJson(2,self::CODE_IMG_SIZE_ERROR);
        }
        if (!is_dir(Yii::$app->basePath.'/web/uploads/lunbo/')) {
            mkdir(Yii::$app->basePath.'/web/uploads/lunbo/');
        }
        $time = time();
        $dst = '/web/uploads/lunbo/'.$time.'_'.Yii::$app->user->getId().'.'.$file->getExtension();
        $dst_ = '/uploads/lunbo/'.$time.'_'.Yii::$app->user->getId().'.'.$file->getExtension();
        $result =  move_uploaded_file($file->tempName,Yii::$app->basePath.$dst);
        if ($result) {
            $protocol = 'https';
            return $this->writeJson(1,self::CODE_OK,'','',$protocol.'://'.$_SERVER['HTTP_HOST'].$dst_);
        } else {
            return $this->writeJson(2,self::CODE_ERROR);
        }
    }

    /**
     * 台费设置
     */
    public function actionTableFee()
    {
        $gameId = Yii::$app->params['default_table_fee_game_id'];//默认显示扎金花的房费
        $games = Yii::$app->params['games'];

        if(!Yii::$app->request->post()){
            $data = $this->getTableFeeData($gameId);
            return $this->render('table_fee',['data'=>$data,'gameId'=>$gameId,'games'=>$games]);
        }else{
            $request = Yii::$app->request->post();

            if(!isset($request['tableFee']) || !isset($request['levelId']) || !isset($request['gameId'])){
                $data = $this->getTableFeeData($gameId);
                return $this->render('table_fee',['data'=>$data,'gameId'=>$gameId,'games'=>$games]);
            }else{
                $tableFee = $request['tableFee'];
                $levelId = $request['levelId'];
                $gameId = $request['gameId'];
                try{
                    $key = $this->getRedisKey($gameId);
                    $redis = Yii::$app->redis;
                    $redis->hset($key,$levelId,$tableFee);
                }catch (Exception $e){
                    $this->writeResult(self::CODE_ERROR,"更改台费失败!");
                }

                $this->writeResult();
            }

        }

        return $this->render('table_fee',['games'=>$games,'gameId'=>$gameId,'data'=>[]]);
    }

    /**
     * 返回底注类别和台费
     *
     * @param $gameId
     * @return bool
     */
    private function getTableFeeData($gameId){
        if(empty($gameId)) return false;

        $ruleLevel = Yii::$app->params['stake_'.$gameId];
        $data['ruleLevel'] = $ruleLevel;
        $data['gameId'] = $gameId;

        return $data;
    }

    /**
     * 获取台费
     *
     */
    public function actionGetTableFeeByLevelId(){
        if(!empty($_REQUEST)){
            $levelId = $_REQUEST['levelId'];
            $gameId = $_REQUEST['gameId'];
            $tableFee = $this->getTableFeeFromRedis($gameId);
            $this->writeJsonOk($tableFee[$levelId]);
        }else{
            $this->writeJsonFalse("数据错误！");
        }

    }


    /**
     * Ajax返回底注类别
     *
     */
    public function actionGetRuleLevel(){
        if(isset(Yii::$app->request->post()['gameId'])){
            $gameId = Yii::$app->request->post()['gameId'];
            $ruleLevel = Yii::$app->params['stake_'.$gameId];
            $this->writeJsonOk($ruleLevel);
        }else{
            $this->writeJsonFalse('该子游戏不存在');
        }

    }

    /**
     * 获取台费比例
     *
     * @param $gameId
     * @return array|string
     */
    private function getTableFeeFromRedis($gameId){
        if(empty($gameId)) return "";

        $key = $this->getRedisKey($gameId);
        $redis = Yii::$app->redis;
        $source = $redis->hgetall($key);

        $data=[];
        for ($i=0;$i<count($source)-1;$i++){
            if($i%2 == 0){
                $data[$source[$i]] = $source[$i+1];
            }
        }

        return $data;
    }

    /**
     * 获取子游戏对应台费key(redis)
     *
     * @param $gameId
     * @return string
     */
    public function getRedisKey($gameId){
        if(empty($gameId)) return "";

        $redisKeys = Yii::$app->params['redisKeys'];
        $gameForShort = Yii::$app->params['gameForShort'];
        $key = "table_fee_rate_".$gameForShort[$gameId];

        return $redisKeys[$key];
    }

    /**
     * 充值相关配置
     * 充值的黑白名单、
     */
    public function actionRechargeConf()
    {
        return $this->render('recharge_conf');
    }

    /**
     * 游戏内功能配置项
     * 充值限额、排行榜可见度、金额限制等
     */
    public function actionInGame()
    {
        return $this->render('in_game');
    }

    /*****************************百人场机器人*************************************/
    /**
     * 模拟机器人
     */
    public function actionT1()
    {
        $arr = [
            'oid' => 1,
            'id' => 900000002,
            'name' => '我是机器人2',
            'photoUrl' => 'https://oss.dropgame.cn/upload/lunbo/robot/000002.jpg',
            'ip' => '127.0.0.1',
            'character' => 1,
            'gold' => 501,
        ];

        echo json_encode($arr);
    }

    public function actionT2()
    {
        $redis = Yii::$app->redis_2;

        for ($i = 10; $i <= 80; $i ++) {
            $arr = [
                'oid' => $i,
                'id' => 9000000 . $i,
                'name' => '机器人' . $i,
                'photoUrl' => 'https://oss.dropgame.cn/upload/lunbo/robot/0000' . $i . '.jpg',
                'ip' => '127.0.0.1',
                'character' => 1,
                'gold' => 501,
            ];

            $info = json_encode($arr);

            $redis->lpush('robot_common_info', $info);
        }
    }

    public function actionT3()
    {
        $redis = Yii::$app->redis_2;

//        $redis->hset('robot_common_probability', 'op_interval', '5000,10000');
        $info = $redis->hmset('robot_common_probability', 'op_interval', '5000,10000', 'nn_rob1', 20, 'nn_rob2', 30, 'nn_rob3', 30, 'nn_rob4', 20, 'nn_call1', 30, 'nn_call2', 20, 'nn_call3', 30, 'nn_call4', 30, 'ready', '1000,5000', 'leave', 50, 'zjh_see', '15', 'nn_open', '50', 'zjh_giveup', 10, 'zjh_heel', 100, 'zjh_fill', 10, 'zjh_compar', 10, 'character', '普通性格', 'switch', 1);
        var_dump($info);
    }


    //---------------------------------------------------------------------------------------------------------------
    //百人场机器人

    /**
     * 百人场机器人设置页面
     */
    public function actionRobotHundreds()
    {
        return $this->render('robot_hundreds');
    }


    /**
     * 百人场机器人创建
     */
    public function actionHundredsRobotCreate()
    {
	ini_set('memory_limit', '256M');
        //创建庄机器人还是普通机器人，庄机器人为1，普通机器人为2
	    $type = 2;
        //之后扩展会传入变量
        $gid = 524821;//推筒子
        $gid = Yii::$app->request->post('gid',524821);//推筒子
        $request = Yii::$app->request->post();
        $request['gid'] = $gid;
        //$request['gid'] = $gid;
        //生成player_id
        //$redis = Yii::$app->redis;
        $game_dev_redis = Yii::$app->game_dev_redis_1;
        $PLAYER_ID  = $game_dev_redis->incr('user_id_index');
        $file_path  = "userId.conf";
        $userid_str = file_get_contents($file_path);//将整个文件内容读入到一个字符串中
        $userid_arr = explode(':',$userid_str);
        $PLAYER_INDEX = $userid_arr[$PLAYER_ID];
        $request['player_id'] = $PLAYER_INDEX;
        $login_db = Yii::$app->login_db;
        $model = new HundredRobot();
        if (isset($request['is_system']) && $request['is_system'] == 1) {//创建庄机器人
            $type = 1;
            $sys_exists = (new Query())
                ->select('*')
                ->from(HundredRobot::tableName())
                ->where('is_system = 1 and gid = '.$request['gid'])
                ->where('is_system = 1 and gid = '.$gid)
                ->one();
            if ($sys_exists) {//庄机器人已存在，则修改庄机器人
                unset($request['player_id']);
                $model = HundredRobot::findOne($sys_exists['id']);
            }
        }
        if ($model->load($request,'') && $model->save()) {
            $data = [
                'weixin_nickname' => $model->nickname,
                'u_id' => $model->player_id,
                'head_img' => $model->img_url,
                'ip' => $model->ip,
                'reg_time' => date('Y-m-d H:i:s',time())
            ];
            if ($type == 1) {//庄机器人
                if ($sys_exists) {//庄机器人已存在
                    if ($model->player_id) {
                        $result = $login_db->createCommand()->update('t_lobby_player',$data,'u_id = '.$model->player_id)->execute();
                    }
                } else {
                    $result = $login_db->createCommand()->insert('t_lobby_player',$data)->execute();
                }
            } else if ($type == 2) {
                $result = $login_db->createCommand()->insert('t_lobby_player',$data)->execute();
            }
            //庄机器人是否已存在
            /*$sys_exists = (new Query())
                ->select('*')
                ->from(HundredRobot::tableName())
                ->where('is_system = 1 and gid = '.$request['gid'])
                ->one();*/
            if ($result) {
                $robot_info = [
                    'playerIndex'=> $model->player_id,
                    'playerName'=> $model->nickname,
                    'headImageUrl'=> $model->img_url,
                    'sex'=> 111,
                    'ip'=> $model->ip,
                    'gameTimes'=> 0,
                    'winTimes'=> 0,
                    'loseTimes'=> 0,
                    'winProbability'=> 0,
                    'state'=> 1,//状态0：暂停     1：待上场   2：在场中     -1删除
                    'instruc'=> 0,//指令 0无     1使之退出   2使之可用   3使其删除
                ];
                if ($type == 1) {
                    $robot_info['system'] = true;
                } else {
                    $robot_info['system'] = false;
                }
                Yii::$app->game_dev_redis->hset(Yii::$app->params['redisKeys']['robot_player'].$request['gid'],$model->player_id,json_encode($robot_info,JSON_UNESCAPED_UNICODE));
                //var_dump($robot_info);exit;

                $this->writeJson(2,self::CODE_OK);
            } else {
                $model->delete();
                $this->writeJson(2,self::CODE_ERROR);
            }
        } else {
            var_dump($model->getErrors());exit;
            $this->writeJson(2,self::CODE_ERROR);
        }



    }

    /**
     * 更新机器人
     */
    public function actionHundredsRobotUpdate()
    {
        //$gid = 524821;//推筒子
        $request = Yii::$app->request->post();
        $login_db = Yii::$app->login_db;
        $game_dev_redis = Yii::$app->game_dev_redis;
        if (isset($request['id']) && $request['id']) {
            $model = HundredRobot::findOne($request['id']);
            $model->updated_time = date('Y-m-d H:i:s',time());
            if ($model->load($request,'') && $model->save()) {
                //TODO::redis数据更改和验证修改
                $data = [
                    "weixin_nickname" => $model->nickname,
                    "head_img" => $model->img_url,
                    "ip" => $model->ip,
                ];
                if ($model->player_id) {
                    $login_db->createCommand()->update('t_lobby_player',$data,'u_id = '.$model->player_id)->execute();
                }
                $robot_info = $game_dev_redis->hget(Yii::$app->params['redisKeys']['robot_player'].$request['gid'],$model->player_id);
                $robot_info = json_decode($robot_info,true);
                $robot_info['playerName'] = $model->nickname;
                $robot_info['headImageUrl'] = $model->img_url;
                $robot_info['ip'] = $model->ip;

                $game_dev_redis->hset(Yii::$app->params['redisKeys']['robot_player'].$request['gid'],$model->player_id,json_encode($robot_info,JSON_UNESCAPED_UNICODE));
                $this->writeJson(2,self::CODE_OK);
            } else {
                $this->writeJson(2,self::CODE_ERROR);
            }
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 百人场机器人指令操作
     */
    public function actionHundredsRobotState()
    {
        $request = Yii::$app->request->post();
        if (isset($request['player_id']) && $request['player_id']) {
            file_put_contents('/tmp/hundreds_robot.log', print_r([$request, $d], 1), FILE_APPEND);
            return false;

            $d = date('Y-m-d H:i:s', time());
            $game_dev_redis = Yii::$app->game_dev_redis;
            $robot_info = $game_dev_redis->hget(Yii::$app->params['redisKeys']['robot_player'].$request['gid'],$request['player_id']);
            $robot_info = json_decode($robot_info,true);
            $robot_info['instruc'] = $request['instruc'];
            $game_dev_redis->hset(Yii::$app->params['redisKeys']['robot_player'].$request['gid'],$request['player_id'],json_encode($robot_info,JSON_UNESCAPED_UNICODE));
            $this->writeJson(2,self::CODE_OK);
        }
        $this->writeJson(2,self::CODE_PARAM_ERROR);
    }

    /**
     * 机器人详情
     */
    public function actionHundredsRobotDetail()
    {
        $request = Yii::$app->request->get();
        if (isset($request['id']) && $request['id']) {
            $model = HundredRobot::findOne($request['id']);
            $this->writeJson(1,self::CODE_OK,'',0,$model->attributes?$model->attributes:[]);
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }


    /**
     * 删除机器人
     */
    public function actionHundredsRobotDel()
    {
        //$gid = 524821;//推筒子
        $request = Yii::$app->request->post();
        if (!isset($request['gid']) || !$request['gid']) {
            return $this->writeResult(self::CODE_PARAM_ERROR);
        }
        if (isset($request['id']) && $request['id']) {
            $model = HundredRobot::findOne($request['id']);
            $player_id = $model->player_id;
            $result = $model->delete();
            if ($result) {
                Yii::$app->game_dev_redis->hdel(Yii::$app->params['redisKeys']['robot_player'].$request['gid'],$player_id);
                $this->writeJson(2,self::CODE_OK);
            } else {
                $this->writeJson(2,self::CODE_ERROR);
            }
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 机器人列表
     */
    public function actionHundredsRobotIndex()
    {
        $gidAttr = Yii::$app->params['hundreds_games'];
        $db = Yii::$app->db;
        //刷新机器人状态值
        $game_dev_redis = Yii::$app->game_dev_redis;
        foreach($gidAttr as $val) {
            $result = $game_dev_redis->hgetall(Yii::$app->params['redisKeys']['robot_player'].$val);
            $data = [];
            if ($result) {
                foreach ($result as $k=>$v) {
                    if ($k%2 == 0) {
                        $data[$v] = $result[$k+1];
                        $state = json_decode($result[$k+1],true);
                        if (isset($state['state'])) {
                            $db->createCommand()->update(HundredRobot::tableName(),['state'=>$state['state']],'player_id = '.$state['playerIndex'])->execute();
                        }
                    }
                }
            }
        }

        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        $request = Yii::$app->request->get();
        //$where[] = 'gid = '.$gid;
        $where[] = 'is_system = 0';
        if (isset($request['start_time']) && $request['start_time']) {
            $where[] = 'date >= '.$request['start_time'];
        }
        if (isset($request['end']) && $request['end']) {
            $where[] = 'date <= '.$request['end'];
        }
        $where = implode(' and ',$where);
        $rows = (new Query())
            ->select('*')
            ->from(HundredRobot::tableName())
            ->where($where)
            ->orderBy('id asc')
            ->limit($limit)
            ->offset(($page-1)*$limit)
            ->all();
        $count = (new Query())
            ->select('*')
            ->from(HundredRobot::tableName())
            ->where($where)
            ->count();
        $this->writeJson(1,self::CODE_OK,'',$count,$rows?$rows:[]);
    }

    /**
     * 庄家机器人
     */
    public function actionHundredsRobotSys()
    {
        //$gid = 524821;//推筒子
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        $gid = Yii::$app->request->get('gid');
        if (!$gid) {
            return $this->writeResult(self::CODE_PARAM_ERROR);
        }
        $rows = (new Query())
            ->select('*')
            ->from(HundredRobot::tableName())
            ->where('is_system = 1 and gid = '.$gid)
            ->orderBy('id asc')
            //->limit($limit)
            //->offset(($page-1)*$limit)
            ->all();
        $count = (new Query())
            ->select('*')
            ->from(HundredRobot::tableName())
            ->where('is_system = 1 and gid = '.$gid)
            ->count();
        $this->writeJson(1,self::CODE_OK,'',$count,$rows?$rows:[]);
    }

    /**
     * 百人场游戏配置页
     */
    public function actionHundredsRobotSet()
    {
        return $this->render('robot_hundreds_set');
    }

    /**
     * 百人场游戏设置
     */
    public function actionHundredsSet()
    {
        $redis = Yii::$app->game_dev_redis;
        $request = Yii::$app->request->post();
        if (isset($request['gid']) && $request['gid']) {
            $gid = $request['gid'];
            unset($request['gid']);
            foreach ($request as $key=>$val) {
                $redis->hset(Yii::$app->params['redisKeys']['br_table_config'].$gid,$key,$val);
            }
            $this->writeJson(2,self::CODE_OK);
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }

    }

    /**
     * 百人场配置查看
     */
    public function actionHundredsIndex()
    {
        $request = Yii::$app->request->get();
        if (isset($request['gid']) && $request['gid']) {
            $redis = Yii::$app->game_dev_redis;
            $result = $redis->hgetall(Yii::$app->params['redisKeys']['br_table_config'].$request['gid']);
            $data = [];
            if ($result) {
                foreach ($result as $k=>$v) {
                    if ($k%2 == 0) {
                        $data[$v] = $result[$k+1];
                    }
                }
            }
            $this->writeJson(1,self::CODE_OK,'',0,$data);
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 更改百人场奖池
     */
    public function actionHundredsChangeGoldPool()
    {
        $request = Yii::$app->request->post();
        if (isset($request['gid']) && $request['gid']) {
            $d = date('Y-m-d H:i:s', time());
            Yii::info(print_r([$request, $d], 1), 'pay');
            Yii::$app->redis->hset(Yii::$app->params['redisKeys']['br_table_config'].$request['gid'],'changeGoldPool',$request['change']);
            $this->writeJson(2,self::CODE_OK);
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }


    }


    /**
     * 百人场设置行为触发比例
     */
    public function actionBehaviorPercent()
    {
        $request = Yii::$app->request->get();
        //$request['gid'] = 524821;
        if (isset($request['gid']) && $request['gid']) {
            $redis = Yii::$app->game_dev_redis;
            $result = $redis->hgetall(Yii::$app->params['redisKeys']['br_table_config'].$request['gid']);
            $data = [];
            if ($result) {
                foreach ($result as $k=>$v) {
                    if ($k%2 == 0) {
                        $data[$v] = $result[$k+1];
                    }
                }
                if ($request['gid'] == 524821) {//炸金花
                    $new_data = [
                        ['block' => $data['block1']],
                        ['block' => $data['block2']],
                        ['block' => $data['block3']],
                        ['block' => $data['block4']],
                        ['block' => $data['block5']],
                    ];
                    foreach($new_data as $key=>$val) {
                        $data_ = explode(',',$data['killType'.($key+1)]);
                        $new_data[$key]['killType1'] = $data_[0];
                        $new_data[$key]['killType2'] = $data_[1];
                        $new_data[$key]['killType3'] = $data_[2];
                        $new_data[$key]['killType4'] = $data_[3];
                        $new_data[$key]['killType5'] = $data_[4];
                    }
                } else {//牛牛
                    $new_data = [
                        ['block' => $data['block1']],
                        ['block' => $data['block2']],
                        ['block' => $data['block3']],
                        ['block' => $data['block4']],
                        ['block' => $data['block5']],
                        ['block' => $data['block6']],
                    ];
                    foreach($new_data as $key=>$val) {
                        $data_ = explode(',',$data['killType'.($key+1)]);
                        $new_data[$key]['killType1'] = $data_[0];
                        $new_data[$key]['killType2'] = $data_[1];
                        $new_data[$key]['killType3'] = $data_[2];
                        $new_data[$key]['killType4'] = $data_[3];
                        $new_data[$key]['killType5'] = $data_[4];
                        $new_data[$key]['killType6'] = $data_[5];
                    }
                }

            }
            $this->writeJson(1,self::CODE_OK,'',0,isset($new_data)?$new_data:[]);
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }


    /**
     * 百人场触发比例修改
     */
    public function actionBehaviorPercentUpdate()
    {
        $request = Yii::$app->request->post();
        if (isset($request['gid']) && $request['gid']) {
            $d = date('Y-m-d H:i:s', time());
            file_put_contents('/tmp/hundreds_robot.log', print_r([$request, $d], 1), FILE_APPEND);
            return false;

            $redis = Yii::$app->game_dev_redis;
            if ($request['gid'] == 524821) {
                $killType = $request['killType1'].','.$request['killType2'].','.$request['killType3'].','.$request['killType4'].','.$request['killType5'];
            } else if ($request['gid'] == 524823) {
                $killType = $request['killType1'].','.$request['killType2'].','.$request['killType3'].','.$request['killType4'].','.$request['killType5'].','.$request['killType6'];
            }
            $block = 'block'.($request['LAY_TABLE_INDEX']+1);
            $killType_index = 'killType'.($request['LAY_TABLE_INDEX']+1);
            $redis->hset(Yii::$app->params['redisKeys']['br_table_config'].$request['gid'],$block,$request['block']);
            $redis->hset(Yii::$app->params['redisKeys']['br_table_config'].$request['gid'],$killType_index,$killType);
            $this->writeJson(2,self::CODE_OK);
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 百人场换牌概率
     */
    public function actionChangePercent()
    {
        $request = Yii::$app->request->get();
        //$request['gid'] = 524821;
        if (isset($request['gid']) && $request['gid']) {
            $redis = Yii::$app->game_dev_redis;
            $result = $redis->hgetall(Yii::$app->params['redisKeys']['br_table_config'].$request['gid']);
            $data = [];
            if ($result) {
                foreach ($result as $k=>$v) {
                    if ($k%2 == 0) {
                        $data[$v] = $result[$k+1];
                    }
                }
                $new_data = [
                    ['changeBlock' => $data['changeBlock1']],
                    ['changeBlock' => $data['changeBlock2']],
                    ['changeBlock' => $data['changeBlock3']],
                    ['changeBlock' => $data['changeBlock4']],
                    ['changeBlock' => $data['changeBlock5']],
                    ['changeBlock' => $data['changeBlock6']],
                ];
                foreach($new_data as $key=>$val) {
                    $new_data[$key]['change'] = $data['change'.($key+1)];

                }
            }
            $this->writeJson(1,self::CODE_OK,'',0,isset($new_data)?$new_data:[]);
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 百人场换牌概率修改
     */
    public function actionChangePercentUpdate()
    {
        $request = Yii::$app->request->post();
        //$request['gid'] = 524821;
        if (isset($request['gid']) && $request['gid']) {
            $redis = Yii::$app->game_dev_redis;
            $block = 'changeBlock'.($request['LAY_TABLE_INDEX']+1);
            $change_index = 'change'.($request['LAY_TABLE_INDEX']+1);
            $redis->hset(Yii::$app->params['redisKeys']['br_table_config'].$request['gid'],$block,$request['changeBlock']);
            $redis->hset(Yii::$app->params['redisKeys']['br_table_config'].$request['gid'],$change_index,$request['change']);
            $this->writeJson(2,self::CODE_OK);
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }



    /**
     * 百人场数据统计
     *
     */
    public function actionHundredsStat()
    {
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        $request = Yii::$app->request->get();
        if (!isset($request['gid']) || !$request['gid']) {
            return $this->writeResult(self::CODE_PARAM_ERROR);
        }
        $where = ' and gid = '.$request['gid'];
        $rows = (new Query())
            ->select('*')
            ->from('log_hundred_game_record')
            ->where('unix_timestamp(`date`) <'.(strtotime($request['date'])+86400).' and unix_timestamp(`date`) >='.strtotime($request['date']).$where)
            ->orderBy('date desc')
            ->limit($limit)
            ->offset(($page-1)*$limit)
            ->all();
        $count = (new Query())
            ->select('*')
            ->from('log_hundred_game_record')
            ->where('unix_timestamp(`date`) <'.(strtotime($request['date'])+86400).' and unix_timestamp(`date`) >='.strtotime($request['date']).$where)
            ->count();
        $this->writeJson(1,self::CODE_OK,'',$count,$rows?$rows:[]);
    }

    /**
     * 百人场数据统计
     * 以天为单位
     */
    public function actionHundredsDayStat()
    {
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        $request = Yii::$app->request->get();
        if (!isset($request['gid']) || !$request['gid']) {
            return $this->writeResult(self::CODE_PARAM_ERROR);
        }
        $where[] = 'gid ='.$request['gid'];
        if (isset($request['start_time']) && $request['start_time']) {
            $where[] = 'unix_timestamp(`date`) >='.strtotime($request['start_time']);
        }
        if (isset($request['end_time']) && $request['end_time']) {
            $where[] = 'unix_timestamp(`date`) <='.strtotime($request['end_time']);
        }
        $where = implode(' and ',$where);
        //var_dump($where);exit;
        $rows = (new Query())
            ->select('*')
            ->from('log_hundred_game_day_record')
            ->where($where)
            ->orderBy('date desc')
            ->limit($limit)
            ->offset(($page-1)*$limit)
            ->all();
        if ($rows) {
            foreach ($rows as $key => $val) {
                if ($val['shun_men']) {
                    $rows[$key]['shun_men'] = ($val['shun_men']*100).'%';
                }
                if ($val['tian_men']) {
                    $rows[$key]['tian_men'] = ($val['tian_men']*100).'%';
                }
                if ($val['di_men']) {
                    $rows[$key]['di_men'] = ($val['di_men']*100).'%';
                }
            }
        }
        $count = (new Query())
            ->select('*')
            ->from('log_hundred_game_day_record')
            ->where($where)
            ->count();
        $this->writeJson(1,self::CODE_OK,'',$count,$rows?$rows:[]);
    }

    /**
     * 当天的百人场
     */
    public function actionHundredsToday()
    {
        $request = Yii::$app->request->get();
        if (!isset($request['gid']) || !$request['gid']){
            return $this->writeResult(self::CODE_PARAM_ERROR);
        }
        $gid = $request['gid'];
        $result = Yii::$app->game_dev_redis->hgetall(Yii::$app->params['redisKeys']['br_table_config'].$gid);
        $data = [];
        if ($result) {
            foreach ($result as $k => $v) {
                if ($k % 2 == 0) {
                    $data[$v] = $result[$k + 1];
                }
            }
        }
        //机器人设置中的数据
        $arr[1] = $data;
        //今日输赢
        $today = Yii::$app->db->createCommand('select * from log_hundred_game_record where gid='.$gid.' and unix_timestamp(`date`) >= '.strtotime('today').' order by `date` asc LIMIT 1')->queryOne();
        if (isset($arr[1]['totalGoldPool'])) {
            $arr[1]['today_win'] = $arr[1]['totalGoldPool']-(isset($today['gold_pool'])?$today['gold_pool']:0);
        } else {
            $arr[1]['today_win'] = 0;
        }
        $this->writeJson(1,self::CODE_OK,'',1,$arr?$arr:[]);
    }

    /**
     * 百人场首页动态图
     */
    public function actionHundredsCostEcharts()
    {
        $request = Yii::$app->request->get();
        if (!isset($request['gid']) || !$request['gid']){
            return $this->writeResult(self::CODE_PARAM_ERROR);
        }
        $gid = $request['gid'];
        $game_dev_redis = Yii::$app->game_dev_redis;
        $len = $game_dev_redis->llen(Yii::$app->params['redisKeys']['br_robot_info'].$gid.'_'.date('Ymd',time()));
        if ($len > 0) {
            for ($i = $len-1;$i>=0;$i--) {
                $arr[0][] = json_decode($game_dev_redis->lindex(Yii::$app->params['redisKeys']['br_robot_info'].$gid.'_'.date('Ymd',time()),$i),true);
            }
        } else {
            $arr[0] = [];
        }

        $this->writeJson(1,self::CODE_OK,'',count($arr[0]),$arr?$arr:[]);
    }

    /**
     * 百人场机器人头像上传
     */
    public function actionHundredsImgUpload()
    {
        $result = UploadFile::UploadToWeb('hundreds_robot');
        if ($result['code'] == 0) {
            $this->writeJson(1,self::CODE_OK,'',0,$result['url']);
        } else {
            $this->writeJson(2,self::CODE_ERROR);
        }
    }

    /**
     *踢出玩家
     */
    public function actionHundredsKickPlayer()
    {
        $request = Yii::$app->request->post();
        $tableId = $request['gid']."1";//百人场tableid为游戏id+1，如推筒子5248211
        Yii::info($tableId, 'pay');
        $player_id = $request['$player_id'];
        if (!$player_id) {
            return $this->writeResult(self::CODE_PARAM_ERROR);
        }
        $result = (new Curl())->get(Yii::$app->params['hundreds_kick'].'/kickPlayer?msg={tableId:'.$tableId.',playerId:'.$player_id.'}');
        $result = json_decode($result,1);
        if (isset($result['state']) && $result['state'] == 0) {
            return $this->writeResult(self::CODE_OK);
        } else {
            return $this->writeResult(self::CODE_ERROR);
        }

    }

    /**
     * 活动页面
     */
    public function actionActivityIndex()
    {
        return $this->render('activity_info');
    }

    /**
     * 活动列表
     */
    public function actionActivityList()
    {
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        $rows = (new Query())
            ->select('*')
            ->from('conf_activity')
            ->where('status = 1 and unix_timestamp(end_time)>='.time())
            ->orderBy('sort')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->all();
        $count = (new Query())
            ->select('*')
            ->from('conf_activity')
            ->where('status = 1 and unix_timestamp(end_time)>='.time())
            ->count();
        $this->writeJson(1,self::CODE_OK,'',$count,$rows?$rows:[]);
    }

    /**
     * 活动设置
     */
    public function actionActivitySet()
    {
        $request = Yii::$app->request->post();
        //yii::error($request);
        //exit;
        if (isset($request['id']) && $request['id']) {
            $model = Activity::findOne($request['id']);
            yii::error($model->attributes);
            yii::error('----');
        } else {
            $model = new Activity();
        }
        $old = '';
        $update = 0;
        if (isset($request['id']) && $request['id']) {
            $model = Activity::findOne($request['id']);
            $old = $model->attributes;
            $update = 1;
        } else {
            $model = new Activity();
            $model->status = 1;
            $update = 0;
        }
        if ($model->load($request,'') && $model->save()) {
            if (!isset($request['id']) && isset($model->id) && $model->id) {//创建时更新sort字段
                Yii::$app->db->createCommand()->update(Activity::tableName(),['sort'=>$model->id],'id='.$model->id)->execute();
            }
            if ($update == 0) {//创建
                (new ActivityEdit())->saveActivityEditLog($model->id,$old,$model->attributes,1);
            } else {//更新
                (new ActivityEdit())->saveActivityEditLog($model->id,$old,$model->attributes,2);
            }
            $this->writeJson(2,self::CODE_OK);
        } else {
            var_dump($model->getErrors());
            $this->writeJson(2,self::CODE_ERROR);
        }
    }

    /**
     * 活动删除
     */
    public function actionActivityDel()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            //$model = Activity::findOne($request['id']);
            //$result = $model->delete();
            $result = Yii::$app->db->createCommand()->update('conf_activity',['status'=>0],'id = '.$request['id'])->execute();
            if ($result) {
                $this->writeJson(2,self::CODE_OK);
            } else {
                $this->writeJson(2,self::CODE_ERROR);
            }

        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 排序
     */
    public function actionActivitySort()
    {
        $request = Yii::$app->request->post();
        if (!isset($request['ids']) || !isset($request['sorts']) || !$request['ids'] || !$request['sorts']) {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        } else {
            foreach ($request['ids'] as $k=>$v) {
                Yii::$app->db->createCommand()->update(Activity::tableName(),['sort'=>$request['sorts'][$k]],'id='.$v)->execute();
            }
            $this->writeJson(2,self::CODE_OK);
        }
    }

    /**
     * 活动内跳转列表
     */
    public function actionActivityJump()
    {
        $rows = (new Query())
            ->select('a.*,b.remark as father_name')
            ->from('conf_gamejump as a')
            ->join('left join','conf_gamejump as b','a.father_id = b.id')
            ->orderBy('a.father_id')
            ->all();
        $new_data = (new Activity())->getJump($rows,0);
        //yii::error($new_data);
        $this->writeJson(1,self::CODE_OK,'',count($rows),$new_data?$new_data:[]);

    }

    /**
     * 活动图片上传
     */
    public function actionActivityImgUpload()
    {
        $path = 'activity';
        if($_REQUEST && isset($_REQUEST['path'])){
            $path =  $_REQUEST['path'];
        }
        $result = UploadFile::UploadToWeb($path);

        if ($result['code'] == 0) {
            $this->writeJson(1,self::CODE_OK,'',0,$result['url']);
        } else {
            $this->writeJson(2,self::CODE_ERROR);
        }
    }

    /**
     * 历史活动库页面
     */
    public function actionActivityHistoryIndex()
    {
        return $this->render('activity_history');
    }


    /********************需要更新的页面**************************/



    /**
     * 登录黑名单
     */
    public function actionLoginBlack()
    {
        return $this->render('login_black');
    }


    /**
     * 企业签设置
     */
    public function actionEnterpriseSign()
    {
        return $this->render('enterprise_sign');
    }
    /**
     * 历史活动库
     */
    public function actionActivityHistory()
    {
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        $rows = (new Query())
            ->select('*')
            ->from('conf_activity')
            ->where('status = 0 or unix_timestamp(end_time)<'.time())
            ->orderBy('sort')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->all();
        foreach ($rows as  $k => $v) {
            if ($v['jump_type'] == 2) {
                if ($v['jump_url']) {
                    $jump = explode('_',$v['jump_url']);
                    foreach ($jump as $k_ => $v_) {
                        $jump_content = Yii::$app->db->createCommand('select * from conf_gamejump where id = '.$v_)->queryOne();
                        $rows[$k]['jump_content'][] = $jump_content['remark'];
                    }
                }
            }
        }
        $count = (new Query())
            ->select('*')
            ->from('conf_activity')
            ->where('status = 0 or unix_timestamp(end_time)<'.time())
            ->count();
        $this->writeJson(1,self::CODE_OK,'',$count,$rows?$rows:[]);

    }

    /*********************************通用机器人********************************/
    /**
     * 通用机器人页面
     */
    public function actionGeneralRobotIndex()
    {
        return $this->render('robot_general_set');
    }

    /**
     * 通用机器人属性页面
     */
    public function actionGeneralRobotCharacterIndex()
    {
        //总开关
        $status['switch'] = Yii::$app->game_dev_redis->hget(Yii::$app->params['redisKeys']['robot_switch'],'sum_switch');
        //炸金花
        $status['switch_1'] = Yii::$app->game_dev_redis->hget(Yii::$app->params['redisKeys']['robot_switch'],'524816');
        //牛牛
        $status['switch_2'] = Yii::$app->game_dev_redis->hget(Yii::$app->params['redisKeys']['robot_switch'],'524818');
        //斗地主
        $status['switch_3'] = Yii::$app->game_dev_redis->hget(Yii::$app->params['redisKeys']['robot_switch'],'524822');
        return $this->render('robot_general_character',$status);
    }

    /**
     * 通用机器人的创建与修改
     */
    public function actionGeneralRobotCreate()
    {
        //数据库保存机器人数据
        /*$request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $model = GeneralRobot::findOne($request['id']);
        } else {
            //生成唯一player_id
            $redis = Yii::$app->redis_1;
            $PLAYER_ID  = $redis->incr('user_id_index');
            $file_path  = "userId.conf";
            $userid_str = file_get_contents($file_path);//将整个文件内容读入到一个字符串中
            $userid_arr = explode(':',$userid_str);
            $PLAYER_INDEX = $userid_arr[$PLAYER_ID];
            $request['player_id'] = $PLAYER_INDEX;

            $model = new GeneralRobot();
        }
        if ($model->load($request,'') && $model->save()) {
            $this->writeJson(2,self::CODE_OK);
        } else {
            var_dump($model->getErrors());exit;
            $this->writeJson(2,self::CODE_ERROR);
        }*/
        //数据库不保存机器人，
        $request = Yii::$app->request->post();
        $game_dev_redis = Yii::$app->game_dev_redis;
        $db = Yii::$app->db;

        if (isset($request['machine_code']) && $request['machine_code']) {
            $key = $request['machine_code'];
            $result = $game_dev_redis->hget(Yii::$app->params['redisKeys']['general_robot_config'],$key);
            $data = json_decode($result,true);
            $data['name'] = $request['nickname'];
            $data['headImg'] = $request['img_url'];
            $data['ip'] = $request['ip'];
            $data['characterId'] = (int)$request['character_id'];
            $data['latitude'] = $request['latitude'];
            $data['longitude'] = $request['longitude'];
            $data['borrowGold'] = $request['take_coin'];
            //登录用数据
            $new_data['weixin_nickname'] = $request['nickname'];
            $new_data['head_img'] = $request['img_url'];
            $new_data['ip'] = $request['ip'];
            if ($new_data) {
                $db->createCommand()->update('login_db.t_lobby_player',$new_data,'machine_code = '.$request['machine_code'])->execute();
            }

        } else {
            $key = time().Sms::randNumber(4);
            $data = [
                'uid' => 0,
                'name' => $request['nickname'],
                'headImg' => $request['img_url'],
                'ip' => $request['ip'],
                'latitude' => $request['latitude'],
                'longitude' => $request['longitude'],
                'machineCode' => $key,
                'characterId' => (int)$request['character_id'],
                'borrowGold' => $request['take_coin'],
                'open' => true,
                'gameId' => $request['gid']
            ];
        }

        $result = $game_dev_redis->hset(Yii::$app->params['redisKeys']['general_robot_config'],$key,json_encode($data,JSON_UNESCAPED_UNICODE));
        $this->writeJson(2,self::CODE_OK);

    }

    /**
     * 通用机器人的删除
     */
    public function actionGeneralRobotDel()
    {
        $request = Yii::$app->request->post();
        if (isset($request['machine_code']) && $request['machine_code']) {
            $game_dev_redis = Yii::$app->game_dev_redis;
            $data = $game_dev_redis->hget(Yii::$app->params['redisKeys']['general_robot_config'],$request['machine_code']);
            $data = json_decode($data,true);
            $data['open'] = false;
            $result = $game_dev_redis->hset(Yii::$app->params['redisKeys']['general_robot_config'],$request['machine_code'],json_encode($data,JSON_UNESCAPED_UNICODE));
            $this->writeJson(2,self::CODE_OK);
        } else {
            return $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
        /*if (isset($request['id']) && $request['id']) {
            $model = GeneralRobot::findOne($request['id']);
            $result = $model->delete();
            if ($result) {
                $this->writeJson(2,self::CODE_OK);
            } else {
                $this->writeJson(2,self::CODE_ERROR);
            }

        } else {
            return $this->writeJson(2,self::CODE_PARAM_ERROR);
        }*/
    }

    /**
     * 通用机器人列表
     */
    public function actionGeneralRobotList()
    {
        $db = Yii::$app->db;
        $start_time = strtotime('today');
        $d = date('Ymd', $start_time);
        $recharge_table = 'player_log.t_lobby_player_log__' . $d;
        $lobby_player = 'login_db.t_lobby_player';
        $log_game = 'log_game_record';
        $log_player_game = 'log_game_player_record';
        $player_log = Yii::$app->player_log;
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        /*$rows = (new Query())
            ->select('*')
            ->from('t_general_robot')
            ->where('')
            ->orderBy('id')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->all();
        $count = (new Query())
            ->select('*')
            ->from('t_general_robot')
            ->where('')
            ->count();*/
        $game_dev_redis = Yii::$app->game_dev_redis;
        $rows = $game_dev_redis->hgetall(Yii::$app->params['redisKeys']['general_robot_config']);
        foreach ($rows as $k => $v) {
            if ($k % 2 == 0) {
                $key[] = $v;
            } else {
                $value[] = json_decode($v,true);
            }

        }
        if (isset($key) && isset($value)) {
            $data = array_combine($key,$value);
        } else {//没有机器人
            return $this->writeJson(1,self::CODE_OK,'',0,[]);
        }

        $data_ = [];
        //获取性格列表
        $character = (new Query())
            ->select('*')
            ->from('t_general_robot_character')
            ->where('')
            ->all();
        $character_id = array_column($character,'id');
        $character_name = array_column($character,'commont');
        $character_data = array_combine($character_id,$character_name);
        if (!$data) {
            return $this->writeJson(1,self::CODE_OK,'',0,[]);
        }
        foreach ($data as $k => $v) {
            if ($v['open']) {
                $data_[$k] = $v;
                $data_[$k]['character_name'] = isset($character_data[$v['characterId']])?$character_data[$v['characterId']]:'';
                $result = Yii::$app->db->createCommand('select * from t_general_robot where player_id = '.$v['uid'])->queryOne();

                //yii::error($result);
                //if ($result) {
                    //$data_[$k] = array_merge($result,$data_[$k]);

                    //当前元宝数(直接查询)
                    $now_coin = $db->createCommand('select gold_bar from '.$lobby_player.' where u_id = '.$v['uid'])->queryScalar();
                    //携带元宝数
                    $take_coin = $db->createCommand('select extend_1 from '.$lobby_player.' where u_id = '.$v['uid'])->queryScalar();
                    //判断当前表是否存在
                    if (!$player_log->createCommand('show tables like'."'t_lobby_player_log__".$d."'")->execute()) {
                        $borrow_num = 0;
                        $borrow_limit = 0;
                    } else {
                        //信贷次数
                        $borrow_num = $db->createCommand("select count(id) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id = {$v['uid']}")->queryScalar();

                        //信贷额度
                        $borrow_limit = $db->createCommand("select sum(`count`) as num from {$recharge_table} where source_type = 6 and operation_type = 1 and player_id = {$v['uid']}")->queryScalar();
                    }

                    //赢场次
                    $win_num = $db->createCommand("select count(a.id) as num from {$log_player_game} as a left join $log_game as b on a.record_id = b.id where player_id = {$v['uid']} and win_gold > 0 and unix_timestamp(end_time) >=".strtotime("today"))->queryScalar();
                    //输场次
                    $lose_num = $db->createCommand("select count(a.id) as num from {$log_player_game} as a left join $log_game as b on a.record_id = b.id where player_id = {$v['uid']} and win_gold < 0 and unix_timestamp(end_time) >=".strtotime("today"))->queryScalar();
                    $borrow_num +=isset($result['borrow_num'])?$result['borrow_num']:0;
                    $borrow_limit +=isset($result['borrow_limit'])?$result['borrow_limit']:0;
                    $win_num +=isset($result['win_num'])?$result['win_num']:0;
                    $lose_num +=isset($result['lose_num'])?$result['lose_num']:0;

                    //游戏场次
                    $game_num = $win_num + $lose_num;
                    if ($game_num != 0) {
                        $win_percent = round(($win_num/$game_num),5);
                    } else {
                        $win_percent = 0;
                    }

                    $new_data = [
                        'now_coin' => $now_coin?$now_coin:0,
                        'take_coin' => $take_coin?$take_coin:0,
                        'borrow_num' => $borrow_num?$borrow_num:0,
                        'borrow_limit' => $borrow_limit?$borrow_limit:0,
                        'game_num' => $game_num?$game_num:0,
                        'win_num' => $win_num?$win_num:0,
                        'lose_num' => $lose_num?$lose_num:0,
                        'win_percent' => ($win_percent*100).'%',
                    ];
                    //var_dump($result);exit;
                    $data_[$k] = array_merge($data_[$k],$new_data);
                    $data_[$k]['id'] = $result['id'];
                    $data_[$k]['gid'] = $result['gid'];

                //}
            }
        }
        ksort($data_);
        /*foreach ($data_ as $key => $val) {
            $data_[$key]['init_gold'] = $db->createCommand('select extend_1 from '.$lobby_player.' where u_id = '.$val['uid'])->queryScalar();
        }*/
        $this->writeJson(1,self::CODE_OK,'',count($data_),$data_);
    }

    /**
     * 通用机器人开关
     */
    public function actionGeneralRobotSwitch()
    {
        $request = Yii::$app->request->post();
        if (isset($request['switch'])) {
            if ($request['key'] == 'switch1') {//总开关
                $key = 'sum_switch';
            } else if ($request['key'] == 'switch2') {//炸金花
                $key = 524816;
            } else if ($request['key'] == 'switch3') {//牛牛
                $key = 524818;
            } else if ($request['key'] == 'switch4') {//斗地主
                $key = 524822;
            }
            $result = Yii::$app->game_dev_redis->hset(Yii::$app->params['redisKeys']['robot_switch'],$key,$request['switch']=='true'?'true':'false');
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 通用机器人开关状态
     */
    /*public function actionGeneralRobotSwitchStatus()
    {
        $status = Yii::$app->game_dev_redis->get(Yii::$app->params['redisKeys']['robot_switch']);
        $this->writeJson(1,self::CODE_OK,'',0,$status);
    }*/

    /**
     * 通用机器人性格的创建与修改
     */
    public function actionGeneralRobotCharacterCreate()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $model = GeneralRobotCharacter::findOne($request['id']);
        } else {
            $model = new GeneralRobotCharacter();
        }
        if ($model->load($request,'') && $model->save()) {
            (new GeneralRobotCharacter())->saveCharacterToRedis($model->attributes);
            $this->writeJson(2,self::CODE_OK);
        } else {
            $this->writeJson(2,self::CODE_ERROR);
        }
    }

    /**
     * 通用机器人性格删除
     */
    public function actionGeneralRobotCharacterDel()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $model = GeneralRobotCharacter::findOne($request['id']);
            $result = $model->delete();
            if ($result) {
                Yii::$app->redis->hdel(Yii::$app->params['redisKeys']['general_robot_property'],$request['id']);
                $characters = (new Query())
                    ->select('*')
                    ->from('t_general_robot_character')
                    ->all();
                $characters_id = array_column($characters,'id');
                //获取所有机器人
                $robots = (new GeneralRobot())->robotInfo();
                //yii::error($characters);
                //yii::error($characters_id);
                //yii::error($robots);
                foreach ($robots as $key => $val) {
                    if ($val['characterId'] == $request['id']) {
                        $robots[$key]['characterId'] = $characters_id[0];
                    }
                    Yii::$app->redis->hset(Yii::$app->params['redisKeys']['general_robot_config'],$val['machineCode'],json_encode($robots[$key],JSON_UNESCAPED_UNICODE));
                }
                $this->writeJson(2,self::CODE_OK);
            } else {
                return $this->writeJson(2,self::CODE_ERROR);
            }

        } else {
            return $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 通用机器人性格列表
     */
    public function actionGeneralRobotCharacterList()
    {
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',10);
        $rows = (new Query())
            ->select('*')
            ->from('t_general_robot_character')
            ->where('')
            ->orderBy('id')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->all();
        $count = (new Query())
            ->select('*')
            ->from('t_general_robot_character')
            ->where('')
            ->count();
        $this->writeJson(1,self::CODE_OK,'',$count,$rows?$rows:[]);
    }

    public function actionGeneralRobotStatIndex()
    {
        return $this->render('general_robot_stat');
    }


    /**
     * 通用机器人奖池设置
     */
    public function actionGeneralRobotGoldPoolSet()
    {
        $request = Yii::$app->request->post();
        $uid = Yii::$app->user->getId();
        $result = Yii::$app->db->createCommand('select * from t_general_robot_gold_pool')->queryOne();
        if ($result) {
            $model = GeneralRobotGoldPool::findOne($result['id']);
            $model -> total_gold_pool += $request['add_gold_pool'];
            $model -> now_gold_pool += $request['add_gold_pool'];
        } else {
            $model = new GeneralRobotGoldPool();
            $model -> total_gold_pool = $request['add_gold_pool'];
            $model -> now_gold_pool = $request['add_gold_pool'];
        }
        $model->create_time = date('Y-m-d H:i:s',time());
        if ($model->load($request,'') && $model->save()) {
            (new LogGeneralRobotGoldPool())->saveLogGeneralRobotGoldPool([
                'gold_pool' => $request['add_gold_pool'],
                'recovery_pool' => 0
            ],$uid?$uid:0);
            $this->writeJson(2,self::CODE_OK);
        } else {
            var_dump($model->getErrors());
            $this->writeJson(2,self::CODE_ERROR);
        }

    }

    /**
     * 查看通用机器人奖池设置
     */
    public function actionGeneralRobotGoldPool()
    {
        //获取当前奖池额度
        $data = (new Query())
            ->select('*')
            ->from('t_general_robot_gold_pool')
            ->where('id > 0')
            ->one();
        $this->writeJson(1,self::CODE_OK,'',1,$data?$data:[]);
    }

    /**
     * 奖池操作记录
     */
    public function actionLogGeneralRobotGoldPool()
    {
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',1);
        $data = (new Query())
            ->select('*')
            ->from('log_general_robot_gold_pool')
            ->where('id > 0')
            ->orderBy('create_time desc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->all();
        $count = (new Query())
            ->select('*')
            ->from('log_general_robot_gold_pool')
            ->where('id > 0')
            ->count();
        $this->writeJson(1,self::CODE_OK,'',$count,$data?$data:[]);
    }

    /**
     * 机器人每日统计
     */
    public function actionGeneralRobotDayStat()
    {
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',1);
        $rows = (new Query())
            ->select('*')
            ->from('stat_general_robot_day')
            ->orderBy('date desc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->all();
        if ($rows) {
            foreach ($rows as $key => $val) {
                $new_data = [];
                $character = json_decode($val['character'],true);
                foreach ($character as $k =>$v) {
                    $new_data[] = $v['commont'].':'.$v['count'];
                }
                $rows[$key]['character'] = implode(' | ',$new_data);
            }
        }
        $count = (new Query())
            ->select('*')
            ->from('stat_general_robot_day')
            ->count();
        $this->writeJson(1,self::CODE_OK,'',$count,$rows?$rows:[]);
    }

    /**
     * 单个机器人每日统计
     */
    public function actionSignalGeneralRobotDayStat()
    {
        $page = Yii::$app->request->get('page',1);
        $limit = Yii::$app->request->get('limit',1);
        $request = Yii::$app->request->get();
        if (isset($request['id']) && $request['id']) {
            $rows = (new Query())
                ->select('*')
                ->from('stat_signal_general_robot_day')
                ->where('player_id = '.$request['id'])
                ->orderBy('date desc')
                ->offset(($page-1)*$limit)
                ->limit($limit)
                ->all();
            $count = (new Query())
                ->select('*')
                ->from('stat_signal_general_robot_day')
                ->where('player_id = '.$request['id'])
                ->count();
            $this->writeJson(1,self::CODE_OK,'',$count,$rows?$rows:[]);
        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 当天统计页面汇总
     */
    public function actionGeneralRobotStatInfo()
    {
        $table = 'login_db.t_lobby_player';
        $pool_table = 't_general_robot_gold_pool';
        $log_pool_table = 'log_general_robot_gold_pool';
        $player_log = 'log_game_player_record';
        $db = Yii::$app->db;
        $robots = (new GeneralRobot())->robotInfo();
        $uids = array_column($robots,'uid');
        foreach ($uids as $key=>$val) {
            if (!$val) {//删除无效机器人
                unset($uids[$key]);
            }
        }
        $uids = implode(',',$uids);
        //初始元宝，携带元宝
        $golds = $db->createCommand('select sum(`extend_1`) as init_gold,sum(gold_bar) as take_gold from '.$table.' where u_id in ('.$uids.')')->queryOne();

        //奖池额度
        $pool = $db->createCommand('select * from '.$pool_table)->queryOne();

        //今日回收
        $today_recovery = $db->createCommand('select sum(recovery_pool) as recovery_pool from '.$log_pool_table.' where unix_timestamp(create_time) >='.strtotime('today').' and unix_timestamp(create_time) <'.strtotime('tomorrow').' and gold_pool = 0 and recovery_pool != 0')->queryScalar();
        //今日后台奖池增加额度
        $today_add_pool = $db->createCommand('select sum(gold_pool) from '.$log_pool_table.' where unix_timestamp(create_time) >='.strtotime('today').' and unix_timestamp(create_time) <'.strtotime('tomorrow').' and gold_pool != 0 and recovery_pool = 0')->queryScalar();
        //今日借贷额度
        $today_pool = Yii::$app->redis->get(Yii::$app->params['redisKeys']['general_robot_now_gold_pool']);
        $recovery_pool = $db->createCommand('select sum(recovery_pool) from log_general_robot_gold_pool')->queryScalar();
        $data = [
            'init_gold' => isset($golds['init_gold'])?$golds['init_gold']:0,
            'take_gold' => isset($golds['take_gold'])?$golds['take_gold']:0,
            'gold_pool' => isset($pool['now_gold_pool'])?$pool['now_gold_pool']-$today_pool:0,
            'today_gold_pool' => (-$today_pool)+$today_add_pool,//今日奖池增减(今日借贷加上后台奖池增加额度)
            'recovery_pool' => $recovery_pool?$recovery_pool:0,
            'today_recovery_pool' => $today_recovery?$today_recovery:0
        ];

        $this->writeJson(1,self::CODE_OK,'',1,$data?$data:[]);
    }

    /**
     * 机器人进场底注限制
     */
    public function actionGeneralRobotMatchBase()
    {
        $request = Yii::$app->request->post();
        if (isset($request['gid']) && $request['gid']) {
            if ($request['match_base']) {
                $data = explode('/',$request['match_base']);
                $request['match_base'] = implode(',',$data);
            }
            Yii::$app->redis->hset(Yii::$app->params['redisKeys']['robot_add_match_base'],$request['gid'],$request['match_base']);
            return $this->writeResult(self::CODE_OK);
        } else {
            return $this->writeResult(self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 获取游戏对于机器人的底注设置
     */
    public function actionGameMatchBase()
    {
        $request = Yii::$app->request->get();
        if (isset($request['gid']) && $request['gid']) {
            $data = Yii::$app->redis->hget(Yii::$app->params['redisKeys']['robot_add_match_base'],$request['gid']);
            if ($data) {
                $data = explode(',',$data);
                $data = implode('/',$data);
            }
            return $this->writeResult(self::CODE_OK,'',$data);
        } else {
            return $this->writeResult(self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 斗地主机器人进场时间设置
     */
    public function actionDdzEnter()
    {
        $value = Yii::$app->request->post('enter_time');
        if ($value) {
            Yii::$app->redis->set(Yii::$app->params['redisKeys']['robot_match_waittime'],$value);
            return $this->writeResult(self::CODE_OK);
        }
        return $this->writeResult(self::CODE_PARAM_ERROR);
    }

    /**
     * 代理登记消耗配置
     */
    public function actionRebateRatio()
    {
        if (Yii::$app->request->isPost) {
            $count = (new Query())
                ->select('conf_rebate_ratio')
                ->count();
            $data = (new Query())
                ->select('*')
                ->from('conf_rebate_ratio')
                ->all();

            $this->writeLayui(Code::OK, '', $count, $data);
        } else {
            return $this->render('rebate_ratio');
        }
    }

    /**
     * 返利等级列表
     *
     */
    public function actionRebateSet(){
        if(Yii::$app->request->isPost){
            if(Yii::$app->request->post()){
                $request = Yii::$app->request->post();
                $confRebateModel = new ConfRebateRatio();
                $data = $confRebateModel->getData(array(),"*",1);
                foreach ($data as $key=>$val){
                    $data[$key]['desLevel'] = 'V'.$val['level'];
                }
                return $this->writeLayui(Code::OK,'success',count($data),$data);
            }
        }

        return $this->render('rebate-set');
    }

    /**
     * 返利设置
     */
    public function actionRebateAdd(){
        if(Yii::$app->request->isPost){
            if(Yii::$app->request->post()){
                $request = Yii::$app->request->post();
                $confRebateModel = new ConfRebateRatio();
                if($confRebateModel->setLevel($request['level'],$request)){
                    $this->writeResult(Code::OK);
                }else{
                    $this->writeResult(Code::ERROR,'添加失败');
                }
            }
        }
    }

    /**
     * 代理后台活动配置
     *
     */
    public function actionAgentActivity(){
        if(Yii::$app->request->isPost){
            $page = Yii::$app->request->get('page',1);
            $limit = Yii::$app->request->get('limit',10);
            $data = date('Y-m-d H:i:s');
            $rows = (new Query())
                ->select('*')
                ->from('conf_agent_activity')
                ->where("status = 1 and end_time >= '". $data . "'")
                ->orderBy('sort')
                ->offset(($page-1)*$limit)
                ->limit($limit)
                ->all();

            foreach ($rows as $key=>$val){
                if($val['show_type'] == 1){
                    $showTypeName = '一次';
                }else if($val['show_type'] == 2){
                    $showTypeName = '每次';
                }else if($val['show_type'] == 3){
                    $showTypeName = '时间段';
                }else{
                    $showTypeName = '';
                }

                $rows[$key]['showTypeName'] = $showTypeName;

            }

            $count = (new Query())
                ->select('*')
                ->from('conf_agent_activity')
                ->where('status = 1 and unix_timestamp(end_time)>='.time())
                ->count();

            $this->writeJson(1,self::CODE_OK,'',$count,$rows?$rows:[]);
        }else{
            return $this->render('agent_activity');
        }
    }

    /**
     * 活动设置
     */
    public function actionAgentActivitySet()
    {
        $request = Yii::$app->request->post();

        $request['type'] = 1;
        if(isset($request['img_url']) && $request['img_url']){
            $request['type'] = 2;
        }

        $agentActivityModel = new AgentActivity();
        if($agentActivityModel->setAgentActivity($request,$request['id'])){
            $this->writeResult(Code::OK,'success');
        }else{
            $this->writeResult(Code::ERROR,'success');
        }
    }

    /**
     * 代理活动排序
     *
     */
    public function actionAgentActivitySort()
    {
        $request = Yii::$app->request->post();
        if (!isset($request['ids']) || !isset($request['sorts']) || !$request['ids'] || !$request['sorts']) {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        } else {
            foreach ($request['ids'] as $k=>$v) {
                Yii::$app->db->createCommand()->update(AgentActivity::tableName(),['sort'=>$k+1],'id='.$v)->execute();
            }
            $this->writeJson(2,self::CODE_OK);
        }
    }

    /**
     * 代理后台活动删除
     */
    public function actionAgentActivityDel()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $result = Yii::$app->db->createCommand()->update('conf_agent_activity',['status'=>0],'id = '.$request['id'])->execute();
            if ($result) {
                $this->writeJson(2,self::CODE_OK);
            } else {
                $this->writeJson(2,self::CODE_ERROR);
            }

        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

    /**
     * 代理后台活动历史信息
     *
     */
    public function actionAgentActivityHistory(){
        if(Yii::$app->request->isPost){
            $page = Yii::$app->request->get('page',1);
            $limit = Yii::$app->request->get('limit',10);
            $data = date('Y-m-d H:i:s');
            $rows = (new Query())
                ->select('*')
                ->from('conf_agent_activity')
                ->where("status = 0 or end_time < '". $data . "'")
                ->orderBy('sort')
                ->offset(($page-1)*$limit)
                ->limit($limit)
                ->all();
            foreach ($rows as $key=>$val){
                if($val['show_type'] == 1){
                    $showTypeName = '一次';
                }else if($val['show_type'] == 2){
                    $showTypeName = '每次';
                }else if($val['show_type'] == 3){
                    $showTypeName = '时间段';
                }else{
                    $showTypeName = '';
                }

                $rows[$key]['showTypeName'] = $showTypeName;

            }

            $count = (new Query())
                ->select('*')
                ->from('conf_agent_activity')
                ->where("status = 0 and end_time < '". $data . "'")
                ->count();

            $this->writeJson(1,self::CODE_OK,'',$count,$rows?$rows:[]);
        }
    }

    /**
     * 渠道设置列表
     *
     */
    public function actionChannelList(){
        if(Yii::$app->request->isPost){
            if(Yii::$app->request->post()){
                $request = Yii::$app->request->post();

                $limit = $request['limit'];
                $page = $request['page'];

                $where=[];
                $where[] = 'status = 1';
                if(isset($request['channelId']) && $request['channelId']){
                    $where[] = 'channel_id = '.$request['channelId'];
                }

                if(isset($request['agentId']) && $request['agentId']){
                    $where[] = 'agent_id = '.$request['agentId'];
                }

                $where = implode(' and ',$where);

                $channelModel = new Channel();
                $channelList = $channelModel->getDataByCon($where,'*',4,$limit,$page);

                $count = $channelModel->getDataByCon($where,'id',5);

                $this->writeLayui(Code::OK,'success',$count,$channelList);
            }
        }

        return $this->render('channel_list');
    }
    /**
     * 渠道设置
     */
    public function actionChannelSet()
    {
        $request = Yii::$app->request->post();

        $request['status'] = 1;
        if(!isset($request['channel_id']) || !$request['channel_id']){
            $this->writeResult(Code::ERROR,'error');
        }
        if(!isset($request['channel_name']) || !$request['channel_name']){
            $this->writeResult(Code::ERROR,'error');
        }
        if(!isset($request['agent_id']) || !$request['agent_id']){
            $this->writeResult(Code::ERROR,'error');
        }
        
        $channelModel = new Channel();
        $id = isset($request['id']) ? $request['id'] : 0;
        if($channelModel->setChannel($request,$id)){
            $this->writeResult(Code::OK,'success');
        }else{
            $this->writeResult(Code::ERROR,'success');
        }
    }

    /**
     * 代理后台渠道删除
     */
    public function actionChannelDel()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $result = Yii::$app->db->createCommand()->delete('t_channel',['id'=>$request['id']])->execute();
            if ($result) {
                $this->writeJson(2,self::CODE_OK);
            } else {
                $this->writeJson(2,self::CODE_ERROR);
            }

        } else {
            $this->writeJson(2,self::CODE_PARAM_ERROR);
        }
    }

}
