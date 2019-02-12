<?php
namespace app\common;

use Yii;

/**
 * 代理相关的计算放在这里了
 */
class DailiCalc {

    /**
     * 今天的伞下代理
     */
    const NOW_UNDER_DAILI = ":now_under_daili_";

    /**
     * 今天的伞下玩家
     */
    const NOW_UNDER_PLAYER = ":now_under_player_";

    /**
     * 今天的代理
     */
    const NOW_DIRECT_DAILI = ":now_direct_daili_";

    /**
     * 今天的玩家
     */
    const NOW_DIRECT_PLAYER = ":now_direct_player_";

    /**
     * 伞下代理
     */
    const ALL_UNDER_DAILI = "daili:all_under_daili_";

    /**
     * 伞下玩家
     */
    const ALL_UNDER_PLAYER = "player:all_under_player_";

    /**
     * 直接下线代理
     */
    const ALL_DIRECT_DAILI = "daili:all_direct_daili_";

    /**
     * 直接下线玩家
     */
    const ALL_DIRECT_PLAYER = "player:all_direct_player_";

    /**
     * 代理关系，这个是自己维护，为了查找id用
     */
    const DAILI_INFO = "relation:daili_info";

    /**
     * 代理列表
     */
    const DAILI_LIST = "relation:daili_list";

    /**
     * 定义今日数据有效期
     */
    const EXPIRE_TIME = 172800;

    /**
     * 获取写的redis
     * @return mixed
     */
    public static function getWriteRedis(){
        return Yii::$app->daili_redis_write;
    }

    /**
     * 获取读的redis
     * @return mixed
     */
    public static function getReadRedis(){
        return Yii::$app->daili_redis_read_00;
    }

    /**
     * 获取父亲id
     * @param $id
     * @return int
     */
    public static function getParentId($id)
    {
        $value = self::getReadRedis()->hget(self::DAILI_INFO, $id);
//        var_dump("getParentId: id = ".$id.";    value = ".$value);
        return $value;
    }

    /**
     * 新增一个玩家
     * $sourceId    产生数据的玩家或者代理Id
     * $dailiId     正在修改的代理ID，此ID肯定是$sourceId的上线
     */
    public static function increasePlayer($sourceId, $dailiId){
        $redis = self::getWriteRedis();
        $now = date("Ymd", time());
        $redis->sadd($now.self::NOW_DIRECT_PLAYER.$dailiId, $sourceId);
        $redis->expire($now.self::NOW_DIRECT_PLAYER.$dailiId, self::EXPIRE_TIME);
        $redis->sadd(self::ALL_DIRECT_PLAYER.$dailiId, $sourceId);

        self::increasePlayerUnder($sourceId, $dailiId, $redis, $now, 0);
    }

    /**
     * 新增伞下玩家数据
     * @param $sourceId
     * @param $dailiId
     * @param $redis
     * @param $now
     * @param $index
     */
    public static function increasePlayerUnder($sourceId, $dailiId, $redis, $now, $index){
        if( $index >= 1000000 ){
            return;
        }

//        var_dump("increasePlayerUnder: ".$sourceId."; ".$dailiId);
//        var_dump("\n");
        // 今日的伞下玩家
        $redis->sadd($now.self::NOW_UNDER_PLAYER.$dailiId, $sourceId);
        $redis->expire($now.self::NOW_UNDER_PLAYER.$dailiId,  self::EXPIRE_TIME);

        // 伞下玩家
        $redis->sadd(self::ALL_UNDER_PLAYER.$dailiId, $sourceId);

        $index = $index + 1;
        $parentId = self::getParentId($dailiId);
        if( $parentId == 0 ){
            return;
        }

        self::increasePlayerUnder($sourceId, $parentId, $redis, $now, $index);
    }

    /**
     * 修改一个玩家成为代理
     * @param $sourceId
     * @param $dailiId
     */
    public static function modifyPlayerToDaili($sourceId, $dailiId){
        $redis = self::getWriteRedis();
        $now = date("Ymd", time());

        // 新增一个伞下代理
        $redis->sadd($now.self::NOW_DIRECT_DAILI.$dailiId, $sourceId);
        $redis->expire($now.self::NOW_DIRECT_DAILI.$dailiId,  self::EXPIRE_TIME);

        $redis->sadd(self::ALL_DIRECT_DAILI.$dailiId, $sourceId);

        // 删除伞下玩家
        $redis->srem($now.self::NOW_DIRECT_PLAYER.$dailiId, $sourceId);
        $redis->srem(self::ALL_DIRECT_PLAYER.$dailiId, $sourceId);

        self::modifyPlayerToDailiUnder($sourceId, $dailiId, $redis, $now, 0);
    }

