<?php
/**
 * User: SeaReef
 * Date: 2018/7/12 21:25
 *
 * 代理控制器
 */
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

class Exchange extends CommonModel
{
    public static function tableName()
    {
        return 't_exchange';
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
    public function setBind($type,$playerId,$code,$name,$accountName){
        if($type && $playerId && $code && $name){
            $data['type']           = $type;
            $data['player_id']      = $playerId;
            $data['code']           = $code;
            $data['name']           = $name;
            $data['account_name']   = $accountName;
            $data['create_time']    = date('Y-m-d H:i:s');
        }else{
            return false;
        }

        if($this->getOne($playerId,$type)){
            if($this->updateInfo($playerId,$type,$data)){
                return true;
            }
        }else{
            if($this->addBind($data)){
                return true;
            }
        }

        return false;
    }

    /**
     * 添加绑定
     *
     * @param $data
     * @return bool
     */
    public function addBind($data){
        if($data){
            $model = new Exchange();
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
     * 查询绑定信息
     *
     */
    public function getData($con,$fields="*",$type=1){
        return $this->commonSelect(self::tableName(),$con,$fields,$type);
    }

    /**
     * 获取指定信息
     *
     * @param $playerId
     * @param $type
     * @return bool
     */
    public function getOne($playerId,$type){
        $isHave = false;
        $info = (new Query())
            ->select("*")
            ->from(self::tableName())
            ->where(['player_id'=>$playerId,'type'=>$type])
            ->one();

        if($info){
            $isHave = $info;
        }

        return $isHave;
    }

    /**
     * 更新绑定信息
     *
     * @param $playerId
     * @param $type
     * @param $data
     * @return bool
     */
    public function updateInfo($playerId,$type,$data){
        $model = self::findOne(['player_id'=>$playerId,'type'=>$type]);
        foreach ($data as $k => $v) {
            $model -> $k = $v;
        }
        $res = $model -> save();

        return $res;
    }

}