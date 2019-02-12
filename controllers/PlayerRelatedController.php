<?php
/**
 * User: SeaReef
 * Date: 2018/9/4 21:27
 *
 * 玩家相关
 */
namespace app\controllers;

use app\common\Code;
use app\models\DailiPlayer;
use Yii;
use yii\db\Query;
use app\common\helpers\Sms;
use app\models\Player;
use app\models\PlayerMember;
use app\common\DailiCalc;

class PlayerRelatedController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 查询玩家
     */
    public function actionSearchPlayer()
    {
        if (Yii::$app->request->isPost) {
            $uid = Yii::$app->request->post('uid');
            if($this->channel_under_list){
                if(!in_array($uid,$this->channel_under_list)){
                    $this->writeResult(Code::ERROR,'当前渠道无该玩家');
                }
            }

            $res = Yii::$app->db->createCommand("SELECT * FROM login_db.t_lobby_player WHERE u_id=".$uid)->queryOne();

            $data['player_id'] = $res['u_id'];
            $data['nickname'] = $res['weixin_nickname'];
            $data['machine_code'] = $res['machine_code'];
            $data['head_img'] = $res['head_img'];
            $data['phone_num'] = $res['phone_number'];
            $data['reg_time'] = $res['reg_time'];
            $data['last_login_time'] = $res['last_login_time'];
            $data['ip'] = $res['ip'];
            $data['sex'] = $res['sex'];
            $data['province'] = $res['province'];
            $data['city'] = $res['city'];
            $data['status'] = 1;//此处暂时默认为1
            $data['auth_time'] = $res['reg_time'];//此处暂时用注册时间

            if($data){
                if($data['sex'] == 1){
                    $data['sex'] = '男';
                }else{
                    $data['sex'] = '女';
                }
                if($data['status'] == 1){
                    $data['status'] = '正常';
                }else{
                    $data['status'] = '不正常';
                }

                unset($data['id']);
                $PlayerMember = new PlayerMember();
                $parentIndex = $PlayerMember->getFatherId($data['player_id']);

                if($parentIndex){
                    $DailiPlayer = new DailiPlayer();
                    $parentInfo = $DailiPlayer->getById($parentIndex);
                    if($parentInfo){
                        $data['parentIndex'] = $parentIndex;
                        $data['parentName'] = $parentInfo->name ?: '系统';
                        $data['parentCreateTime'] = $parentInfo->create_time ?: 0;
                        $data['parentDailiLevel'] = $parentInfo->daili_level ?: '顶级';
                    }else{
                        $data['parentIndex'] = $parentIndex;
                        $data['parentName'] = '系统';
                        $data['parentCreateTime'] = 0;
                        $data['parentDailiLevel'] = '顶级';
                    }
                }
                $this->writeResult(self::CODE_OK,'',$data);
            }else{
                $this->writeResult(self::CODE_ERROR,'该玩家不存在!');
            }
        } else {
            $data = [
                'player_id'=>'',
                'nickname'=>'',
                'machine_code'=>'',
                'head_img'=>'',
                'phone_num'=>'',
                'reg_time'=>'',
                'last_login_time'=>'',
                'ip'=>'',
                'sex'=>'',
                'province'=>'',
                'city'=>'',
                'status'=>'',
                'auth_time'=>''
            ];
        }

        return $this->render('search_player', ['data' => $data]);
    }

    /**
     * 会员列表
     *
     */
    public function actionMemberList(){
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request->post();
            $page = $request['page'];
            $limit = $request['limit'];

            $model = new PlayerMember();

            $where = '';
            if (isset($request['playerId']) && $request['playerId']) {
//                $where = 'player_id ='.$request['playerId'];
                $where = 'u_id =' . $request['playerId'];
            }
            if (isset($request['dailiId']) && $request['dailiId']) {
                $dailiId = $request['dailiId'];

                $con['parent_id'] = $dailiId;
                $lowerIds = $model->getDataByCon($con,'player_id');
                $lowerIdArr = array_column($lowerIds,'player_id');
                $ids_str = implode($lowerIdArr,',');
                if($ids_str){
                    $where = "u_id in (".$ids_str.")";
                }else{
                    $this->writeResult(self::CODE_ERROR,'该玩家无下级！');
                }
            }

            if(isset($request['hasLogin'])){//今日登陆玩家
                if($request['hasLogin'] == 1){
                    if($where){
                        $where .= ' and last_login_time > "'.date("Y-m-d").'"';
                    }else{
                        $where .= 'last_login_time > "'.date("Y-m-d").'"';
                    }
                }
            }

            $data = (new Query())
                ->select('*')
                ->from('login_db.t_lobby_player')
                ->where($where)
                ->andFilterWhere(['in','u_id',$this->channel_under_list])
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->all();

            Yii::info('玩家列表：'.json_encode($data));
            foreach ($data as $key=>$val){
                $parentIndex = $model->getFatherId($val['u_id']);
                Yii::info('$parentIndex:::'.$parentIndex);
                if($parentIndex){
                    $DailiPlayer = new DailiPlayer();
                    $parentInfo = $DailiPlayer->getById($parentIndex,2);
                    if($parentInfo){
                        $data[$key]['agentId'] = $parentInfo['player_id'];
                        if($parentInfo['player_id'] == 999){
                            $data[$key]['agentName'] = "系统";
                        }else{
                            $data[$key]['agentName'] = $parentInfo['name'];
                        }
                    }else{
                        $data[$key]['agentId'] = $parentIndex;
                        $data[$key]['agentName'] = "系统";
                    }

                }else{
                    $data[$key]['agentId'] = 0;
                    $data[$key]['agentName'] = "无";
                }
                $data[$key]['goldBar'] = $this->getGoldBar($val['u_id']);
            }

            $count = (new Query())
                ->select('*')
                ->from('login_db.t_lobby_player')
                ->where($where)
                ->andFilterWhere(['in','u_id',$this->channel_under_list])
                ->count();

            $this->writeLayui(Code::OK, 'success', $count, $data);
        }

        return $this->render('member_list');
    }

    /**
     * 修改玩家上级id
     */
    public function actionEditParentId(){
        if(Yii::$app->request->isPost){
            if(Yii::$app->request->post()){
                $request = Yii::$app->request->post();
                $parentId='';$playerId='';
                if(isset($request['playerIdCont']) && $request['playerIdCont']){
                    $parentId = $request['playerIdCont'];
                }else{
                    $this->writeResult(self::CODE_ERROR,'上级id不能为空！');
                }
                /*
                                $code='';
                                if(isset($request['identifyingCode']) && $request['identifyingCode']){
                                    $code = $request['identifyingCode'];
                                }else{
                                    $this->writeResult(self::CODE_ERROR,'验证码不能为空！');
                                }
                */
                if(isset($request['playerId']) && $request['playerId']){
                    $playerId = $request['playerId'];
                }else{
                    $this->writeResult(self::CODE_ERROR,'验证码不能为空！');
                }

                /*
                                $smsValue = $this->sessionOperate($this->key,'get');//获取session的值
                                $this->sessionOperate($this->key,'remove');//操作后删除session

                                if($smsValue){
                                    $smsArr = explode('-',$smsValue);

                                    $smsCode = $smsArr[0];
                                    if($smsCode != $code){
                                        $this->writeResult(self::CODE_ERROR,'验证码错误！'.$code.'--'.$smsCode);
                                    }else{
                                        $smsTime = $smsArr[1];
                                        if((date('YmdHis')-$smsTime) > 60*5){//验证码有效期5分钟
                                            $this->sessionOperate($this->key,'remove');
                                            $this->writeResult(self::CODE_ERROR,'验证码失效！');
                                        }
                */
                //修改数据库
                if($parentId && $playerId){
                    $res = $this->updateParentId($parentId,$playerId);
                    if($res){
                        DailiCalc::updateParentId($parentId,$playerId);
                        $this->writeResult(self::CODE_OK,'修改成功!');//修改成功
                    }else{
                        $this->writeResult(self::CODE_ERROR,'修改失败！!');
                    }
                }else{
                    $this->writeResult(self::CODE_ERROR,'数据错误!');
                }
//                    }
//            }else{
//                $this->writeResult(self::CODE_ERROR,'修改失败，验证码不合法！');
//            }
        }
    }
}

/**
 * 玩家列表
 *
 */
public function actionPlayerList(){
    return $this->render('player_list');
}
}
