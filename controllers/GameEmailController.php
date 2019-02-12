<?php
/**
 * User: jw
 * Date: 2018/7/26 0026
 */
namespace app\controllers;

use app\common\helpers\Upload;
use app\models\GameEmailLog;
use Yii;
use yii\db\Query;
use app\models\GameEmail;
use app\models\UploadForm;
use app\common\helpers\Sms;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\web\UploadedFile;

class GameEmailController extends CommonBaseController
{
    public $enableCsrfValidation = false;

    private $code_time = 60;//短信验证码过期时间

    /**
     * 公告邮件页面
     */
    public function actionEmailIndex()
    {
        return $this->render('email_index');
    }

    /**
     * 公告邮件创建页面
     */
    public function actionCreateEmailIndex()
    {
        $request = Yii::$app->request->get();
        return $this->render('create_mail',$request?$request:[]);
    }

    /**
     * 历史邮件
     */
    public function actionEmailLogIndex()
    {
        return $this->render('email_log');
    }


    public function actionCreate()
    {
        $request = Yii::$app->request->post();
        //判断是否是修改
        if (isset($request['id']) && $request['id']) {
            $model = GameEmail::findOne($request['id']);
            $old_content = $model->attributes;
            $model->updated_time = date('Y-m-d H:i:s', time());
        } else {
            $model = new GameEmail();
        }
        if (isset($request['receive_player'])) {
            $request['receive_player'] = trim($request['receive_player']);
        }

        //创建时的email_code
        if ($model->load($request,'')) {
            if (!(isset($request['id']) && $request['id'])) {
                $query = new Query();
                $count = $query
                    ->select('id')
                    ->from($model::tableName())
                    ->where('id > 0 and unix_timestamp(created_time) >='.strtotime('today').' and unix_timestamp(created_time)<'.strtotime('tomorrow'))
                    ->count();
                if ($count < 99) {
                    $model->email_code = date('Ymd',strtotime('today')).str_pad($count++,2,'0',STR_PAD_LEFT);
                } else {
                    $this->writeResult(self::CODE_GAME_EMAIL_MORE_THAN_99);
                }
            }
        }

        if (isset($model->attachment) && $model->attachment) {
            //非标准json转换
            if (!is_array($model->attachment)) {
                $model->attachment = json_decode($model->attachment,true);
            }
            foreach ($model->attachment as $value) {
                if ($value['num'] < 1 || $value['num'] > 500000) {
                    $this->writeResult(self::CODE_ATTACHMENT_NUM);
                }
            }
            $model->attachment = json_encode($model->attachment);
        }

        if ($model->save()) {
            if (isset($request['id']) && $request['id']) {
                //添加修改记录
                $log_model = new GameEmailLog();
                $log_model->LogInfo($log_model->email_update,$request['id'],$old_content,$model->attributes);
                $this->writeResult(0);
            }
            $this->writeResult(0);
        } else {
            return $this->writeResult(self::CODE_ERROR);
        }

        return $this->writeResult(self::CODE_ERROR,'参数错误!!');
    }

