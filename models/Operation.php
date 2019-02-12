<?php
/**
 * User: SeaReef
 * Date: 2018/7/12 10:41
 *
 * 操作记录类
 */
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Operation extends ActiveRecord
{
    public static function tableName()
    {
        return 'log_operation';
    }

    public function log($data)
    {
        $username = User::find()->select('username')->where(['id' => $data['id']])->asArray()->one();

        $m = new self;
        $m->username = $username['username'];
        $m->op_type = $data['op_type'];
        $m->op_time = date('Y-m-d H:i:s', time());
        $m->op_content = $data['op_content'];
        $m->save();
    }
}