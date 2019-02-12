<?php
/**
 * User: SeaReef
 * Date: 2018/6/27 16:46
 */

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class LangController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['t1', 't2', 't3'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['t1', 't2'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['t3'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionT1()
    {
        echo 't1';
    }

    public function actionT2()
    {
        echo 't2';
    }

    public function actionT3()
    {
        echo 't3';
    }

    /**
     * 登录授权
     */
    public function actionT4()
    {
        $identity = User::findOne(['username' => 'admin']);
        Yii::$app->user->login($identity);
    }


    /**
     * 退出登录
     */
    public function actionT5()
    {
        Yii::$app->user->logout();
    }

    /**
     * 权限验证
     */
    public function actionT6()
    {
        $auth = Yii::$app->authManager;
        echo '<pre>';
        var_dump($auth);
    }

    public function up()
    {
        $auth = Yii::$app->authManager;

        // 添加 "createPost" 权限
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        // 添加 "updatePost" 权限
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);

        // 添加 "author" 角色并赋予 "createPost" 权限
        $author = $auth->createRole('author');
        $auth->add($author);
        $auth->addChild($author, $createPost);

        // 添加 "admin" 角色并赋予 "updatePost"
        // 和 "author" 权限
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $author);

        // 为用户指派角色。其中 1 和 2 是由 IdentityInterface::getId() 返回的id
        // 通常在你的 User 模型中实现这个函数。
        $auth->assign($author, 2);
        $auth->assign($admin, 1);
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }

    public function actionT7()
    {
        return $this->render('t7');
    }
}