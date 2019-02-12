<?php
/**
 * User: jw
 * Date: 2018/8/10 0010
 */
namespace app\models;

use function GuzzleHttp\Psr7\str;
use yii\db\Query;

class LogAgentActivity extends CommonModel
{
    public static function tableName()
    {
        return 'log_agent_activity';
    }

    /**
     * 根据条件获取数据
     *
     * @param $con
     * @param string $fields
     * @param int $type $type 查询类型 1查询所有 2查询一条 3标量查询
     * @return bool|mixed
     */
    public function getDataByCon($con, $fields = "*", $type = 1,$limit="",$page=0,$group='',$order='')
    {
        return $this->commonSelect(self::tableName(), $con, $fields, $type);
    }

    /**
     * 设置绑定
     *
     * @param $type
     * @param $playerId
     * @param $code
     * @param $name
     * @return bool
     */
    public function setLogAgentActivity($data){
        $where = ['player_id'=>$data['player_id'],'activity_id'=>$data['activity_id'],'operate_date'=>date("Y-m-d")];
        $model = self::findOne($where);

        if($model){
            $modelArr = self::findOne($where)->toArray();
            $data['last_operate_time']   = date('Y-m-d H:i:s');
            $data['operate_count'] = $modelArr['operate_count']+1;

            if($this->updateLogAgentActivity($model,$data)){
                return true;
            }
        }else{
            $data['operate_time'] = date('Y-m-d H:i:s');
            $data['operate_date'] = date('Y-m-d');
            $data['last_operate_time'] = date('Y-m-d H:i:s');
            if($this->addLogAgentActivity($data)){
                return true;
            }
        }

        return false;
    }

    /**
     * 获取指定信息
     *
     * @param $playerId
     * @param $type
     * @return bool
     */
    public function getOne($level){
        $isHave = false;
        $info = (new Query())
            ->select("*")
            ->from(self::tableName())
            ->where(['level'=>$level])
            ->one();

        if($info){
            $isHave = $info;
        }

        return $isHave;
    }

    public function addLogAgentActivity($data){
        $model = new LogAgentActivity();
        foreach ($data as $k => $v) {
            $model -> $k = $v;
        }
        if ($model->save()) {
            return true;
        }

        return false;
    }

    public function updateLogAgentActivity($model,$data){
        foreach ($data as $k => $v) {
            $model -> $k = $v;
        }
        $res = $model -> save();
        return $res;
    }
}