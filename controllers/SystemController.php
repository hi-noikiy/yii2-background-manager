<?php
/**
 * User: SeaReef
 * Date: 2018/9/5 10:12
 *
 * 平台系统设置
 */
namespace app\controllers;

use app\common\Code;
use app\models\GoldBusinessPlayer;
use app\models\PayUrlConfig;
use app\models\VipRecharge;
use Yii;

class SystemController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 管理员列表
     */
    public function actionManagerList()
    {
        return $this->render('manager_list');
    }

    /**
     * 角色管理
     */
    public function actionAuthRole()
    {
        return $this->render('auth_role');
    }

    /**
     * 权限分类
     */
    public function actionRule()
    {
        return $this->render('rule');
    }

    /**
     * 权限管理
     */
    public function actionRuleManage()
    {
        return $this->render('rule_manage');
    }

    /**
     * 修改密码
     */
    public function actionChangePwd()
    {
        return $this->render('change_pwd');
    }

    /**

     * 白菜设置--白菜列表
     *
     */
    public function actionCabbage(){
        if(Yii::$app->request->isPost) {
            $model = new GoldBusinessPlayer();
            $list = $model->getAll();

            $this->writeLayui(Code::OK, 'success',count($list),$list);
        }

        return $this->render('cabbage');
    }

    /**
     * 白菜列表--修改
     *
     */
    public function actionCabbageEdit(){
        if(Yii::$app->request->isPost){
            $request = Yii::$app->request->post();

            $id = $request['id'];
            $userID = $request['userID'];
            $cabbageName = $request['cabbageName'];
            $tel = $request['tel'];
            $switchCont = $request['switchCont'];

            $data['PLAYER_INDEX'] = $userID;
            $data['NAME'] = $cabbageName;
            $data['TEL'] = $tel;
            $data['SWITCH'] = $switchCont;

            $model = new GoldBusinessPlayer();
            if($model->editData($id,$data)){
                $this->writeResult();
            }else{
                $this->writeResult(self::CODE_ERROR,'修改失败');
            }

        }else{
            $this->writeResult(self::CODE_ERROR,'请求错误,请刷新重试!');
        }
    }
    /**
     * 白菜列表--新增白菜
     *
     */
    public function actionCreate(){
        if(Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();

            if(!$request){
                $this->writeResult(self::CODE_ERROR,'请求错误!');
            }
            $model = new GoldBusinessPlayer();

            $model->PLAYER_INDEX = $request['userID'];
            $model->NAME = $request['cabbageName'];
            $model->TEL = $request['tel'];
            $model->SWITCH = $request['switchCont'];
            $model->CREATE_TIME = date('Y-m-d H:i:s');

            if($model->insert()){
                $this->writeResult();
            }else{
                $this->writeResult(self::CODE_ERROR,'添加失败!');
            }
        }else{
            $this->writeResult(self::CODE_ERROR,'错误请求');
        }
    }

    /**
     * 白菜列表--删除
     *
     */
    public function actionDel(){
        if(Yii::$app->request->isPost){
            $request = Yii::$app->request->post();
            $id = $request['BID'];
            if(!$id){
                $this->writeResult(self::CODE_ERROR,'id不能为空!');
            }

            $model = new GoldBusinessPlayer();
            if($model->del($id)){
                $this->writeResult();
            }else{
                $this->writeResult(self::CODE_ERROR,'数据不存在!');
            }
        }else{
            $this->writeResult(self::CODE_ERROR,'请求错误!');
        }
    }

    /**
     * 白菜设置--白菜货币每日汇总
     *
     */
    public function actionDetail(){
        if(Yii::$app->request->isPost){
            $request = Yii::$app->request->post();
            $limit = $request['limit'];
            $page = $request['page'];

            $date = date('Y-m-d');
            $date1='';$date2='';
            if(isset($request['date1']) && $request['date1']){
                $date1 = $request['date1'];
            }
            if(isset($request['date2']) && $request['date2']){
                $date2 = $request['date2'];
            }
            if(!empty($date1) && ($date1 == $date2)){
                $date2 = date("Y-m-d",strtotime("+1 day",strtotime($date2)));
            }
            if(!$date1 || !$date2){
                $date1 = $date;
                $date2 = date("Y-m-d",strtotime("+1 day",strtotime($date)));
            }

            $model = new GoldBusinessPlayer();
            $data = $model->getStatistics($date1,$date2,$limit,$page);

            if($data){
                $this->writeLayui(Code::OK, 'success',$data['rowsCount'],$data['rows']);
            }else{
                $this->writeResult(self::CODE_ERROR,'暂无数据!');
            }

        }else{
            $this->writeResult(self::CODE_ERROR,'请求错误,请刷新重试!');
        }
    }

    /**
     * 白菜设置--白菜五子棋每日对局详情
     *
     */
    public function actionGobangDetail(){
        if(Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $page = $request['page'];
            $limit = $request['limit'];

            $date = date('Y-m-d');
            if(isset($request['date']) && $request['date']){
                $date = $request['date'];
            }
            $where='';
            if(isset($request['playerId']) && $request['playerId']){
                $where = "HOME_PLAYER=".$request['playerId']." or PLAYER=".$request['playerId'];
            }

            //对局详情
            $tableName = 't_oper_goban_log__'.date('Ymd',strtotime($date));
            $juge = Yii::$app->mdwl_activity->createCommand("show tables")->queryAll();
            $cun =  $this->deep_in_array($tableName,$juge);
            if($cun){
                $model = new GoldBusinessPlayer();
                $data = $model->getGobangDetail($tableName,$where,$page,$limit);
                $count = $model->getCount($tableName,$where);

                foreach ($data as $key=>$val){
                    //房主增减
                    if($val['HOME_GOLD_START'] < $val['HOME_GOLD_END']){
                        $data[$key]['HOME_GOLD_TYPE'] = '增加:'.($val['HOME_GOLD_END']-$val['HOME_GOLD_START']);
                    }else{
                        $data[$key]['HOME_GOLD_TYPE'] = '减少:'.($val['HOME_GOLD_START']-$val['HOME_GOLD_END']);
                    }
                    //玩家增减
                    if($val['PLAYER_GOLD_START'] < $val['PLAYER_GOLD_END']){
                        $data[$key]['PLAYER_GOLD_TYPE'] = '增加:'.($val['PLAYER_GOLD_END']-$val['PLAYER_GOLD_START']);
                    }else{
                        $data[$key]['PLAYER_GOLD_TYPE'] = '减少:'.($val['PLAYER_GOLD_START']-$val['PLAYER_GOLD_END']);
                    }
                    $data[$key]['START_TIME'] = date('Y-m-d H:i:s',$val['START_TIME']);
                    $data[$key]['END_TIME'] = date('Y-m-d H:i:s',$val['END_TIME']);
                }
                if($data){
                    $this->writeLayui(Code::OK, 'success',$count,$data);
                }else{
                    $this->writeResult(self::CODE_ERROR,'暂无数据!');
                }
            }else{
                $this->writeResult(self::CODE_ERROR,'暂无数据!');
            }

        }

        $this->writeResult(self::CODE_ERROR,'请求错误,请刷新重试!');
    }

    /**
     * 支付可视化配置
     *
     */
    public function actionPayWay(){
        $model = new PayUrlConfig();

        if(Yii::$app->request->isPost){
            if(Yii::$app->request->post()){
                $request = Yii::$app->request->post();

                if(isset($request['wechat'])){
                    if($request['wechat'] == 0){
                        $model->updateDataByCon(['short_name'=>'wechat'],['is_use'=>0]);
                    }else{
                        $old_id = $model->getIsUseWay('wechat');
                        if($old_id){//将之前的修改成未使用状态
                            $model->updateData($old_id,['is_use'=>0]);
                        }

                        if(!$model->updateData($request['wechat'],['is_use'=>1])){
                            $this->writeResult(self::CODE_ERROR,'微信修改失败');
                        }
                    }
                }

                if(isset($request['alipay'])){
                    if($request['alipay'] == 0){
                        $model->updateDataByCon(['short_name'=>'alipay'],['is_use'=>0]);
                    }else{
                        $old_id = $model->getIsUseWay('alipay');
                        if($old_id){//将之前的修改成未使用状态
                            $model->updateData($old_id,['is_use'=>0]);
                        }
                        if(!$model->updateData($request['alipay'],['is_use'=>1])){
                            $this->writeResult(self::CODE_ERROR,'微信修改失败');
                        }
                    }
                }

                if(isset($request['unionpay'])){
                    if($request['unionpay'] == 0){
                        $model->updateDataByCon(['short_name'=>'unionpay'],['is_use'=>0]);
                    }else{
                        $old_id = $model->getIsUseWay('unionpay');
                        if($old_id){//将之前的修改成未使用状态
                            $model->updateData($old_id,['is_use'=>0]);
                        }
                        if(!$model->updateData($request['unionpay'],['is_use'=>1])){
                            $this->writeResult(self::CODE_ERROR,'银联修改失败');
                        }
                    }
                }

                $this->writeResult();

            }else{
                $this->writeResult(self::CODE_ERROR,'参数为空！');
            }
        }

        $data = $model->getAll();

        $return=array();
        foreach ($data as $key=>$val){
            if($val['short_name'] == "wechat"){
                $return['wechat'][] = $val;
            }elseif($val['short_name'] == "alipay"){
                $return['alipay'][] = $val;
            }elseif($val['short_name'] == "unionpay"){
                $return['unionpay'][] = $val;
            }else{
                continue;
            }
        }

        return $this->render('pay_way',['data'=>$return]);
    }


    /**
     * vip充值账户设置
     *
     */
    public function actionVipRecharge(){
        $rechargeType = Yii::$app->params['VIP_RECHARGE_TYPE'];
        if(Yii::$app->request->isPost){
            if(Yii::$app->request->post()){
                $request = Yii::$app->request->post();
                $limit = $request['limit'];
                $page = $request['page'];

                $VipModel = new VipRecharge();
                $list = $VipModel->getAllVipList($limit,$page);
                foreach ($list as $key=>$val){
                    $list[$key]['uid'] = $key+1;
                    if($val['type'] == 1){
                        $list[$key]['typeName'] = '微信';
                    }else if($val['type'] == 2){
                        $list[$key]['typeName'] = 'QQ';
                    }
                }
                $count = $VipModel->getAllVipCount();

                $this->writeLayui($code = Code::OK, 'success', $count, $list);
            }else{
                $this->writeResult(self::CODE_ERROR,'暂无数据！');
            }
        }

        return $this->render('vip_recharge',['type'=>$rechargeType]);
    }

    /**
     * 添加和更新vip充值账户
     *
     */
    public function actionUpdateVipRechargeInfo(){
        if(Yii::$app->request->isPost){
            if(Yii::$app->request->post()){
                $request = Yii::$app->request->post();
                $vipRechargeModel = new VipRecharge();
                $data = $request;

                if(isset($data['id']) && $data['id']){
                    $id = $data['id'];
                    $info = $vipRechargeModel->getOne($id);
                    if($info){
                        $data['update_time'] = date('Y-m-d H:i:s');
                        if($vipRechargeModel->updateVipRecharge($id,$data)){
                            $this->writeResult();
                        }else{
                            $this->writeResult(self::CODE_ERROR,'修改失败！');
                        }
                    }
                }else{
                    $data['create_time'] = date('Y-m-d H:i:s');
                    $data['update_time'] = date('Y-m-d H:i:s');
                    if(!$vipRechargeModel->addVipRecharge($data)){
                        $this->writeResult(self::CODE_ERROR,'添加失败！');
                    }

                    $this->writeResult();
                }
            }else{
                $this->writeResult(self::CODE_ERROR,'暂无数据！');
            }
        }else{
            $this->writeResult(self::CODE_ERROR,'暂无数据！');
        }
    }

    /**
     * 删除vip充值账户
     *
     */
    public function actionRemove(){
        if(Yii::$app->request->isPost){
            if(Yii::$app->request->post()){
                $request = Yii::$app->request->post();

                if(isset($request['id']) && $request['id']){
                    $id = $request['id'];
                    $vipRechargeModel = new VipRecharge();
                    $vipRechargeModel->removeVipRecharge($id);

                    $this->writeResult();
                }else{
                    $this->writeResult(self::CODE_ERROR,'删除失败！');
                }
            }
        }
    }

}