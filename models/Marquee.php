<?php
/**
 * User: jw
 * Date: 2018/8/3 0003
<<<<<<< Updated upstream
 */


namespace app\models;

use Yii;
use yii\base\Curl;
use yii\db\ActiveRecord;

class Marquee extends ActiveRecord
{
    public static function tableName()
    {
        return 't_marquee';
    }

    public function rules()
    {
        return [
            [['account','content','start_time','end_time'],'required'],
            ['content','trim'],
            ['content','string','max'=>50],
            //[['start_time','end_time'],'date','format'=>'yyyy-MM-dd HH:mm:ss'],
            ['interval_time','default','value'=>$this->getInterval(101)],
            [['created_time','updated_time'],'default','value'=>date('Y-m-d H:i:s',time())],
            [['account','is_notice','status','interval_time','is_play'],'integer'],
            ['status','default','value'=>1],
            [['is_notice','is_play','is_edit'],'default','value'=>0],
            [['type','cost','white_sign'],'safe']
        ];
    }

    /**
     * 获取跑马灯列表
     * type 什么类型的跑马灯
     * filter 跑马灯的时间筛选 all：全部before没有播放完得after已经播放完的
     */
    public function getPostList($start,$page_size,$type=101,$filter = 'all',$uid = null){
        $nowTime = time();
        if($uid){
            $add_sql = 'create_uid = '.$uid.' AND';
        }else{
            $add_sql = '';
        }

        switch ($filter) {
            case 'all':
                $sql = 'SELECT * FROM t_game_post WHERE '.$add_sql.' type = '.$type.' AND status < 3 ORDER BY create_time DESC LIMIT '.$start.','.$page_size;
                break;

            case 'before':
                $sql = 'SELECT * FROM t_game_post WHERE '.$add_sql.' type = '.$type.' AND status < 3 AND end_time > '.$nowTime.' ORDER BY create_time DESC LIMIT '.$start.','.$page_size;
                break;

            case 'after':
                $sql = 'SELECT * FROM t_game_post WHERE '.$add_sql.' type = '.$type.' AND status < 3 AND end_time < '.$nowTime.' ORDER BY create_time DESC LIMIT '.$start.','.$page_size;
                break;
        }
        //echo $sql;exit;
        $data = Yii::$app->db
            ->createCommand($sql)
            ->queryAll();
        return $data;
    }
    /**
     * 根据跑马灯id获取跑马灯信息
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getPostById($id){
        $sql = 'SELECT * FROM t_game_post WHERE id = '.$id;
        $data = Yii::$app->db
            ->createCommand($sql)
            ->queryOne();
        return $data;
    }

    public function getPostCount($type=101,$filter = 'all'){
        $nowTime = time();
        switch ($filter) {
            case 'all':
                $sql = 'SELECT count(*) as cou FROM t_game_post WHERE type = '.$type.' AND status < 3';
                break;

            case 'before':
                $sql = 'SELECT count(*) as cou FROM t_game_post WHERE type = '.$type.' AND status < 3 AND end_time > '.$nowTime;
                break;

            case 'after':
                $sql = 'SELECT count(*) as cou FROM t_game_post WHERE type = '.$type.' AND status < 3 AND end_time < '.$nowTime;
                break;
        }
        $data = Yii::$app->db
            ->createCommand($sql)
            ->queryOne();
        return $data['cou'];
    }

    /**
     * 插入用户的跑马灯
     */
    public function insertUserPost($data){
        $model = new Marquee();
        $model->account = $data['create_uid'];
        $model->created_time = date('Y-m-d H:i:s',time());
        $model->content = $data['content'];
        $model->start_time = 0;
        $model->end_time = 0;
        $model->type = 201;
        $model->is_notice = 0;
        $model->status = 1;
        $model->cost = $data['cost'];
        $model->updated_time = date('Y-m-d H:i:s',time());
        $model->white_sign = $data['white_sign'];
        $res = $model -> save();
        if($res){
            $id = $model -> attributes['id'];
        }else{
            $id = false;
        }
        return $id;
    }

    /**
     *更新用户状态值
     */
    public function updateGamePost($id,$data){
        $model = self::findOne(['id' => $id]);
        foreach ($data as $k => $v) {
            $model -> $k = $v;
        }
        $res = $model -> save();
        return $res;
    }

