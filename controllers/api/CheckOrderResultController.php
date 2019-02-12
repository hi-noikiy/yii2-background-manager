<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/12
 * Time: 14:29
 */

namespace app\controllers\api;


use app\models\Order;

class CheckOrderResultController extends BaseController
{
    public function actionSuccess(){
        $this->render('success');
    }

}