    /**
     * 修改玩家为代理，处理伞下用户
     * @param $sourceId
     * @param $dailiId
     * @param $redis
     * @param $now
     * @param $index
     */
    public static function modifyPlayerToDailiUnder($sourceId, $dailiId, $redis, $now, $index){
        if( $index >= 1000000 ){
            return;
        }

        // 新增伞下代理
        $redis->sadd($now.self::NOW_UNDER_DAILI.$dailiId, $sourceId);
        $redis->expire($now.self::NOW_UNDER_DAILI.$dailiId,  self::EXPIRE_TIME);

        $redis->sadd(self::ALL_UNDER_DAILI.$dailiId, $sourceId);

        // 伞下玩家删除
        $redis->srem(self::ALL_UNDER_PLAYER.$dailiId, $sourceId);
        $redis->srem($now.self::NOW_UNDER_PLAYER.$dailiId, $sourceId);

        $index = $index + 1;
        $parentId = self::getParentId($dailiId);
        if( $parentId == 0 ){
            return;
        }

        self::modifyPlayerToDailiUnder($sourceId, $parentId, $redis, $now, $index);
    }

    /**
     * 修改一个玩家到另一个代理名下
     * $sourceId    产生数据的玩家或者代理Id
     * $srcDailiId  修改前玩家的上级代理
     * $desDailiId  修改后玩家的上级代理
     */
    public static function modifyPlayerOnDaili($sourceId, $srcDailiId, $desDailiId){
        $redis = self::getWriteRedis();
        $now = date("Ymd", time());

        // 处理老代理
        $redis->srem($now.self::NOW_DIRECT_PLAYER.$srcDailiId, $sourceId);
        $redis->srem(self::ALL_DIRECT_PLAYER.$srcDailiId, $sourceId);

        // 处理新代理
        $redis->sadd($now.self::NOW_DIRECT_PLAYER.$desDailiId, $sourceId);
        $redis->expire($now.self::NOW_DIRECT_PLAYER.$desDailiId,  self::EXPIRE_TIME);
        $redis->sadd(self::ALL_DIRECT_PLAYER.$desDailiId, $sourceId);

        self::decreasePlayerUnder($sourceId, $srcDailiId, $redis, $now, 0);
        self::increasePlayerUnder($sourceId, $desDailiId, $redis, $now, 0);
    }

    /**
     * 删除伞下玩家数据
     * @param $sourceId
     * @param $dailiId
     * @param $redis
     * @param $now
     * @param $index
     */
    public static function decreasePlayerUnder($sourceId, $dailiId, $redis, $now, $index){
        if( $index >= 1000000 ){
            return;
        }

        // 今日的伞下玩家
        $redis->srem($now.self::NOW_UNDER_PLAYER.$dailiId, $sourceId);

        // 伞下玩家
        $redis->srem(self::ALL_UNDER_PLAYER.$dailiId, $sourceId);

        $index = $index + 1;
        $parentId = self::getParentId($dailiId);
        if( $parentId == 0 ){
            return;
        }

        self::decreasePlayerUnder($parentId, $parentId, $redis, $now, $index);
    }

