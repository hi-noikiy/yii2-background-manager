<?php
/**
 * User: jw
 * Date: 2018/8/10 0010
 */
namespace app\models;

class AuthAssignment extends CommonModel
{
    public static function tableName()
    {
        return 'auth_assignment';
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
}