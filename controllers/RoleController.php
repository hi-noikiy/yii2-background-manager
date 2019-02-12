<?php
/**
 * User: SeaReef
 * Date: 2018/6/29 13:52
 */
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class RoleController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'signup'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }
}