    /**
     * 修改一个代理到另一个代理名下
     * $sourceId    产生数据的玩家或者代理Id
     * $desDailiId  修改后玩家的上级代理
     */
    public static function modifyDailiOnDaili($sourceId, $srcDailiId, $desDailiId){
        $redis = self::getWriteRedis();
        $readRedis = self::getReadRedis();
        $now = date("Ymd", time());

        // 老代理名下减少一个直接代理
        if( $srcDailiId != 0 ){
            // 把一个没有上级的代理绑定到一个代理下面
            $redis->srem($now.self::NOW_DIRECT_DAILI.$srcDailiId, $sourceId);
            $redis->srem(self::ALL_DIRECT_DAILI.$srcDailiId, $sourceId);
        }

        // 新代理名下新增一个直接代理
        $redis->sadd($now.self::NOW_DIRECT_DAILI.$desDailiId, $sourceId);
        $redis->expire($now.self::NOW_DIRECT_DAILI.$desDailiId,  self::EXPIRE_TIME);

        $redis->sadd(self::ALL_DIRECT_DAILI.$desDailiId, $sourceId);

        $nowUnderPlayer = $readRedis->smembers($now.self::NOW_UNDER_PLAYER.$sourceId);
        $nowUnderDaili = $readRedis->smembers($now.self::NOW_UNDER_DAILI.$sourceId);
        array_push($nowUnderDaili, $sourceId); // 算上自己
        $allUnderPlayer = $readRedis->smembers(self::ALL_UNDER_PLAYER.$sourceId);
        $allUnderDaili = $readRedis->smembers(self::ALL_UNDER_DAILI.$sourceId);
        array_push($allUnderDaili, $sourceId); // 算上自己

        if( $srcDailiId != 0 ) {
            // 把一个没有上级的代理绑定到一个代理下面
            self::modifyDailiOnDailiDecrease($srcDailiId, $nowUnderPlayer, $nowUnderDaili, $allUnderPlayer, $allUnderDaili, $redis, $now, 0);
        }
        self::modifyDailiOnDailiIncrease($desDailiId, $allUnderPlayer, $allUnderDaili, $redis, $now, 0);
    }

    /**
     * 修改代理到代理，新代理数据增加
     * @param $desDailiId
     * @param $allUnderPlayer
     * @param $allUnderDaili
     * @param $redis
     * @param $now
     * @param $index
     */
    public static function modifyDailiOnDailiIncrease($desDailiId, $allUnderPlayer, $allUnderDaili, $redis, $now, $index){
        if( $index >= 1000000 ){
            return;
        }

        foreach ($allUnderPlayer as $value){
            $redis->sadd($now.self::NOW_UNDER_PLAYER.$desDailiId, $value);
            $redis->expire($now.self::NOW_UNDER_PLAYER.$desDailiId,  self::EXPIRE_TIME);
            $redis->sadd(self::ALL_UNDER_PLAYER.$desDailiId, $value);
        }

        foreach ($allUnderDaili as $value){
            $redis->sadd($now.self::NOW_UNDER_DAILI.$desDailiId, $value);
            $redis->expire($now.self::NOW_UNDER_DAILI.$desDailiId,  self::EXPIRE_TIME);
            $redis->sadd(self::ALL_UNDER_DAILI.$desDailiId, $value);
        }

        $index = $index + 1;
        $parentId = self::getParentId($desDailiId);
        if( $parentId == 0 ){
            return;
        }

        self::modifyDailiOnDailiIncrease($parentId, $allUnderPlayer, $allUnderDaili, $redis, $now, $index);
    }

    /**
     * 修改代理到代理，老代理数据删除
     * @param $srcDailiId
     * @param $nowUnderPlayer
     * @param $nowUnderDaili
     * @param $allUnderPlayer
     * @param $allUnderDaili
     * @param $redis
     * @param $now
     * @param $index
     */
    public static function modifyDailiOnDailiDecrease($srcDailiId, $nowUnderPlayer, $nowUnderDaili, $allUnderPlayer, $allUnderDaili, $redis, $now, $index){
        if( $index >= 1000000 ){
            return;
        }

        foreach ($nowUnderPlayer as $value){
            $redis->srem($now.self::NOW_UNDER_PLAYER.$srcDailiId, $value);
        }

        foreach ($nowUnderPlayer as $value){
            $redis->srem($now.self::NOW_UNDER_DAILI.$srcDailiId, $value);
        }

        foreach ($nowUnderPlayer as $value){
            $redis->srem(self::ALL_UNDER_PLAYER.$srcDailiId, $value);
        }

        foreach ($nowUnderPlayer as $value){
            $redis->srem(self::ALL_UNDER_DAILI.$srcDailiId, $value);
        }

        $index = $index + 1;
        $parentId = self::getParentId($srcDailiId);
        if( $parentId == 0 ){
            return;
        }

        self::modifyDailiOnDailiDecrease($parentId, $nowUnderPlayer, $nowUnderDaili, $allUnderPlayer, $allUnderDaili, $redis, $now, $index);
    }

