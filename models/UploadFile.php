<?php
/**
 * User: jw
 * Date: 2018/8/14 0014
 */

namespace app\models;

use yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadFile extends Model
{
    //上传文件通用方法
    public static function UploadToWeb($path = 'lunbo', $extension = ['jpg', 'png', 'jpeg', 'gif'], $size = 10240000)
    {
        $file = UploadedFile::getInstanceByName('file');
        if ($file->error != 0) {
            return ['code' => 1, 'msg' => 'error'];
        }
        if (!in_array($file->getExtension(), $extension)) {
            return ['code' => 2, 'msg' => 'extension error'];
        }
        if ($file->size > $size) {
            return ['code' => 3, 'msg' => 'size error'];
        }
        if (!is_dir(Yii::$app->basePath . '/web/upload/' . $path . '/')) {
            mkdir(Yii::$app->basePath . '/web/upload/' . $path . '/',0777,true);
        }
        $time = time();
        $dst = '/web/upload/' . $path . '/' . $time . '_' . Yii::$app->user->getId() . '.' . $file->getExtension();
        $dst_ = '/upload/' . $path . '/' . $time . '_' . Yii::$app->user->getId() . '.' . $file->getExtension();
        $result = move_uploaded_file($file->tempName, Yii::$app->basePath . $dst);
        if ($result) {
            $protocol = 'https';
            return ['code' => 0, 'msg' => 'ok', 'url' => $protocol . '://' . $_SERVER['HTTP_HOST'] . $dst_];
        } else {
            return ['code' => 4, 'msg' => 'size error'];
        }
    }


}