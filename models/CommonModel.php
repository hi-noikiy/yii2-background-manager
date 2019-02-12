<?php
/**
 * name 公共查询类
 * Created by PhpStorm.
 * User: moyu
 * Date: 2018/11/27
 * Time: 20:41
 */

namespace app\models;


use yii\db\ActiveRecord;
use yii\db\Query;

class CommonModel extends ActiveRecord
{
    /**
     * 根据条件获取用户信息
     *
     * @param $tableName
     * @param $con
     * @param string $fields
     * @param int $type 查询类型 1查询所有 2查询一条 3标量查询 4分页查询 5查询数量
     * @return bool|mixed
     */
    protected function commonSelect($tableName,$con,$fields='*',$type=1,$limit=10,$page=1,$group='',$order='')
    {
        $data='';
        switch ($type){
            case 1:
                $data = (new Query())->select($fields)->from($tableName)->where($con)->all();
                break;
            case 2:
                $data = (new Query())->select($fields)->from($tableName)->where($con)->one();
                break;
            case 3:
                $data = (new Query())->select($fields)->from($tableName)->where($con)->scalar();
                break;
            case 4:
                $data = (new Query())->select($fields)->from($tableName)->where($con)->limit($limit)->offset(($page - 1) * $limit)->all();
                break;
            case 5:
                $data = (new Query())->select($fields)->from($tableName)->where($con)->count();
                break;
            case 6:
                if($group){
                    $data = (new Query())->select($fields)->from($tableName)->where($con)->groupBy($group)->limit($limit)->offset(($page - 1) * $limit)->all();
                }
                break;
            case 7:
                if($order){
                    $data = (new Query())->select($fields)->from($tableName)->where($con)->orderBy($order)->all();
                }
                break;
            default:
                break;
        }

        return $data;
    }
}