    public function getAdminUser(){
        $sql = 'SELECT id,username FROM tcc_zjh_daili.t_user';
        $data = Yii::$app->db
            ->createCommand($sql)
            ->queryAll();
        $res = array_column($data,'username','id');
        return $res;
    }
    /**
     * 记录跑马灯修改的方法
     * @param [array] $before [修改之前的数组]
     * @param [array] $after  [修改之后的数组]
     * $before中的key要和after中的key一一对应
     */
    public function addEditLog($uid,$pmid,$before,$after){
        /**
         * 定义一个数组保存数据库中的字段对应显示保存的文字，如果字段含义改变，请不要删除原有数组，注释掉，标注一下使用时间，因为存数据库是匹配之后的文字，不是字段了，以后可以根据时间查到是那个字段
         */
        //create_time = 2018-05-10;end_time = null;
        $keyArr = array(
            'content' => '内容',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
        );

        $editArr = array();
        foreach($before as $k => $v){
            if($before[$k] != $after[$k]){
                if($k == 'start_time' || $k == 'end_time'){
                    $editArr[$k] = array(
                        'before' => date('Y-m-d H:i:s',$before[$k]),
                        'after' => date('Y-m-d H:i:s',$after[$k])
                    );
                }else{
                    $editArr[$k] = array(
                        'before' => $before[$k],
                        'after' => $after[$k]
                    );
                }
            }
        }
        if(empty($editArr)){
            return false;
        }
        $content = '';
        foreach($editArr as $k => $v){
            if($k == 'notice_sign'){
                if($v['after'] == 1){
                    $content = '将<span style="color:blue">是否为公告形式</span>修改为将<span style="color:green">是</span>';
                }else if($v['after'] == 2){
                    $content = '将<span style="color:blue">是否为公告形式</span>修改为将<span style="color:green">否</span>';
                }
            }else if($k == 'status'){
                if($v['after'] == 1){
                    $content = '将<span style="color:blue">跑马灯状态</span>修改为将<span style="color:green">正常播放</span>';
                }else if($v['after'] == 2){
                    $content = '将<span style="color:blue">跑马灯状态</span>修改为将<span style="color:green">暂停</span>';
                }else if($v['after'] == 3){
                    $content = '将<span style="color:blue">跑马灯状态</span>修改为将<span style="color:green">禁止</span>';
                }else if($v['after'] == 4){
                    $content = '将<span style="color:blue">跑马灯状态</span>修改为将<span style="color:green">删除</span>';
                }

            }else{
                $content .= '将<span style="color:blue">'.$keyArr[$k].'</span>由<span style="color:red">'.$v['before'].'</span>修改为<span style="color:green">'.$v['before'].'</span>;';
            }
        }

        $data = array(
            'post_id' => $pmid,
            'edit_uid' => $uid,
            'content' => $content,
            'update_time' => time(),
        );
        //var_dump($data);exit;
        Yii::$app->db
            ->createCommand()
            ->insert('game_post_log',$data)
            ->execute();

    }
    /**
     * 根据跑马灯的id获取该条跑马灯的操作记录
     * @param  [type] $id 跑马灯id
     */
    public function getPostLog($id){
        $sql = 'SELECT * FROM game_post_log WHERE post_id = '.$id;
        $data = Yii::$app->db
            ->createCommand($sql)
            ->queryAll();
        return $data;
    }
    /**
     * 获取黑白名单列表$sign = 1白名单$sign = 2黑名单
     */
    public function getUserList($sign){
        /*if($uid){
            $sql = 'SELECT * FROM post_user_list as list LEFT JOIN tcc_zjh_daili.t_player as player ON list.player_index = player.PLAYER_INDEX WHERE list.sign = '.$sign.' AND list.player_index = '.$uid.' ORDER BY create_time DESC LIMIT '.$start.','.$page_size;
        }else{
            $sql = 'SELECT player.*,list.create_time FROM post_user_list as list LEFT JOIN tcc_zjh_daili.t_player as player ON list.player_index = player.PLAYER_INDEX WHERE list.sign = '.$sign.' ORDER BY create_time DESC LIMIT '.$start.','.$page_size;
        }*/
        $sql = 'select * from post_user_list where sign='.$sign;
        $data = Yii::$app->db
            ->createCommand($sql)
            ->queryAll();
        return $data;
    }

