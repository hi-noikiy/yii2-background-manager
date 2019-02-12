<?php
/**
 * User: SeaReef
 * Date: 2018/9/4 21:00
 *
 * 收入分析
 */
namespace app\controllers;

use Yii;

class IncomeController extends CommonBaseController
{
    /**
     * 收入数据
     */
    public function actionIncomeData()
    {
        return $this->render('income_data');
    }

    /**
     * 付费渗透
     */
    public function actionPayFilter()
    {
        return $this->render('pay_filter');
    }

    /**
     * 新玩家价值
     */
    public function actionNewPlayerValue()
    {
        return $this->render('new_player_value');
    }

    /**
     * 充值额度分析
     */
    public function actionRechargeQuota()
    {
        return $this->render('recharge_quota');
    }
}