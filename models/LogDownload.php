<?php
/**
 * User: SeaReef
 * Date: 2018/12/5 15:24
 */

namespace app\models;

class LogDownload extends CommonModel
{
    public static function tableName(){
        return "log_download";
    }

    public function updateRecord($data, $id = '')
    {
        if($id){
            $model = self::findOne(['id'=>$id]);
        }else{
            $model = new LogDownload();
        }
        foreach ($data as $k=>$v){
            $model->$k = $v;
        }

        $model->save();
    }

    public function getData($con,$fields="*",$type){
        return $this->commonSelect(self::tableName(),$con,$fields,$type);
    }
}