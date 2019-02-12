<?php
/**
 * User: SeaReef
 * Date: 2018/10/16 15:13
 *
 * 使用redsi模拟代理关系
 */
namespace app\commands;

use yii;
use yii\console\Controller;

class PkController extends Controller
{
    const CACHE_PLAYER_MEMBER = 'cache_player_member';

    public function actionPlayerMember()
    {
        $db = Yii::$app->db;
        $redis = Yii::$app->redis_5;

        $info = $db->createCommand('SELECT * FROM t_player_member LIMIT 10')->queryAll();
        foreach ($info as $k => $v) {
            $i = $redis->zadd(self::CACHE_PLAYER_MEMBER, $v['PLAYER_INDEX'], $v['MEMBER_INDEX']);
            var_dump($i);
        }
    }
}