    /**
     * 获取黑白名单数量$sign = 1白名单$sign = 2黑名单
     */
    public function getUserCount($sign,$uid){
        if($uid){
            $sql = 'SELECT count(*) as cou FROM post_user_list as list LEFT JOIN tcc_zjh_daili.t_player as player ON list.player_index = player.PLAYER_INDEX WHERE list.sign = '.$sign.' AND list.player_index = '.$uid;
        }else{
            $sql = 'SELECT count(*) as cou FROM post_user_list as list LEFT JOIN tcc_zjh_daili.t_player as player ON list.player_index = player.PLAYER_INDEX WHERE list.sign = '.$sign;
        }

        $data = Yii::$app->db
            ->createCommand($sql)
            ->queryOne();
        return $data['cou'];
    }

    /**
     * 添加黑白名单
     * $uid = 用户id
     * $sign = 1/2  白名单/黑名单
     */
    public function addList($uid,$sign){
        $data = array(
            'player_index' => $uid,
            'sign' => $sign,
            'create_time' => time(),
        );
        //var_dump($data);exit;
        $res = Yii::$app->db
            ->createCommand()
            ->insert('post_user_list',$data)
            ->execute();
        return $res;
    }
    /**
     * 判断是否存在名单中
     * 不存在返回真，存在返回假
     */
    public function checkList($uid){
        $sql = 'SELECT * FROM post_user_list WHERE player_index = '.$uid;
        //echo $sql;exit;
        $data = Yii::$app->db
            ->createCommand($sql)
            ->queryAll();
        if(empty($data)){
            $res = true;
        }else{
            $res = false;
        }
        return $res;
    }
    /**
     * 删除黑白名单中的用户信息
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public function delUserList($uid){
        $sql = 'DELETE FROM post_user_list WHERE player_index = '.$uid.' LIMIT 1';
        $res = Yii::$app->db
            ->createCommand($sql)
            ->execute();
        return $res;
    }

    /**
     * 获取用户发跑马灯该扣多少钱
     * @return [type] [description]
     */
    public function getPmdDeduct(){
        $sql = 'SELECT * FROM auto_config WHERE name = "pmd_deduct"';
        //echo $sql;exit;
        $data = Yii::$app->db
            ->createCommand($sql)
            ->queryOne();
        return $data['value'];
    }
    /**
     * 获取跑马灯展示时间
     * @return [type] [description]
     */
    public function getPmdShowtime(){
        $sql = 'SELECT * FROM auto_config WHERE name = "pmd_show_time"';
        //echo $sql;exit;
        $data = Yii::$app->db
            ->createCommand($sql)
            ->queryOne();
        return $data['value'];
    }
    /**
     * 修改用户发跑马灯该扣多少钱
     */
    public function UpdateDeduct($num){
        $sql = 'UPDATE auto_config SET value = '.$num.' WHERE name = "pmd_deduct"';
        $res = Yii::$app->db
            ->createCommand($sql)
            ->execute();
        return $res;
    }

    /**
     * 修改用户发跑马灯展示时间
     */
    public function UpdateShowTime($num){
        $sql = 'UPDATE auto_config SET value = '.$num.' WHERE name = "pmd_show_time"';
        $res = Yii::$app->db
            ->createCommand($sql)
            ->execute();
        return $res;
    }