    /**
     * 更新玩家上级
     * @param $newParentId
     * @param $playerId
     */
    public static function updateParentId($newParentId,$playerId){
        $readRedis = self::getReadRedis();
        $writeRedis = self::getWriteRedis();

        if ($newParentId == 999) {

        } elseif (!$readRedis->sismember(self::DAILI_LIST, $newParentId)) {
            return ;
        }

        Yii::$app->redis->zadd("inf_agent_relation", $newParentId, $playerId);

        $playerIsDaili = $readRedis->sismember(self::DAILI_LIST, $playerId);
        $oldParentId = $readRedis->hget(self::DAILI_INFO, $playerId);
        $writeRedis->hset(self::DAILI_INFO, $playerId, $newParentId);
        if( $oldParentId == null || $oldParentId == 0 ){
            // 玩家之前没有绑定代理
            if( $playerIsDaili ){
                // 自己本身是代理
                self::modifyDailiOnDaili($playerId, 0, $newParentId);
            }else{
                // 自己不是代理，那就进行绑定代理
                self::bindDaili($newParentId,$playerId);
            }
        }else{
            // 玩家之前有绑定
            if($playerIsDaili){
                // 自己是代理
                self::modifyDailiOnDaili($playerId, $oldParentId, $newParentId);
            }else{
                // 自己不是代理,
                self::modifyPlayerOnDaili($playerId, $oldParentId, $newParentId);
            }
        }
    }

    /**
     * 开通代理
     * @param $playerId
     */
    public static function openDaili($playerId){
        $readRedis = self::getReadRedis();
        $writeRedis = self::getWriteRedis();

        if( $readRedis->sismember(self::DAILI_LIST, $playerId) )
            return;

        // 新增一个代理
        $writeRedis->sadd(self::DAILI_LIST, $playerId);
        // 玩家的上级
        $parentId = $readRedis->hget(self::DAILI_INFO, $playerId);
        Yii::info("DaliCalc openDaili: playerId".$playerId."; parentId = ".$parentId);
        if( $parentId == null || $parentId == 0 )
        {
            Yii::info("DaliCalc openDaili: parentId == null");
            Yii::$app->redis->zadd("inf_agent_relation", 999, $playerId);
            return;
        }

        self::modifyPlayerToDaili($playerId, $parentId);
    }

    /**
     * 绑定代理，扫码
     * @param $dailiId
     * @param $playerId
     */
    public static function bindDaili($dailiId, $playerId){
        $readRedis = self::getReadRedis();
        $writeRedis = self::getWriteRedis();
	    Yii::info("开始绑定代理");
        if( $readRedis->sismember(self::DAILI_LIST, $playerId) || !$readRedis->sismember(self::DAILI_LIST, $dailiId) ){
	        Yii::info("绑定代理条件不符合,".$playerId.'----'.$dailiId);
            return;
	    }
	    Yii::info("可以绑定代理，开始写入redis");
	    $redis = Yii::$app->redis;
        $redis->zadd("inf_agent_relation", $dailiId, $playerId);
	    Yii::info("写入redis完成");
        $writeRedis->hset(self::DAILI_INFO, $playerId, $dailiId);
        self::increasePlayer($playerId, $dailiId);
    }

    /**
     * 获得直接代理
     * @param $dailiId
     * @return array
     */
    public static function getDailiInfo($dailiId, $now='')
    {
        if(!$now){
            $now = date("Ymd", time());
        }else{
            $now = date('Ymd',strtotime($now));
        }

        $readRedis = self::getReadRedis();

        $nowUnderPlayer = $readRedis->scard($now.self::NOW_UNDER_PLAYER.$dailiId);
        $nowUnderDaili = $readRedis->scard($now.self::NOW_UNDER_DAILI.$dailiId);
        $allUnderPlayer = $readRedis->scard(self::ALL_UNDER_PLAYER.$dailiId);
        $allUnderDaili = $readRedis->scard(self::ALL_UNDER_DAILI.$dailiId);
        $allDirectPlayer = $readRedis->scard(self::ALL_DIRECT_PLAYER.$dailiId);
        $allDirectDaili = $readRedis->scard(self::ALL_DIRECT_DAILI.$dailiId);
        $newDirectPlayer = $readRedis->scard($now.self::NOW_DIRECT_PLAYER.$dailiId);
        $newDirectDaili = $readRedis->scard($now.self::NOW_DIRECT_DAILI.$dailiId);

        return array("nowUnderPlayer" => $nowUnderPlayer, "nowUnderDaili" => $nowUnderDaili, "allUnderPlayer" => $allUnderPlayer,
            "allUnderDaili" => $allUnderDaili, "allDirectPlayer" => $allDirectPlayer, "allDirectDaili" => $allDirectDaili,"newDirectPlayer"=>$newDirectPlayer,"newDirectDaili"=>$newDirectDaili);
    }

