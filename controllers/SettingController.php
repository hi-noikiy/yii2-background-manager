<?php
/**
 * User: jw
 * Date: 2018/7/23 0023
 */
namespace app\controllers;

use app\common\Code;
use app\common\Tool;
use Yii;
use yii\base\Curl;
use app\models\ServiceRecharge;

/**
 * Class SettingController
 * @package app\controllers
 */
class SettingController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 官方充值页面
     */
    public function actionRechargeIndex()
    {
        return $this->render('recharge_index');
    }

    public function actionRecharge()
    {
        $model = new ServiceRecharge();
        if ($model->load(Yii::$app->request->post(),'')) {
            $player = $model->getPlayer($model->player_id);

            if (!$player) {
                return $this->writeResult(Code::CODE_PLAYID_NOT_FOUND);
            } else {
                $model->player_name = $player['weixin_nickname']?$player['weixin_nickname']:'';
                $model->use_by = 1;
                $model->time = date('Y-m-d H:i:s',time());
                if ($model->validate()) {
//                    Tool::sendGold()
                    $present_data = [
                        'sourceType'=>Tool::RECHARGE_WEB,
                        'propsType'=>$model->gold_type,
                        'count'=>$model->gold_num,
                        'operateType'=>$model->use_type,
                        'gameId'=>$model->gid,//只有大厅游戏id,1114112
                        'userId'=>$model->player_id
                    ];
                    $present_url  = Yii::$app->params['recharge_Url'];
                    $curl = new Curl();
                    $present_data = 'msg=' . json_encode($present_data, JSON_UNESCAPED_UNICODE);

                    $info = $curl->get($present_url.'?'.$present_data);
                    $info = json_decode($info,true);
                    if (isset($info['code'])) {
                        if ($info['success'] && $info['code'] != 2) {
                            $model->status = 1;
                            $model->save();
                            return $this->writeResult();
                        } else {
                            $model->status = 2;
                            $model->save();
                            return $this->writeResult(self::CODE_ERROR);
                        }

                    } else {
                        $model->status = 3;
                        $model->save();
                        return $this->writeResult(Code::CODE_RECHARGE_TIMEOUT);
                    }
                }

            }
        }
    }

}
