<?php
/**
 * User: jw
 * Date: 2018/8/10 0010
 */
namespace app\models;

class Channel extends CommonModel
{
    public static function tableName()
    {
        return 't_channel';
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
        return $this->commonSelect(self::tableName(), $con, $fields, $type,$limit,$page,$group,$order);
    }

    /**
     * 设置渠道
     *
     */
    public function setChannel($data,$id=''){
        $data['create_time']    = date('Y-m-d H:i:s');
        if($id){
            if($this->updateChannel($id,$data)){
                return true;
            }
        }else{
            if($this->addChannel($data)){
                return true;
            }
        }

        return false;
    }

    public function addChannel($data){
        $model = new Channel();
        foreach ($data as $k => $v) {
            $model -> $k = $v;
        }
        if ($model->save()) {
            return true;
        }

        return false;
    }

    public function updateChannel($id,$data){
        $model = self::findOne(['id' => $id]);
        foreach ($data as $k => $v) {
            $model -> $k = $v;
        }
        $res = $model -> save();
        return $res;
    }
}