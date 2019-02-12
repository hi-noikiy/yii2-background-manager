<?php
/**
 * User: jw
 * Date: 2018/8/1 0001
 */
namespace app\controllers;

use app\common\Code;
use app\models\MarqueeEditLog;
use yii;
use yii\db\Query;
use app\models\Marquee;

class MarqueeController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 玩家信息列表
     */
    public function actionPlayerMarquee()
    {
        //按照id，时间范围查询，解禁or禁言
        $request = Yii::$app->request->get();
        if ($request['id']) {
            $where[] = 'b.account = "'.$request['id'].'"';
        }
        if ($request['start_time']) {
            $where[] = 'unix_timestamp(start_time) >= '.strtotime($request['start_time']);
        }
        if ($request['end_time']) {
            $where[] = 'unix_timestamp(end_time) <= '.strtotime($request['end_time']);
        }
        $offset = ($request['page']-1)*$request['limit'];

        $where[] = 'b.status = 1';
        $where[] = 'b.type = 201';
        $where = implode(' and ',$where);
        $query = new Query();
        $count = $query->select('b.account')
            ->from(Marquee::tableName().' as b')
            ->leftJoin('login_db.t_lobby_player as a','b.account = a.u_id')
            ->where($where)
            ->count();
        $rows = $query->select('')
            ->orderBy('b.created_time')
            ->limit($request['limit'])
            ->offset($offset)
            ->all();
        $model = new Marquee();
        $user_list = $model->getUserList(1);
        $user_list = array_column($user_list,'player_index');
        foreach ($rows as $key => $row) {
            if (in_array($row['u_id'],$user_list)) {
                $rows[$key]['white'] = 1;
            }
        }
        $this->writeLayui(0,'success',$count,$rows?$rows:[]);

    }

    /**
     * 玩家白名单或黑名单
     * @param sign 白名单 1 ，黑名单 2
     */
    public function actionWhiteBlack()
    {
        //id搜索
        $request = Yii::$app->request->get();
        if (!(isset($request['sign']) && $request['sign'])) {
            $this->writeResult(self::CODE_PARAMS_ERROR);
        }
        $where = 'sign = '.$request['sign'];
        /*if ($request['id']) {
            $where .= " and player_index = ".$request['id'];
        }*/
        $offset = ($request['page']-1)*$request['limit'];
        $query = new Query();
        $count = $query->select('a.player_index')
            ->from('post_user_list as a')
            ->leftJoin('login_db.t_lobby_player as b' ,'a.player_index = b.u_id')
            ->leftJoin('t_marquee as c' ,'a.player_index = c.account')
            ->groupBy(['a.player_index'])
            ->where($where)
            ->orderBy('c.updated_time desc,a.created_time desc')
            ->count();
        $rows = $query->select('')
            ->limit($request['limit'])
            ->offset($offset)
            ->all();
        $this->writeLayui(0,'',$count,$rows?$rows:[]);
    }

    /**
     * 加入白名单或白名单
     * @param sign 白名单 1 ，黑名单 2
     */
    public function actionOperateList()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id'] && isset($request['sign']) && $request['sign']) {
            $player = Yii::$app->login_db->createCommand('select * from t_lobby_player where u_id = :u_id')
                ->bindValue(':u_id',$request['id'])
                ->queryOne();
            if (!$player) {
                $this->writeResult(self::CODE_PLAYER_NOT_FOUND);
            }
            $query = new Query();
            //是否已在列表中
            $exist_list = $query->select('*')
                ->from('post_user_list')
                ->where('player_index = '.$request['id'])
                ->one();
            if ($exist_list) {
                if ($exist_list['sign'] == 1) {
                    $this->writeResult(self::CODE_PLAYER_EXIST_LIST,'玩家已在白名单中');
                } else {
                    $this->writeResult(self::CODE_PLAYER_EXIST_LIST,'玩家已在黑名单中');
                }

            }
            $result = Yii::$app->db->createCommand()->insert('post_user_list',['player_index'=>$request['id'],'sign'=>$request['sign'],'created_time'=>time()])->execute();
            if ($result) {
                $this->writeResult();
            } else {
                $this->writeResult(self::CODE_ERROR);
            }

        }
        $this->writeResult(self::CODE_PARAMS_ERROR);

    }

    /**
     * 移除白名单或黑名单
     * @param sign 白名单 1 ，黑名单 2
     */
    public function actionRemoveList()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id'] && isset($request['sign']) && $request['sign']) {
            $player = Yii::$app->login_db->createCommand('select * from t_lobby_player where u_id = :u_id')
                ->bindValue(':u_id',$request['id'])
                ->queryOne();
            if (!$player) {
                $this->writeResult(self::CODE_PLAYER_NOT_FOUND);
            }
            $query = new Query();
            //是否已在列表中
            $exist_list = $query->select('*')
                ->from('post_user_list')
                ->where('player_index = '.$request['id'].' and sign = '.$request['sign'])
                ->one();
            if (!$exist_list) {
                $this->writeResult();
            }
            $result = Yii::$app->db->createCommand()->delete('post_user_list',['player_index'=>$request['id'],'sign'=>$request['sign']])->execute();
            if ($result) {
                $this->writeResult();
            } else {
                $this->writeResult(self::CODE_ERROR);
            }

        }
        $this->writeResult(self::CODE_PARAMS_ERROR);
    }

    //GM信息
    public function actionGmMarquee()
    {
        //已播，未播，全部
        $request = Yii::$app->request->get();
        $where = ' status = 1 and type = 101';
        if (isset($request['is_play'])) {
            $where .= ' and is_play = '.$request['is_play'];
        }
        $offset = ($request['page']-1)*$request['limit'];
        $count = (new Query())->select('*')
            ->from(Marquee::tableName())
            ->where($where)
            ->count();
        $rows = (new Query())->select('*')
            ->from(Marquee::tableName())
            ->where($where)
            ->orderBy('id asc')
            ->limit($request['limit'])
            ->offset($offset)
            ->all();
        if ($rows) {
            foreach ($rows as $key=>$val) {
                $rows[$key]['interval_time'] = (new Marquee())->getInterval(101);
            }
        }
        $this->writeLayui(0,'success',$count,$rows?$rows:[]);

    }

    /**
     * 跑马灯播放暂停 播放1，暂停2
     * @throws yii\db\Exception
     *
     */
    public function actionGmPlayPause()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $log_model = new MarqueeEditLog();
            if ($request['play_status'] == 1) {
                $operate = $log_model->operate_play;
                $play_status = 1;
            } else {
                $operate = $log_model->operate_pause;
                $play_status = 2;
            }
            $result = Yii::$app->db->createCommand()->update(Marquee::tableName(),['play_status'=>$play_status,'updated_time'=>date('Y-m-d H:i:s',time())],'id = '.$request['id'])->execute();
            if ($result) {
                //成功后将修改状态重置
                Yii::$app->db->createCommand()->update(Marquee::tableName(),['is_edit'=>0,'updated_time'=>date('Y-m-d H:i:s',time())],'id = '.$request['id'])->execute();

                $log_model->addEditLog($request['id'],$operate);

                //如果开始播放就放入redis(跑马灯id和跑马灯开始时间)
                //暂停要推送
                $model = new Marquee();
                $res = $model::find()
                    ->select('id,start_time')
                    ->where('id='.$request['id'])
                    ->asArray()
                    ->one();
                if(! CommonController::toPMDRedis($res['id'],$res['start_time'])){
                    $this->writeResult(self::CODE_ERROR,"操作redis失败");
                }

                $this->writeResult();
            } else {
                $this->writeResult(self::CODE_ERROR);
            }


        } else {
            $this->writeResult(self::CODE_PARAMS_ERROR);
        }

    }

    //信息详情
    public function actionGmDetail()
    {
        $request = Yii::$app->request->get();
        if (isset($request['id']) && $request['id']) {
            $row = Marquee::findOne($request['id']);
            $this->writeLayui(
                0,
                'success',
                1,
                $row?[$row->attributes]:[]
            );
        } else {
            $this->writeResult(Code::CODE_PARAMS_ERROR);
        }
    }

    //删除跑马灯
    public function actionGmDelete()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            if (Yii::$app->db->createCommand()->update(Marquee::tableName(),['status'=>0,'updated_time'=>date('Y-m-d H:i:s',time())],'id = '.$request['id'])->execute()) {
                $log_model = new MarqueeEditLog();
                $log_model->addEditLog($request['id'],$log_model->operate_del);
                $marquee_info = Marquee::findOne($request['id']);
                if(! CommonController::toPMDRedis($request['id'],$marquee_info['start_time'])){
                    $this->writeResult(self::CODE_ERROR,"操作redis失败");
                }
                $this->writeResult();
            } else {
                $this->writeResult(self::CODE_ERROR);
            };
        } else {
            $this->writeResult(self::CODE_PARAMS_ERROR);
        }
    }

    /**
     * 修改跑马灯 log
     *
     */
    public function actionGmEditLog()
    {
        //查询条件 修改日期范围 是否公告 id|操作人|内容关键词
        $where[]= 'l.status = 1';
        $request = Yii::$app->request->get();
        if (!isset($request['id'])) {
            $this->writeResult(self::CODE_PARAMS_ERROR);
        }
        $where[]= ' l.marquee_id = '.$request['id'];
        $offset = ($request['page']-1)*$request['limit'];
        if (isset($request['l.start_time']) && $request['start_time']) {
            $where[] = 'l.updated_time >= "'.$request['start_time'].'"';
        }
        if (isset($request['end_time']) && $request['end_time']) {
            $where[] = 'l.updated_time <= "'.$request['end_time'].'"';
        }
        /*if (isset($request['is_notice']) && $request['is_notice']) {
            $where[] = 't_marquee.is_notice = '.$request['is_notice'];
        }*/

        if (isset($request['keyword']) && $request['keyword']) {
            $where[] = '(l.marquee_id like "%'.$request['keyword'].'%" or l.content like "%'.$request['keyword'].'%" or l.account like "%'.$request['keyword'].'%")';
        }

        $where = implode(' and ',$where);
        $query = new Query();
        $count = $query->select('l.id')
            ->from(MarqueeEditLog::tableName().' as l ')
            ->leftJoin('t_marquee',' l.marquee_id = t_marquee.id ')
            ->where($where)
            ->count('l.id');
        $rows = $query->select('l.*,t_marquee.is_notice')
              ->limit($request['limit'])
              ->offset($offset)
              ->all();
        if ($rows) {
            foreach ($rows as $key=>$val) {
                switch ($val['operate_type']) {
                    case 1:
                        $rows[$key]['operate_type'] = '修改';
                        break;
                    case 2:
                        $rows[$key]['operate_type'] = '删除';
                        break;
                    case 3:
                        $rows[$key]['operate_type'] = '播放';
                        break;
                    case 4:
                        $rows[$key]['operate_type'] = '暂停';
                        break;
                }
                $val['content'] = json_decode($val['content'],true);
                if (isset($val['content']['start_time'])) {
                    $rows[$key]['start_time'] = $val['content']['start_time']['before'].'改为'.$val['content']['start_time']['after'];
                }
                if (isset($val['content']['end_time'])) {
                    $rows[$key]['end_time'] = $val['content']['end_time']['before'].'改为'.$val['content']['end_time']['after'];
                }
                if (isset($val['content']['interval_time'])) {
                    $rows[$key]['interval_time'] = $val['content']['interval_time']['before'].'s改为'.$val['content']['interval_time']['after'].'s';
                }
                if (isset($val['content']['is_notice'])) {
                    $rows[$key]['is_notice'] = ($val['content']['is_notice']['before']?'有':'无').'改为'.($val['content']['is_notice']['after']?'有':'无');
                }
                unset($rows[$key]['content']);
                if (isset($val['content']['content'])) {
                    $rows[$key]['content'] = $val['content']['content']['before'].'改为'.$val['content']['content']['after'];
                }
            }

        }
        $this->writeLayui(0,'',$count,$rows?$rows:[]);
    }

    /**
     * 创建跑马灯
     *
     */
    public function actionGmCreate()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $model = Marquee::findOne($request['id']);
            $old_content = $model->attributes;
            $old_content['updated_time'] = date('Y-m-d H:i:s',time());
            $model->is_edit = 1;
        } else {
            $model = new Marquee();
            $model->type = 101;
            $model->cost = 0;
        }
        $model->account = Yii::$app->user->getId()?Yii::$app->user->getId():1;
        if ($model->load(Yii::$app->request->post(),'') && $model->save()) {
            if (isset($request['id']) && $request['id']) {
                $log_model = new MarqueeEditLog();
                $log_model->addEditLog($request['id'],$log_model->operate_edit,$old_content,$model->attributes);
            }

            if(! CommonController::toPMDRedis($model->id,$model->start_time)){
                $this->writeResult(self::CODE_ERROR,"操作redis失败");
            }

            $this->writeResult();
        } else {
            $this->writeResult(self::CODE_ERROR);
        }
    }

    /**
     * 获取跑马灯的配置项
     * @return [type] [description]
     */
    public function actionGetPmConfig(){
        $model = new Marquee();
        $gm_interval = $model -> getInterval(101);
        $user_interval = $model -> getInterval(201);
        $deduct = $model -> getPmdDeduct();
        $show_time = $model -> getPmdShowtime();
        $data['deduct'] = $deduct;
        $data['gm_post'] = $gm_interval;
        $data['user_post'] = $user_interval;
        $data['show_time'] = $show_time;
        return json_encode([
            'code' => self::CODE_OK,
            'msg' => '',
            'data' => $data
        ]);
    }

    /**
     * 跑马灯页面
     */
    public function actionMarqueeIndex()
    {
        return $this->render('marquee_index');
    }

    /**
     * 跑马灯配置项页面
     */
    public function actionMarqueeConf()
    {
        return $this->render('marquee_conf');
    }

    /**
     * 修改跑马灯配置项
     *
     */
    public function actionUpdatePmConfig(){
        $request = Yii::$app->request->post();
        if (!$request['type'] || !$request['value']) {
            $this->writeResult(self::CODE_PARAMS_ERROR);
        }
        $model = new Marquee();
        $result = null;
        switch ($request['type']) {
            case 1://GM消息间隔
                $result = $model -> UpdateInterval(101,intval($request['value']));
                break;
            case 2://用户消息间隔
                $result = $model -> UpdateInterval(201,intval($request['value']));
                break;
            case 3:
                $result = $model -> UpdateDeduct(intval($request['value']));
                break;
            case 4:
                $result = $model -> UpdateShowTime(intval($request['value']));
                break;
        }
        if ($result) {
            $this->writeResult(self::CODE_OK);
        }
        $this->writeResult(self::CODE_ERROR);
    }


}