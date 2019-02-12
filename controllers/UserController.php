<?php
/**
 * User: SeaReef
 * Date: 2018/6/14 20:45
 */
namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Login;

class UserController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [];

//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['login', 'logout'],
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'actions' => ['login'],
//                        'roles' => ['?'],
//                    ],
//                    [
//                        'allow' => true,
//                        'actions' => ['logot'],
//                        'roles' => ['@'],
//                    ]
//                ],
//            ],
//        ];
    }

    public function actionLogin()
    {
        if (Yii::$app->request->isPost) {
            $request = Yii::$app->request;
            $username = $request->post('username');
            $password = $request->post('password');


            $identity = User::findOne(['username' => $username]);
            if (!$identity) {
                return $this->asJson(['code' => 0, 'msg' => '没有该用户']);
            }

//            判断密码是否正确
            if (Yii::$app->getSecurity()->validatePassword($password, $identity->password_hash)) {
                Yii::$app->user->login($identity);

                /*
                $foo = new \yii\web\User();
                $username = $identity->username;
                $foo->on(\yii\web\User::EVENT_BEFORE_LOGIN, function($username){
                    $login = new Login();
                    $login->username = $username;
                    $login->login_time = date('Y-m-d H:i:s', time());
                    $login->login_ip = $_SERVER['ADD_REMODE'];
                    $login->online = 100;
                    $login->save();
                });
                */

                return $this->asJson(['code' => 2, 'msg' => '登录成功']);
            } else {
                return $this->asJson(['code' => 1, 'msg' => '密码错误']);
            }
        } else {
            return $this->render('login');
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

//        $this->redirect('/user/login');
    }
}