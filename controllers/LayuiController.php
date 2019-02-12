<?php
/**
 * User: SeaReef
 * Date: 2018/8/9 20:30
 */
namespace app\controllers;

use Yii;
use yii\web\Controller;

class LayuiController extends Controller
{
    public $layout = 'layui';

    public function actionT1()
    {
        return $this->render('t1');
    }
}