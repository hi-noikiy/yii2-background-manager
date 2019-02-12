<?php
/**
 * User: SeaReef
 * Date: 2018/11/30 14:24
 *
 * 返利日志表
 */
namespace app\models;

use Yii;
use yii\db\Query;

class LogRebate extends CommonModel

{
    public static function tableName()
    {
        return 'log_rebate';
    }


    /**
     * 直属返利
     */
    const REBATE_SUB = 1;

    /**
     * 伞下返利
     */
    const REBATE_UNDER = 2;

    /**
     * 添加返利日志
     *
     * @param $data
     * @return int
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public static function addLog($data)
    {
        $db = Yii::$app->db;
        return $db->createCommand()->insert(self::tableName(), [
            'parent_id' => $data['parent_id'],
            'player_id' => $data['player_id'],
            'consume' => $data['consume'],
            'ratio' => $data['ratio'],
            'rebate' => $data['rebate'],
            'type' => $data['type'],
            'rebate_week' => $data['rebate_week'],
            'create_time' => $data['create_time'],
            'is_agent' => $data['is_agent'],
        ])->execute();
    }

    /**
     * 根据条件获取数据
     *
     * @param $con
     * @param $fields
     * @param $type
     * @return bool|mixed
     */
    public function getData($con,$fields,$type,$limit=10,$page=1){
        return $this->commonSelect(self::tableName(),$con,$fields,$type,$limit,$page);
    }

    /**
     * 获取最后一条的数据指定字段的值
     *
     */
    public function getLastParamValue($fields='*'){
        $model = (new Query())
            ->select($fields)
            ->from(self::tableName())
            ->orderBy('create_time desc')
            ->one();

        return $model[$fields];
    }

    public function getCount($where){

        return (new Query())
            ->select("id")
            ->from(self::tableName())
            ->where($where)
            ->count();
    }

    /**
     * 分组获取数据
     *
     * @param $con
     * @param $fields
     * @param $groupFields
     */
    public function getDataByGroup($con,$fields,$groupFields){
        $data = (new Query())
            ->select($fields)
            ->from(self::tableName())
            ->where($con)
            ->groupBy($groupFields)
            ->all();

        return $data;
    }

    /**
     * 获取当前返利周为代理的第几周返利
     *
     * @param $playerId
     * @param $thisWeek
     * @return float|int
     */
    public function getWeeks($playerId,$thisWeek){
        $agentModel = new DailiPlayer();
        $createTime = $agentModel->getDataByCon(['player_id'=>$playerId],'create_time',2)['create_time'];
        if(strtotime($createTime) < strtotime($thisWeek)+86400*7){
            $diff = strtotime($thisWeek) - strtotime($createTime);
            if($diff < 0){
                return 1;
            }
            $weeks = floor($diff / (86400 * 7));
            return $weeks + 2;
        }

        return 0;
    }
}