    /**
     * 更新邮件
     *
     */
    public function actionUpdate()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $model = GameEmail::findOne($request['id']);
        } else {
            $this->writeResult(self::CODE_PARAMS_ERROR);
        }
        $old_content = $model->attributes;
        if ($model->load($request,'')) {
            $model->updated_time = date('Y-m-d H:i:s',time());
            //verify attachment
            if (isset($model->attachment) && $model->attachment) {
                //非标准json转换
                $model->attachment = str_replace('\'','"',$model->attachment);
                $model->attachment = json_decode($model->attachment,true);
                foreach ($model->attachment as $value) {
                    if ($value['num'] < 1 || $value['num'] > 500000) {
                        $this->writeResult(self::CODE_ATTACHMENT_NUM);
                    }
                }
                $model->attachment = json_encode($model->attachment);
            }
            if ($model->save()) {
                //添加修改记录
                $log_model = new GameEmailLog();
                $log_model->LogInfo($log_model->email_update,$request['id'],$old_content,$model->attributes);
                $this->writeResult();

            } else {
                return $this->writeResult(self::CODE_ERROR);
            }
        } else {
            $this->writeResult(self::CODE_PARAMS_ERROR);
        }
        ;

    }

    /**
     * 邮件详情
     *
     */
    public function actionDetail()
    {
        $params = Yii::$app->request->get();
        if (isset($params['id']) && $params['id']) {
            $query = new Query();
            $model = new GameEmail();
            $rows = $query
                ->select('*')
                ->from($model::tableName())
                ->where('id = '.$params['id'])
                ->one();
            $this->writeJson($rows?$rows:[]);
        } else {
            $this->writeResult(self::CODE_ERROR);
        }
    }

    /**
     * 删除邮件
     *
     */
    public function actionDelete()
    {
        $request = Yii::$app->request->post();
        if ($request['id']) {
            $data = GameEmail::findOne($request['id']);
            if ($data) {
                $result = Yii::$app->db->createCommand()->update(GameEmail::tableName(),['status'=>0,'updated_time'=>date('Y-m-d H:i:s',time())],'id = '.$request['id'])->execute();
                if ($result) {
                    $log_model = new GameEmailLog();
                    //添加操作记录
                    $log_model->logInfo($log_model->email_delete,$request['id']);
                    return $this->writeResult();
                } else {
                    return $this->writeResult(self::CODE_ERROR);
                }
            } else {
                return $this->writeResult(self::CODE_GAME_EMAIL_NOT_FOUND);
            }
        } else {
            return $this->writeResult(self::CODE_PARAMS_ERROR);
        }


    }

    public function actionIndex()
    {
        //var_dump(1111);exit;
        $query = new Query();
        $request = Yii::$app->request->get();
        $model = new GameEmail();

        $count = $query
            ->select('id')
            ->from($model::tableName())
            ->where('status = 1')
            ->where(' status = 1 and send_status != 3')
            ->orderBy('id')
            ->count();
        $offset = ($request['page']-1)*$request['limit'];

        $rows = $query
            ->select('*')
            ->from($model::tableName())
            ->where(' status = 1 and send_status != 3')
            ->orderBy('id ASC')
            ->limit($request['limit'])
            ->offset($offset)
            ->all();
        return $this->writeLayui(0, $msg = 'success', $count, $rows?$rows:[]);
    }

    /**
     * 历史信息库
     * 已发送和已删除的记录
     * @return string
     */
    public function actionLogInfo()
    {
        $query = new Query();
        $request = Yii::$app->request->get();
        $model = new GameEmail();

        $count = $query
            ->select('id')
            ->from($model::tableName())
            ->where('status = 0 or send_status = 3')
            ->count();
        $offset = ($request['page']-1)*$request['limit'];

        $rows = $query
            ->select('*')
            ->from($model::tableName())
            ->where('status = 0 or send_status = 3')
            ->orderBy('id DESC')
            ->limit($request['limit'])
            ->offset($offset)
            ->all();
        if ($rows) {
            foreach ($rows as $key => $row) {
                if ($row['is_pop'] == 1 && $row['send_status'] == 3 && time() < strtotime($row['pop_time'])) {
                    $rows[$key]['play_btn'] = 1;
                }
            }
        }
        return $this->writeLayui(0, $msg = 'success', $count, $rows?$rows:[]);
    }

    /**
     * 邮件操作记录详情
     * @return array
     */
    public function actionLogDetail()
    {
        $params = Yii::$app->request->get();
        if (isset($params['id'])) {
            $query = new Query();
            $model = new GameEmailLog();
            $count = $query
                ->select('id')
                ->from($model::tableName())
                ->where('email_id = '.$params['id'])
                ->count();
            $rows = $query
                ->select('*')
                ->from($model::tableName())
                ->where('email_id = '.$params['id'])
                ->all();
            return $this->writeLayui(0,'',$count,$rows?$rows:[]);
        } else {
            return $this->writeLayui(0,'',0,[]);
        }
    }

    public function actionSmsCode()
    {
        $account = Yii::$app->user->getId();
        $code = Sms::randNumber();
        $content = '验证码：'.$code;
        Sms::send('15910284120',$content);
        Yii::$app->redis->set('game_email:verification:'.$account,$code);
        Yii::$app->redis->expire('game_email:verification:'.$account,Yii::$app->params['game_email_sms_timeout']);
    }

    public function actionVerifyCode()
    {
        $account = Yii::$app->user->getId();
        $request = Yii::$app->request->post();
        $verify_code = Yii::$app->redis->get('game_email:verification:'.$account);
        if (!$verify_code) {
            $this->writeResult(self::CODE_VERIFY_CODE_TIMEOUT);
        }
        if ($request['code'] == $verify_code) {
            $this->writeResult();
        } else {
            $this->writeResult(self::CODE_VERIFY_CODE_ERROR);
        }
    }

    /**
     * 导入对象ID的Excel文件
     * @return array|void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function actionExcelReceivePlayers()
    {
        $account = Yii::$app->user->getId();
        //文件上传处理
        //$request = isset($_FILES['file'])?$_FILES['file']:false;
        $request = UploadedFile::getInstancesByName('file');
        $request = $request[0];
        if ($request->error === 0) {
            if (!in_array($request->type,['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel'])) {
                $this->writeResult(self::CODE_EXCEL_TYPE_ERROR);
            }
            if ($request->size > 10*1024*10000) {
                $this->writeResult(self::CODE_SIZE_TOO_LARGE);
            }
            if (!is_dir(Yii::$app->basePath.'/runtime/uploads/')) {
                mkdir(Yii::$app->basePath.'/runtime/uploads/');
            }
            $dst = Yii::$app->basePath.'/runtime/uploads/'.time().'_'.$account.'.'.$request->getExtension();
            $result =  move_uploaded_file($request->tempName,$dst);
            if (!$result) {
                $this->writeResult(self::CODE_EXCEL_UPLOAD_FAIL);
            }

            //文件读取
            $reader = IOFactory::createReader(ucfirst($request->getExtension()));
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($dst);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();//总行数
            $players = [];
            for ($row = 2; $row <= $highestRow; $row++) {
                $value = trim($worksheet->getCellByColumnAndRow(1,$row)->getValue());
                if ($value) {
                    $players[] = $value;
                }
            }
            if ($players) {
                $this->writeJson([
                    'code' => self::CODE_OK,
                    'msg' => self::$CODE_MESSAGES[self::CODE_OK],
                    'data' => implode(',',$players)
                ]);
            } else {
                $this->writeResult(self::CODE_EXCEL_EMPTY);
            }

        } else {
            $this->writeResult(self::CODE_FILE_UPLOAD_ERROR);
        }
    }

    /**
     * 邮件弹框开始和暂停
     */
    public function actionPlayPause()
    {
        $request = Yii::$app->request->post();
        if (isset($request['id']) && $request['id']) {
            $model = new GameEmail();
            $result = $model->sendEmail($request['id'],$request['is_play']);
            if ($result) {
                $this->writeResult();exit;
            }
            $this->writeResult(self::CODE_ERROR);exit;
        }
        $this->writeResult(self::CODE_PARAMS_ERROR);exit;

    }
}