    /**
     * 获取用户是黑名单还是白名单
     * @return [type] [description]
     */
    public function getUserType($player_index){
        $sql = 'SELECT * FROM post_user_list WHERE player_index = '.$player_index;
        $data = Yii::$app->db
            ->createCommand($sql)
            ->queryOne();
        if(empty($data)){
            return false;
        }else{
            return $data['sign'];
        }
    }
    /**
     * 获取时间间隔
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getInterval($id){
        $sql = 'SELECT * FROM t_post_type WHERE type_id = '.$id;
        $data = Yii::$app->db
            ->createCommand($sql)
            ->queryOne();
        if(empty($data)){
            return 0;
        }
        return $data['inter'];
    }
    /**
     * 修改时间间隔
     * @param [type] $id [description]
     */
    public function UpdateInterval($type,$interval){
        $sql = 'UPDATE t_post_type SET inter = '.$interval.' WHERE type_id = '.$type;
        $res = Yii::$app->db
            ->createCommand($sql)
            ->execute();
        return $res;
    }
    /**
     *根据传进来的参数查出跑马灯信息，给游戏服发过去（跑马灯信息，给游戏服发过去）
     * @param [type] $data [description]
     *
     * return 0:该跑马灯不存在
     */
    public function PostServer($id,$sign = null){
        $pm = Marquee::find()->where(['id' => $id])->one();
        if ($pm) {
            $url = Yii::$app->params['pm_url'];
            //根据type查出interval
            $interval = $this -> getInterval($pm['type']) ?: 0;

            //修改过将status置成2(和下面的判断顺序不可变。以免有脑残，修改后删除)
            if ($pm['is_edit'] != 0 && $pm['status'] != 0){
                $pm['status'] = 2;
            }

            //暂停将status置成3
            if($pm['play_status'] != 1 && $pm['status'] != 0){
                $pm['status'] = 3;
            }

            //展示时间
            $show_time = $this -> getPmdShowtime();
            $data = array(
                "id" => $id,
                "content" => $pm['content'],
                "createTime" => strtotime($pm['created_time']),
                "createrId" => $pm['account'],
                "deduct" => $pm['cost'],//玩家发送扣除的费用
                "endTime" => strtotime($pm['end_time']),
                "interval" => $interval,//播放时间间隔
                "noticeSign" => $pm['is_notice'] == 1?1:2,//是否公告，可以用作type下级的再次分类，目前是type=1时，1系统公告2非公告
                "startTime" =>  strtotime($pm['start_time']),
                "status" => $pm['status'],//跑马灯状态，1：播放 0暂停,删除 2:修改
                "type" => $pm['type'],//跑马灯类型 101:平台(GM) ； 201:用户；301:系统
                "updateTime" => $pm['updated_time'],//最近一次更新时间
                "showTime" => $show_time,//展示时间
                "thisTime" => time()//当前时间
            );

            $present_data = 'msg=' . json_encode($data, JSON_UNESCAPED_UNICODE);
            Yii::info("数据：".$present_data);

            $curl = new Curl();
            $info = $curl->CURL_METHOD($url,$present_data);
            Yii::info("返回数据：".$info);

            //判断是否成功，成功就算了，不成功则记录日志
            return $info;
        } else {
            echo "--6";
            return 0;
        }

    }
    /**
     * 获取当前还能播放的跑马灯，（正在播放和还未播放）
     * 经过修改：改成，只拿正在播放的。因为做法变流程了
     * @return [type] [description]
     */
    public function getNowList(){
        $nowTime = time();
        $sql = 'SELECT * FROM t_marquee WHERE type = 101 AND status = 1 AND unix_timestamp(start_time) < '.$nowTime.' AND unix_timestamp(end_time) > '.$nowTime.' AND play_status = 1';
        //echo $sql;exit;
        $data = Yii::$app->db
            ->createCommand($sql)
            ->queryAll();
        return $data;
    }
    /**
     * 获取用户最后一次发跑马灯的时间
     * @param  [type] $player_index [description]
     * @return [type]               [description]
     */
    public function getUserLastTime($player_index){
        $sql = 'SELECT * FROM t_marquee WHERE type = 201 AND account = '.$player_index.' ORDER BY created_time DESC LIMIT 1';
        //echo $sql;exit;
        $data = Yii::$app->db
            ->createCommand($sql)
            ->queryOne();
        if(empty($data)){
            return 0;
        }
        return $data['created_time'];
    }

    /**
     * 跑马灯发送给游戏服务器之后，更改跑马灯发送状态
     * @param $ids
     * @throws \yii\db\Exception
     */
    public function setMarqueeIsplay($ids)
    {
        Yii::$app->db->createCommand()->update(Marquee::tableName(),['is_play' => 1],' id in ('.implode(',',$ids).')')->execute();
    }

}
