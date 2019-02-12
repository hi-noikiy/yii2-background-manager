<?php
/**
 * User: SeaReef
 * Date: 2018/9/21 10:55
 *
 * 渠道合伙人统计信息
 */
namespace app\commands;

use Yii;
use yii\console\Controller;

class PartnerController extends Controller
{
    /**
     * 渠道合伙人键
     */
    const PARTNER_LIST_KEY = 'partner_list_key';

    /**
     * 渠道合伙人列表
     */
    private $partner_list = [];

    public function init()
    {
        $redis = Yii::$app->redis_3;
        $this->partner_list = $redis->hgetall(self::PARTNER_LIST_KEY);
    }


    /**
     * 伞下详情
     */
    public function actionUmbrella()
    {
        $redis = Yii::$app->redis;
        $partner_list = $this->partner_list;

//        遍历所有渠道合伙人信息
        foreach ($partner_list as $k => $v) {
            if ($k % 2 == 0) {
                $list[] = $v;
            } else {
                $list_info[] = $v;
            }
        }

//        遍历渠道合伙人下的所有玩家信息
        $score = $redis->zrangebyscore(self::PARTNER_LIST_KEY, 30011607, 30011607);
        var_dump($score);
    }


}