    /**
     * 获取代理列表
     *
     * @param $dailiId
     * @param string $now
     * @return array
     */
    public static function getAgentList($AgentId, $fields="*", $now='')
    {
        if(!$now){
            $now = date("Ymd", time());
        }else{
            $now = date('Ymd',strtotime($now));
        }
        $readRedis = self::getReadRedis();

        $nowUnderPlayer = $readRedis->smembers($now.self::NOW_UNDER_PLAYER.$AgentId);
        $nowUnderDaili = $readRedis->smembers($now.self::NOW_UNDER_DAILI.$AgentId);
        $allUnderPlayer = $readRedis->smembers(self::ALL_UNDER_PLAYER.$AgentId);
        $allUnderDaili = $readRedis->smembers(self::ALL_UNDER_DAILI.$AgentId);
        $allDirectPlayer = $readRedis->smembers(self::ALL_DIRECT_PLAYER.$AgentId);
        $allDirectDaili = $readRedis->smembers(self::ALL_DIRECT_DAILI.$AgentId);
        $newDirectPlayer = $readRedis->smembers($now.self::NOW_DIRECT_PLAYER.$AgentId);
        $newDirectDaili = $readRedis->smembers($now.self::NOW_DIRECT_DAILI.$AgentId);

        $data =  array("nowUnderPlayer" => $nowUnderPlayer, "nowUnderDaili" => $nowUnderDaili, "allUnderPlayer" => $allUnderPlayer,
            "allUnderDaili" => $allUnderDaili, "allDirectPlayer" => $allDirectPlayer, "allDirectDaili" => $allDirectDaili,"newDirectPlayer"=>$newDirectPlayer,"newDirectDaili"=>$newDirectDaili);

        if($fields == '*'){
            return $data;
        }else{
            if(isset($data[$fields])){
                return $data[$fields];
            }
        }

        return array();
    }

    /**
     * 重新计算
     */
    public static function renewCalc(){
        $redis = self::getWriteRedis();
        $redis->flushdb();
        $db = Yii::$app->db;

        $allDailis = $db->createCommand("select player_id from oss.t_daili_player");
        foreach ($allDailis as $dailiId){
            $redis->sadd(self::DAILI_LIST, $dailiId);
        }

        $bindrelations = $db->createCommand("select PLAYER_INDEX, MEMBER_INDEX, bind_time from oss.t_player_member");
        foreach ($bindrelations as $relation){
            $redis->hset(self::DAILI_INFO, $relation[1], $relation[0]);
        }

        // 所有的玩家加载到内存
        $allPlayers = $db->createCommand("select u_id from login_db.t_lobby_player")->queryColumn();
        $players = [];
        $dailis = [];
        foreach ($allPlayers as $playerId){
            if( $redis->sismember(self::DAILI_LIST, $playerId) ){
                // 是代理
                if( 0== count( $db->createCommand("select * from oss.t_player_member where player_index = ".$playerId) )){
                    self::openDaili($playerId);
                }
            }else{
                // 不是代理
                $parentId = $redis->hget(self::DAILI_INFO, $playerId);
                if( $parentId == null || $parentId == 0 )
                    continue;

                self::increasePlayer($playerId, $parentId);
            }
        }

//        $now = strtotime(date('Y-m-d',time()));;
//        foreach ($bindrelations as $relation){
//            if( $relation[2] > $now )
//                continue;
//
//            if( $redis->sismember(self::DAILI_LIST, $relation[1]) ){
//
//            }
//        }
    }
}

?>

