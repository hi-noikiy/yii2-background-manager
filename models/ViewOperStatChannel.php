<?php
/**
 * User: jw
 * Date: 2018/8/10 0010
 */
namespace app\models;

class ViewOperStatChannel extends CommonModel
{
    public static function tableName()
    {
        return 'view_oper_stat_channel';
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
     * 设置绑定
     *
     * @param $type
     * @param $playerId
     * @param $code
     * @param $name
     * @return bool
     */
    public function setViewOperStatChannel($data,$id=''){
        $data['create_time']    = date('Y-m-d H:i:s');
        if($id){
            if($this->updateViewOperStatChannel($id,$data)){
                return true;
            }
        }else{
            if($this->addViewOperStatChannel($data)){
                return true;
            }
        }

        return false;
    }

    public function addViewOperStatChannel($data){
        $model = new ViewOperStatChannel();
        foreach ($data as $k => $v) {
            $model -> $k = $v;
        }
        if ($model->save()) {
            return true;
        }

        return false;
    }

    public function updateViewOperStatChannel($id,$data){
        $model = self::findOne(['id' => $id]);
        foreach ($data as $k => $v) {
            $model -> $k = $v;
        }
        $res = $model -> save();
        return $res;
    }
}