<?php
/**
 * User: SeaReef
 * Date: 2018/7/13 10:04
 *
 * 公共控制器底层类
 */
namespace app\controllers;

use app\common\Code;
use Yii;
use yii\web\Controller;
use app\models\User;

class CommonController extends Controller
{
    /**
     * 消息编码常量
     */
    const CODE_OK = 0;

    const CODE_ERROR = -1;

    /**
     * 支付中心错误码
     */
    const CODE_PARAM_NOT_ENOUGH = -201;

    const CODE_PARAM_ERROR = -202;

    const CODE_SIGN_ERROR = -203;

    /**
     * 轮播图设置
     */
    const CODE_IMG_EXTENSION_ERROR = -300;
    const CODE_IMG_SIZE_ERROR = -301;

    const CODE_PLAYER_NOT_FOUND = -302;

    /**
     * 权限rbac
     */
    const CODE_AUTH_CATEGORY_NOT_FOUND = -400;
    const CODE_AUTH_CATEGORY_EXISTS = -401;
    const CODE_USER_EXISTS = -402;
    const CODE_ROLE_USED = -403;

    /**
     * 消息编码信息
     */
    public $code_message = [
        self::CODE_OK => 'ok',
        self::CODE_ERROR => 'error',

        self::CODE_PARAM_NOT_ENOUGH => 'params not enough',
        self::CODE_PARAM_ERROR => 'params error',
        self::CODE_SIGN_ERROR => 'sign error',
        self::CODE_IMG_EXTENSION_ERROR => 'file is not img',
        self::CODE_IMG_SIZE_ERROR => 'file size too large',
        self::CODE_AUTH_CATEGORY_NOT_FOUND => 'category not found',
        self::CODE_AUTH_CATEGORY_EXISTS => 'permission category exists',
        self::CODE_USER_EXISTS => 'user exists',
        self::CODE_ROLE_USED => 'role is used',
    ];

    public function writeJsonOk($data){
        $this->writeJson(1, self::CODE_OK,'success', 0, $data);
    }
    public function writeJsonFalse($msg){
        $this->writeJson(2, self::CODE_ERROR,$msg, 0);
    }

    /**
     * 输出layui标准json信息
     *
     * @params $type 数据数据类型、1、layui模式、2、标准模式
     */
    public function writeJson($type = 1, $code = self::CODE_OK, $msg = '', $count = 0, $data = '')
    {
        if ($type == 1) {
            return $this->asJson([
                'code' => $code,
                'msg' => $msg ? : $this->code_message[$code],
                'count' => $count,
                'data' => $data,
            ]);
        }
        if ($type == 2) {
            return $this->asJson([
                'code' => $code,
                'msg' => $msg ? : $this->code_message[$code],
            ]);
        }
    }

    public function writeCode()
    {
        
    }

    /**
     * 跑马灯操作redis
     *
     * @param $id
     * @param $startTime
     * @param int $type 1：需要start_time 2:不需要start_time
     * @return bool
     */
    public static function toPMDRedis($id,$startTime,$type=1){
        if(empty($id) || ($type==1 && empty($startTime))){
            Yii::info("操作redis参数不合法");
            return false;
        }
        if(!empty($startTime)){
            $startTime = strtotime($startTime);
        }
        try{
            $redis = Yii::$app->redis_3;
            $redis_key = Yii::$app->params['redisKeys']['gm_paoma_time'];

            $redis -> zadd($redis_key,$startTime,$id);
        }catch(yii\db\Exception $e){
            Yii::info("操作redis失败".$e);
            return false;
        }

        return true;
    }

    protected function writeResult($code = self::CODE_OK, $msg = 'success',$data='')
    {
        echo json_encode([
            'code' => $code,
            'msg' => $msg ?: $code,
            'data' => $data
        ]);
        exit();
    }

    public function beforeAction($action)
    {
        $uid = Yii::$app->user->getId();
        if ($uid) {
            $model = User::findOne($uid);
        } else {
            return $this->redirect('/user/login');
        }
        if ($model->username == 'admin') {//判断超级管理员
            return true;
        } else {
            $controllerID = Yii::$app->controller->id;
            $actionID = Yii::$app->controller->action->id;
            if ($controllerID == 'index' && $actionID = 'index') {
                return true;
            }
            if ($controllerID == 'auth' && $actionID == 'manager-roles') {//左侧菜单
                return true;
            }
            if(Yii::$app->user->can($controllerID.'/'.$actionID))
            {
                return true;//如该用户能访问该请求，则进行返回
            } else {
                echo '没有访问权限';exit;
            }
        }
    }

    public function writeLayui($code = Code::OK, $message = '', $count, $data)
    {
        echo json_encode([
            'code' => $code,
            'msg' => $message,
            'data' => $data,
            'count' => $count,
        ]);
        die();
    }
}
