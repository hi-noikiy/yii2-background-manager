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

class ConfRebateRatio extends CommonModel

{
    public static function tableName()
    {
        return 'conf_rebate_ratio';
    }

    /**
     * 根据条件获取数据
     *
     * @param $con
     * @param $fields
     * @param $type
     * @return bool|mixed
     */
    public function getData($con,$fields,$type){
        return $this->commonSelect(self::tableName(),$con,$fields,$type);
    }

    /**
     * 获取返利比例对应的代理等级
     *
     */
    public function getLevel($rebate){
        $level = (new Query())
            ->select("level")
            ->from(self::tableName())
            ->where(['ratio'=>$rebate])
            ->scalar();

        return $level;
    }

    /**
     * 更新档位
     *
     * @param $playerId
     * @param $type
     * @param $data
     * @return bool
     */
    public function updateInfo($level,$data){
        $model = self::findOne(['level'=>$level]);
        foreach ($data as $k => $v) {
            $model -> $k = $v;
        }
        $res = $model -> save();

        return $res;
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

    /**
     * 添加档位
     *
     * @param $data
     * @return bool
     */
    public function addLevel($data){
        if($data){
            $model = new ConfRebateRatio();
            foreach ($data as $k => $v) {
                $model -> $k = $v;
            }
            if ($model->save()) {
                return true;
            }
        }

        return false;
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
    public function setLevel($level,$data){
        $data['update_time']    = date('Y-m-d H:i:s');
        $data['create_time']    = date('Y-m-d H:i:s');
        if($this->getOne($level)){
            if($this->updateInfo($level,$data)){
                return true;
            }
        }else{
            if($this->addLevel($data)){
                return true;
            }
        }

        return false;
    }

}