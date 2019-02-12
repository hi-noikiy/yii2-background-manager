<?php
/**
 * User: SeaReef
 * Date: 2018/6/14 21:17
 */

namespace app\controllers;

use app\common\Code;
use app\common\Common;
use app\common\DailiCalc;
use app\common\RedisKey;
use app\common\Tool;
use app\models\AgentBusinessList;
use app\models\DailiPlayer;
use app\models\LobbyPlayer;
use app\models\LogRebate;
use app\models\PlayerMember;
use app\models\OperUserExpendDay;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use app\common\helpers\Sms;


class AgentController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    public $layout = 'common';

    public $level = 1;//玩家属于几级（不是计算返利的等级，之时判断该玩家上面又几级，已变分级显示）

    public function init()
    {
        $this->redis = Yii::$app->redis;
    }

    /**
     * 查询代理
     */
    public function actionAgentSearch()
    {
        if (Yii::$app->request->isPost) {
            $uid = Yii::$app->request->post('uid');
            if($this->channel_under_list){
                if(!in_array($uid,$this->channel_under_list)){
                    $this->writeResult(Code::ERROR,'当前渠道无该玩家');
                }
            }

            $fields = 'player_id,name,tel,address,true_name,daili_level,parent_index,member_num,open_num,create_time,pay_back_gold,all_pay_back_gold,last_login_time';
            $data = DailiPlayer::find()
                ->select($fields)
                ->where(['player_id' => $uid])
                ->asArray()
                ->one();

            if (!$data) {
                $this->writeResult(self::CODE_ERROR, '该代理不存在！');
            }
            //获取玩家下级数量
            $playerMember = new PlayerMember();
            $num = $playerMember->getLowerNum($uid);

            //剩余可开通代理数量
            $data['member_num'] = $num;
            $open_num_residue = 0;
            if (($data['open_num'] - (int)$data['member_num']) > 0) {
                $open_num_residue = $data['open_num'] - $data['member_num'];
            }

            //玩家登陆时间
            $playerInfo = $this->getPlayerInfo($uid, 'last_login_time');
            $data['last_login_time'] = $playerInfo['last_login_time'];

            $data['open_num_residue'] = $open_num_residue;
            $data['all_pay_back_gold'] = round($data['all_pay_back_gold'] / 100, 2);
            $data['pay_back_gold'] = round($data['pay_back_gold'] / 100, 2);

            //代理等级
            if ($data['daili_level'] == 1) {
                $data['daili_level'] = '渠道合伙人';
            } else {
                $data['daili_level'] = '普通代理';
            }

            //获取下级代理数量（直属下级）
            $Daili = new DailiPlayer();
            $dailiNum = $Daili->getDailiNum($uid);
            $data['dailiNum'] = $dailiNum['num'];

            $parentData = DailiPlayer::find()->select('name,create_time,daili_level')->where(['player_id' => $data['parent_index']])->asArray()->one();
            if ($parentData) {
                if ($data['parent_index'] == '999') {
                    $data['childrenId'] = $data['parent_index'];
                    $data['parentName'] = $parentData['name'] ?: '系统';
                    $data['parentCreateTime'] = $parentData['create_time'] ?: '';
                    $data['parentDailiLevel'] = $parentData['daili_level'] ?: '顶级';
                } else {
                    $data['childrenId'] = $data['parent_index'];
                    $data['parentName'] = $parentData['name'] ?: '';
                    $data['parentCreateTime'] = $parentData['create_time'] ?: '';
                    $data['parentDailiLevel'] = $parentData['daili_level'] ?: '';
                }
            }
            if ($data) {
                unset($data['id']);
                $this->writeResult(Code::OK, 'success', $data);
            } else {
                $this->writeResult(self::CODE_ERROR, '该代理不存在!');
            }
        } else {
            $data = [
                'player_id' => '',
                'name' => '',
                'tel' => '',
                'address' => '',
                'true_name' => '',
                'daili_level' => '',
                'parent_index' => '',
                'member_num' => '',
                'open_num' => '',
                'open_num_residue' => '',
                'create_time' => '',
                'pay_back_money' => '',
                'all_pay_back_money' => '',
                'last_login_time' => '',
            ];
        }

        return $this->render('agent_search', ['data' => $data]);
    }

    /**
     * 添加代理
     */
    public function actionAgentAdd()
    {
        Yii::info("添加代理");
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (isset($post['userID']) && $post['userID']) {
                //检测玩家是否存在
                $loginModel = new LobbyPlayer();
                if (!$loginModel->checkUser($post['userID'])) {
                    $this->writeResult(self::CODE_ERROR, '该玩家不存在!');
                }
                $model = new DailiPlayer();
                if ($model->getById($post['userID'])) {
                    $this->writeResult(self::CODE_ERROR, '该玩家已是代理!');
                }
            } else {
                $this->writeResult(self::CODE_ERROR, '参数错误!');
            }
            Yii::info("添加代理关系");

            $parent = new PlayerMember();
            $parentIndex = $parent->getFatherId($post['userID']) ?: 999;

            Yii::info("添加代理表");
            $db = Yii::$app->db;
            $info = $db->createCommand()->insert('t_daili_player', [
                'player_id' => $post['userID'],
                'daili_level' => $post['agentLevel'],
                'name' => $post['nickName'],
                'true_name' => $post['trueName'],
                'parent_index' => $parentIndex,
                'tel' => $post['tel'],
                'address' => $post['province'] . '-' . $post['city'] . '-' . $post['county'] . '-' . $post['deatiledAddress'],
                'create_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s')
            ])->execute();
            Yii::info("添加代理表成功");

            $t = date('Y-m-d H:i:s');
            $db->createCommand("INSERT INTO t_player_member VALUES(NULL, '{$post['userID']}', '{$parentIndex}', '{$t}') ON DUPLICATE KEY UPDATE bind_time = '{$t}'")->execute();

            Yii::info("添加代理关系成功");

            // 开通代理
            DailiCalc::openDaili($post['userID']);

            if ($info) {
                Yii::info("添加代理成功！");
                $this->writeResult();
            }
        }

        return $this->render('agent_add');
    }

    private function updateUnder($player_id)
    {
        $redis = Yii::$app->redis;
        $parent_id = $redis->zscore(RedisKey::INF_AGENT_RELATION, $player_id);
        if ($parent_id == 999) {
            return;
        } else {
            $parent_info = json_decode($redis->hget(RedisKey::INF_AGNET, $parent_id));
            $parent_info['today_under_agent'] += 1;
            $parent_info['today_under_user'] -= 1;
            $parent_info['today_agent'] += 1;
            $parent_info['today_user'] -= 1;
            $parent_info['under_agent'] += 1;
            $parent_info['under_user'] -= 1;
            $redis->hset(RedisKey::INF_AGNET, json_encode($parent_info));

            $this->updateUnder($parent_id);
        }
    }

    /**
     * 返利透明化
     */
    public function actionRebateDetails22()
    {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $page = $request->post('page', 1);
            $limit = $request->post('limit', 10);
            $field = $request->post('field', 'create_time');
            $order = $request->post('order', 'DESC');
            $start_time = $request->get('start_time') ? strtotime($request->get('start_time')) : '';
            $end_time = $request->get('end_time') ? strtotime($request->get('end_time')) : '';
            $player_id = $request->get('player_id', '');

            $count = (new Query())
                ->from('t_income_details')
//                ->andFilterWhere(['and', "create_time > '{$start_time}'", "create_time < '{$end_time}'"])
                ->andFilterWhere(['player_id' => $player_id])
                ->count();
//                ->createCommand()->sql;
//            echo $count;
//            die();

            $data = (new Query())
                ->select('*')
                ->from('t_income_details')
//                ->andFilterWhere(['and', "create_time > '{$start_time}'", "create_time < '{$end_time}'"])
                ->andFilterWhere(['player_id' => $player_id])
                ->orderBy("$field $order")
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->all();

            $this->writeLayui(Code::CODE_LAYUI_OK, '', $count, $data);
        } else {
            return $this->render('rebate_details1');
        }
    }

    /**
     * 返利透明化
     */
    public function actionRebateDetails1()
    {
        $request = Yii::$app->request;
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        $start_time = $request->get('start', date('Y-m-d', time() - 86400 * 7));
        $end_time = $request->get('end', date('Y-m-d', time()));
        $player_id = $request->get('player_id', '');
//        var_dump($page, $limit, $start_time, $end_time, $player_id);

        $count = (new Query())
            ->from('t_income_details')
            ->andFilterWhere(['player_id' => $player_id])
            ->andFilterWhere(['and', "create_time > '{$start_time}'", "create_time < '{$end_time}'"])
            ->count();
        $data = (new Query())
            ->select('*')
            ->from('t_income_details')
            ->andFilterWhere(['player_id' => $player_id])
            ->andFilterWhere(['and', "create_time > '{$start_time}'", "create_time < '{$end_time}'"])
            ->all();

        $this->writeLayui(Code::CODE_LAYUI_OK, '', $count, $data);
    }

    const ALL_UNDER_DAILI = 'daili:all_under_daili';

    const ALL_UNDER_PLAYER = 'player:all_under_player';

    /**
     * 代理列表
     */
    public function actionAgentList()
    {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $page = $request['page'];
            $limit = $request['limit'];
            if(isset($request['field']) && isset($request['order'])){
                $field = $request['field'];
                $orderType = $request['order'];
            }else{
                $field = 'create_time';
                $orderType = 'asc';
            }

            $where = '';
            if (isset($request['agentNick']) && $request['agentNick']) {
                $where = 'name LIKE ' . "'%" . $request['agentNick'] . "%'";
            }

            if (isset($request['playerId']) && $request['playerId']) {
                if ($where) {
                    $where .= 'AND player_id = ' . $request['playerId'];
                } else {
                    $where = 'player_id = ' . $request['playerId'];
                }
            }

            $data = (new Query())
                ->select('*')
                ->from('t_daili_player')
                ->where($where)
                ->filterWhere(['in','player_id',$this->channel_under_list])
                ->orderBy($field.' '.$orderType)
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->all();

            $redis = Yii::$app->redis;
            $week_suffix = date('Ymd', strtotime(date('Y-m-d')) - date('w', strtotime(date('Y-m-d', time() - 86400))) * 86400);

            foreach ($data as $key => $value) {
                $data[$key]['pay_back_gold'] = $value['pay_back_gold'] / 110;//单位从元宝转成元
                $data[$key]['all_pay_back_gold'] = $value['all_pay_back_gold']/110;//总收益单位从元宝转成元
                $data[$key]['all_consume'] = round($redis->hget(RedisKey::INF_UNDER_ALL_CONSUME, $data[$key]['player_id']) / 110, 2) ? : 0;
                $data[$key]['week_consume'] = round($redis->hget(RedisKey::INF_UNDER_WEEK_CONSUME . $week_suffix, $data[$key]['player_id']) / 110, 2) ? : 0;
                $under = $this->getDailiInfoInterface($data[$key]['player_id']);
                $data[$key]['under_agent'] = $under['allUnderDaili'] ?: 0;
                $data[$key]['under_user'] = $under['allUnderPlayer'] ?: 0;
            }

            $count = (new Query())
                ->select('*')
                ->from('t_daili_player')
                ->where($where)
                ->filterWhere(['in','player_id',$this->channel_under_list])
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->count();

            $this->writeLayui(Code::OK, 'success', $count, $data);
        } else {
            return $this->render('agent_list');
        }
    }

    /**
     * 查询跟进记录
     *
     */
    public function actionAdminOperationRecord()
    {
        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->post()) {
                $request = Yii::$app->request->post();

                $page = $request['page'];
                $limit = $request['limit'];

                $type = 5;
                if (isset($request['type']) && $request['type']) {
                    $type = $request['type'];
                }
                $where['op_type'] = $type;
                if (isset($request['uid']) && $request['uid']) {
                    $where['op_player_id'] = $request['uid'];
                }
                $data = (new Query())
                    ->select('*')
                    ->from('log_operation')
                    ->where($where)
                    ->limit($limit)
                    ->offset(($page - 1) * $limit)
                    ->all();


                $count = (new Query())
                    ->select('*')
                    ->from('log_operation')
                    ->where(['op_type' => 3])
                    ->limit($limit)
                    ->offset(($page - 1) * $limit)
                    ->count();

                $this->writeLayui(Code::OK, 'success', $count, $data);

            }
        }
    }

    /**
     * 添加跟进记录
     *
     */
    public function actionRecordFollowUpAdd()
    {
        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->post()) {
                $request = Yii::$app->request->post();
                $id = $request['id'];
                $content = "";
                if (isset($request['content']) && $request['content']) {
                    $content = $request['content'];
                }
                $session = Yii::$app->session;
                $userId = $session->get("__name");
                if (!$userId) {
                    echo "<script>alert('账号失效！请重新登陆');history.go(-1);</script>";
                }
                //添加跟进记录
                Tool::LogOperation($userId, Tool::OP_TYPE_FOLLOW_RECORD, $content);

                $DailiPlayer = new DailiPlayer();
                $DailiPlayer->updateDailiPlayer($id, ['follow' => $userId]);


                $this->writeResult();
            }
        }

        $this->writeResult(self::CODE_ERROR, "请求失败！");
    }

    /**
     * 代理经营列表
     */
    public function actionAgentManageList()
    {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $page = $request['page'];
            $limit = $request['limit'];
            if(isset($request['field']) && isset($request['order'])){
                $field = $request['field'];
                $orderType = $request['order'];
            }else{
                $field = 'day_under_consume';
                $orderType = 'desc';
            }

            if (!isset($request['startTime']) || !isset($request['endTime'])) {
                $this->writeResult(self::CODE_ERROR, '时间必填');
            }
            $start = $request['startTime'];
            $end = $request['endTime'];
            if(isset($request['agentId']) && $request['agentId']){
                $where[] = 'agent_id='.$request['agentId'];
                $where[] = "stat_date >= '".$start."'";
                $where[] = "stat_date <= '".$end."'";
            }else{
                $where[] = "stat_date='".$start."'";
            }

            $where = implode(" and ",$where);
            $agentBusinessModel = new AgentBusinessList();
            $data = $agentBusinessModel->getData($where,"*",$limit,$page,$orderType,$field,$this->channel_under_list);

            foreach ($data as $key=>$val){
                $data[$key]['idName'] = $val['agent_id']."<br/>".$val['nickname'];
                $data[$key]['parentIdName'] = $val['parent_id']."<br/>".$val['parent_name'];
                $data[$key]['topIdName'] = $val['top_id']."<br/>".$val['top_name'];
                $data[$key]['telTrueName'] = $val['tel']."<br/>".$val['true_name'];
            }

            $count = $agentBusinessModel->getDataCount($where,$this->channel_under_list);

            return $this->writeLayui(Code::OK, 'ok', $count, $data);
        } else {
            return $this->render('agent_manage_list');
        }
    }


    /**
     * 代理树
     */
    public function actionAgentTree()
    {
        $startDate = '';
        $endDate = '';
        $where = '';
        $playerId = '';
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();

            //处理请求参数
            if (isset($request['agentID']) && $request['agentID']) {
                $playerId = $request['agentID'];
            }
            if (isset($request['startTime']) && $request['startTime']) {
                $startDate = $request['startTime'];
            }
            if (isset($request['endTime']) && $request['endTime']) {
                $endDate = $request['endTime'];
            }

            $where = $playerId ? "player_id = " . $playerId : "parent_index=999";

        } else {
            $where = "parent_index=999";
        }

        $fields = "player_id,name,parent_index";
        $model = new DailiPlayer();
        $data = $model->getDataByCon($where, $fields);

        //玩家消耗
        foreach ($data as $key => $val) {
            $allMember = $this->getAllLowerPlayer($val['player_id']);
            $lowerAllExpends = $allMember ? $this->getExpend($allMember, $startDate, $endDate, 2) : 0;
            $data[$key]['expend'] = $lowerAllExpends;
        }

        return $this->render('agent_tree', ['data' => $data, 'playerId' => $playerId, 'startDate' => $startDate, 'endDate' => $endDate]);
    }

    /**
     * 获取下级
     *
     */
    public function actionGetLower()
    {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            if (isset($request['parentIndex']) && $request['parentIndex']) {
                $parentIndex = $request['parentIndex'];
                $startDate = "";
                if (isset($request['startDate']) && $request['startDate']) {
                    $startDate = $request['startDate'];
                }
                $endDate = "";
                if (isset($request['endDate']) && $request['endDate']) {
                    $endDate = $request['endDate'];
                }

                $where['parent_id'] = $parentIndex;

                $member = new PlayerMember();
                $memberInfo = $member->getDataByCon($where);

                //获取下级详细信息
                $data = $this->getLowerLevelInfo($memberInfo, $parentIndex, $startDate, $endDate);

                //判断为几级玩家
                $this->getLevelNum($parentIndex);

                $this->writeResult(Code::OK, $this->level, $data);
            } else {
                $this->writeResult(self::CODE_ERROR, "111");
            }
        } else {
            $this->writeResult(self::CODE_ERROR, '22');
        }
    }

    /**
     * 实时返利
     */
    public function actionRebateDetails()
    {
        return $this->render('rebate_details1');
    }

    /**
     * 删除代理
     *
     */
    public function actionDelAgent()
    {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $id = $request['id'];
            try {
                $model = new DailiPlayer();
                $thisData = $model->findOne(['id' => $id]);
                $thisData->delete();

                $this->writeResult();
            } catch (Exception $e) {
                Yii::info("删除异常" . $e);
                $this->writeResult(self::CODE_ERROR, '删除失败！');
            }
        }
    }

    /**
     * 修改代理信息
     *
     */
    public function actionEdit()
    {
        try {
            if (Yii::$app->request->isPost) {
                $request = Yii::$app->request->post();
                $model = new DailiPlayer();
                $playerId = 0;
                if (isset($request['playerId']) && $request['playerId']) {
                    $playerId = $request['playerId'];
                    $playerInfo = $model->getDataByCon(['player_id' => $playerId], 'parent_index');
                    if ($playerInfo[0]['parent_index'] != $request['parentIndex']) {
                        if (!isset($request['code']) || !$request['code']) {
                            $this->writeResult(self::CODE_ERROR, '验证码不能为空！');
                        } else {
                            $code = $request['code'];
                            $smsValue = $this->sessionOperate($this->key, 'get');
                            $this->sessionOperate($this->key, 'remove');//操作后删除session

                            if ($smsValue) {
                                $smsArr = explode('-', $smsValue);

                                $smsCode = $smsArr[0];
                                if ($smsCode != $code) {
                                    $this->writeResult(self::CODE_ERROR, '验证码错误！' . $code . '--' . $smsCode);
                                } else {
                                    $smsTime = $smsArr[1];
                                    if ((date('YmdHis') - $smsTime) > 60 * 5) {//验证码有效期5分钟
                                        $this->sessionOperate($this->key, 'remove');
                                        $this->writeResult(self::CODE_ERROR, '验证码失效！');
                                    } else {
                                        //修改绑定关系表和更新代理表 上级id
                                        if (!$this->updateParentId($request['parentIndex'], $playerId)) {
                                            $this->writeResult(self::CODE_ERROR, '修改上级失败1！');
                                        } else {
                                            // 维护代理关系
                                            DailiCalc::updateParentId($request['parentIndex'], $playerId);
                                        }
                                    }
                                }
                            } else {
                                $this->writeResult(self::CODE_ERROR, '修改失败，验证码不合法！');
                            }
                        }
                    }
                }

                /**/
                $data['name'] = $request['uname'];
                $data['address'] = $request['address'];
                $data['tel'] = $request['phone'];
                $data['daili_level'] = $request['agentLevel'];
                $data['sex'] = $request['sex'];
                $data['age'] = $request['age'];
                $data['follow'] = $request['valet'];
                $data['open_num'] = $request['oper_dali_num'];

                if ($model->updateDailiPlayer($request['id'], $data)) {
                    $session = Yii::$app->session;
                    $userId = $session->get("__name");
                    if (!$userId) {
                        echo "<script>alert('账号失效！请重新登陆');history.go(-1);</script>";
                    }
                    $content = "修改后的信息为：姓名-" . $request['uname'] . ";地址-" . $request['address'] . ";联系方式-" . $request['phone'] . ";代理等级-" . $request['agentLevel'] . ";性别-" . $request['sex'] . ";年龄" . $request['age'] . ";跟进人-" . $request['valet'] . ";上级id-" . $request['parentIndex'] . ";可开通代理数量-" . $request['oper_dali_num'];
                    //添加操作记录
                    Tool::LogOperation($userId, Tool::OP_TYPE_EDIT_RECORD, $content, $playerId);

                    $this->writeResult($code = self::CODE_OK, $msg = '修改成功');
                } else {
                    $this->writeResult(self::CODE_ERROR, '修改失败2！');
                }
                exit;

            }
        } catch (Exception $e) {
            $this->writeResult(self::CODE_ERROR, '请求错误，请刷新重试！');
        }

    }

    /**
     * 记录日志
     *
     */
    public function operationLog($log)
    {

    }

    /**
     * 渠道合伙人列表
     *
     */
    public function actionPartnerList()
    {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $page = $request['page'];
            $limit = $request['limit'];

            $where = 'daili_level=1';//daili_level: 1的就是渠道合伙人
            if (isset($request['agentNick']) && $request['agentNick']) {
                $where .= ' and player_id =' . $request['agentNick'];
            }
            if (isset($request['nickName']) && $request['nickName']) {
                $where .= ' and name like ' . '"%' . $request['nickName'] . '%"';
            }

            $data = (new Query())
                ->select('*')
                ->from('t_daili_player')
                ->where($where)
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->all();

            foreach ($data as $key => $val) {
                $OperUserExpendDay = new OperUserExpendDay();
//                $con1['PLAYER_INDEX'] = $val['player_id'];
                $allLowers = $this->getAllLowerPlayer($val['player_id']);
                $expendAll = 0;
                $expendMonth = 0;
                if ($allLowers) {
                    $con1 = "PLAYER_INDEX IN (" . $allLowers . ")";
                    $con2 = "PLAYER_INDEX IN (" . $allLowers . ") and DAY like '" . date('Y-m') . "%'";

                    $expendAll = $OperUserExpendDay->getDataByPlayerId($con1);//历史总消耗
                    $expendMonth = $OperUserExpendDay->getDataByPlayerId($con2) ?: 0;//当月消耗
                }

                $data[$key]['pay_back_gold'] = $val['pay_back_gold'] / 100;

                //查询渠道合伙人伞下玩家信息
                $partner = (new Query())
                    ->select("*")
                    ->from('t_daili_player_below_channel_partner')
                    ->where(['player_id' => $val['player_id']])
                    ->one();

                $data[$key]['lowerDali'] = $partner['daili_num'];//总的代理数
                $data[$key]['lowerPlayer'] = $partner['player_num'];//总的玩家数
                $data[$key]['newDaili'] = $partner['new_daili'];//当天的代理数
                $data[$key]['newPlayer'] = $partner['new_player'];//当天的玩家数
                $data[$key]['goldExpendAll'] = $expendAll;//总消耗
                $data[$key]['goldExpendMonth'] = $expendMonth;//当月消耗
            }

            $count = (new Query())
                ->select('*')
                ->from('t_daili_player')
                ->where($where)
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->count();

            $this->writeLayui(Code::OK, 'success', $count, $data);
        }

        return $this->render('partner_list');
    }

    /**
     * 代理的消耗详情
     *
     */
    public function actionPartnerExpendDetail()
    {
        $request = $this->checkRequestWay(1);

        if (!$request) {
            $this->writeResult(self::CODE_ERROR, "请求失败！");
        }

        $playerId=$request['playerId'];
        if (!$playerId) {
            $this->writeResult(self::CODE_ERROR, "参数错误(用户id不能为空)！");
        }

        $startTime = $request['start_time'] ?: '';
        $endTime = $request['end_time'] ?: '';
        $type = $request['type'] ?: 1;//1日查 2周查

        switch ($type){
            case 1:
                $dateList = Common::getDateList($startTime, $endTime);
                break;
            case 2:
                $dateList = Common::getWeekList($startTime, $endTime);
                break;
            default:
                $dateList = [];
                break;
        }
        if(!$dateList){
            $this->writeResult(Code::CODE_ERROR,'请求错误！');
        }

        $proportion  = Yii::$app->params['gold_withdraw_deposit'];

        $data=[];
        foreach ($dateList as $k=>$d){
            $data[$k]['date']  = $d;

            if($type == 1){
                $thisDate = date('Ymd',strtotime($d));
            }else{
                $thisDate = strtotime($d);
                $thisDate = date('Ymd',strtotime(date('Y-m-d', $thisDate)) - date('w',strtotime(date('Y-m-d', $thisDate - 86400))) * 86400);
            }

            $consume = $this->getPlayerTodayAchievements($playerId,$type,$thisDate) ?: 0;
            $data[$k]['value'] = Common::disposeStr($consume/$proportion);

            if($type == 2){
                $logRebateModel = new LogRebate();
                $weeks = $logRebateModel->getWeeks($playerId,$d);
                $thisWeekEnd = date("Y-m-d",strtotime($d) + 86400 * 6);
                $weekNum = $weeks ? ' (第'.$weeks.'周)' : '';
                $data[$k]['date'] = $d.'-'.$thisWeekEnd.$weekNum;
                $con = [];
                $con[] = 'parent_id='.$playerId;
                $con[] = 'player_id != 0';
                $con[] = 'rebate_week="'.$d.'"';
                $con = implode(' and ',$con);
                $rebate = $logRebateModel->getData($con,'sum(rebate) as rebate',2)['rebate'] ?: 0;
                $data[$k]['rebate'] = Common::disposeStr($rebate/$proportion);
            }
        }

        $this->writeLayui(Code::OK,'success',count($data),$data);
    }

    /**
     * 渠道合伙人下级详情
     *
     */
    public function actionLowerLevelDetail()
    {
        $request = $this->checkRequestWay(1);
        if(!$request){
            $this->writeResult(Code::CODE_ERROR,'查询错误');
        }

        $agentId = $request['agentId'];

        $playerId = '';$startTime = '';$endTime = '';
        if(isset($request['player_id']) && $request['player_id']){
            $playerId = $request['player_id'];
        }
        if($request['start_time']){
            $startTime = $request['start_time'];
        }
        if($request['end_time']){
            $endTime = $request['end_time'];
        }

        $directAgentList=[];
        if($playerId){
            $directAgentList[] = $playerId;
        }else{
            $directAgentList = DailiCalc::getAgentList($agentId,'allDirectDaili');
        }

        $dateList = Common::getDateList($startTime,$endTime);

        $data=[];
        foreach ($directAgentList as $key=>$player){
            $data[$key]['playerId'] = $player;
            $lobbyModel = new LobbyPlayer();
            $data[$key]['nickname'] = $lobbyModel->getPlayerInfo(['u_id'=>$player],'weixin_nickname',2)['weixin_nickname'];
            $consume = 0; $newAgent = 0; $newPlayer = 0;
            foreach ($dateList as $k=>$d){
                $consume += $this->getPlayerTodayAchievements($player,1,$d);
                $newPeople = DailiCalc::getDailiInfo($player,$d);
                $newAgent += $newPeople['nowUnderDaili'];
                $newPlayer += $newPeople['nowUnderPlayer'];
            }
            $data[$key]['consume'] = $consume;
            $data[$key]['newAgent'] = $newAgent;
            $data[$key]['newPlayer'] = $newPlayer;
        }

        $this->writeLayui(Code::OK,'success',count($data),$data);
    }

    /**
     * 代理返利列表
     */
    public function actionRebateList()
    {
        $request = Yii::$app->request->get();
        $page = Yii::$app->request->get('page', 1);
        $limit = Yii::$app->request->get('limit', 10);
        $where = [];
        if (isset($request['start_time']) && $request['start_time']) {
            $where[] = 'create_time >=' . strtotime($request['start_time']);
        }
        if (isset($request['end_time']) && $request['end_time']) {
            $where[] = 'create_time <=' . strtotime($request['end_time']);
        }
        if (isset($request['player_id']) && $request['player_id']) {
            $where[] = 'player_id like "%' . $request['player_id'] . '%"';
        }
        if (isset($request['father_id']) && $request['father_id']) {
            $where[] = 'father_id like "%' . $request['father_id'] . '%"';
        }
        if (isset($request['gfather_id']) && $request['gfather_id']) {
            $where[] = 'gfather_id like "%' . $request['gfather_id'] . '%"';
        }
        if (isset($request['ggfather_id']) && $request['ggfather_id']) {
            $where[] = 'ggfather_id like "%' . $request['ggfather_id'] . '%"';
        }
        $where = implode(' and ', $where);
        $data = (new Query())
            ->select('*, FROM_UNIXTIME(create_time) AS `create_time`')
            ->from('t_income_details')
            ->where($where)
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->orderBy('create_time')
            ->all();

        $count = (new Query())
            ->select('*')
            ->from('t_income_details')
            ->where($where)
            ->count();

        $this->writeLayui(Code::OK, 'success', $count, $data);
    }

    /**
     * 备用查询数据库表
     */
    public function actionTable()
    {
        //查询所有符合条件的表
        $sql = "select table_name from information_schema.tables where table_schema='oss' and table_type='base table'";
        $tables = Yii::$app->db->createCommand($sql)->queryAll();
        $tableNames = array_column($tables, 'table_name');

        var_dump($tableNames);
        exit;
    }

    /**
     * 获取验证码
     * 目前为固定手机号（mobile暂时无效，但暂时功能保留，未免后期需求更改）
     *
     */
    public function actionGetCode()
    {
        if (Yii::$app->request->isPost) {
            $smsValue = $this->sessionOperate($this->key, 'get');
            if ($smsValue) {
                $smsArr = explode("-", $smsValue);
                $smsTime = $smsArr[1];
                if ((date('YmdHis') - $smsTime) < 60) {
                    $this->writeResult(self::CODE_ERROR, '请求过于频繁！');
                }
            }

            $request = Yii::$app->request->post();
            $mobile = Yii::$app->params['change_bind_modile'];//目前为固定手机号
            $Sms = new Sms();
            $code = $Sms->randNumber();
            $msg = "一拳娱乐后台系统的短信验证码：" . $code . "【一拳娱乐】";
            if ($mobile) {
                $res = json_decode($Sms->send($mobile, $msg));
                if ($res->error == 0) {
                    $value = $code . '-' . date("YmdHis");
                    if ($this->sessionOperate($this->key, 'set', $value)) {//验证码存session，用于计算过期时间
                        $this->writeResult(self::CODE_OK, $code);
                    } else {
                        $this->writeResult(self::CODE_ERROR, '发送失败!');
                    }
                } else {
                    Yii::info("获取验证码发送短信失败" . json_encode($res));
                    $this->writeResult(self::CODE_ERROR, '发送失败，请稍后再试！');
                }
            } else {
                $this->writeResult(self::CODE_ERROR, '手机号为空！');
            }
        }
    }

}
