<?php
/**
 * User: SeaReef
 * Date: 2018/10/24 19:50
 *
 * 人人代理
 */

namespace app\controllers\api;

use http\Exception\RuntimeException;
use Yii;
use yii\db\Exception;
use yii\web\Controller;
use app\common\Tool;
use yii\base\Curl;
use app\models\ExchangeRecord;
use app\common\Code;

class BaseController extends Controller
{

    /** 通用失败返回信息 */
    protected $message = 'Bad request!';

    /** 通用成功返回信息 */
    protected $success = 'success';

    protected $redirectUrl = '';

    protected $shareUrl = "";

    /** 微信网页授权接口地址 */
    public $accessUrl;

    /**
     * 成功返回处理
     *
     * @param array $data
     */
    public function returnOK($data = [])
    {
        try {
            if (is_array($data)) {
                $return['code'] = Code::OK;
                $return['data'] = $data;
                $return['message'] = $this->success;

                $this->writeJson($return);
            } else {
                throw new Exception('The argument must be an array！');
            }
        } catch (Exception $e) {
            die($e);
        }
    }

    /**
     * 返回输出
     *
     * @param $data
     */
    protected function writeJson($data)
    {
        try {
            if (is_array($data)) {
                $response = Yii::$app->response;
                $response->format = \yii\web\Response::FORMAT_JSON;
                $response->data = $data;
                $response->send();

                exit;
            } else {
                throw new Exception('The argument must be an array！');
            }
        } catch (Exception $e) {
            die($e);
        }
    }

    /**
     * 通用失败返回
     *
     * @param int $code
     * @param array $data
     * @param string $message
     */
    public function returnError($code, $message, $data = [])
    {
        $this->writeJson(
            array(
                'code' => $code,
                'data' => $data,
                'message' => $message
            )
        );
    }

    /**
     * 验证银行卡号
     *
     * @param $card_number
     * @return string
     * （转载: 源代码地址：https://blog.csdn.net/gongqinglin/article/details/78065163?locationNum=8&fps=1）
     */
    public function check_bankCard($card_number)
    {
        $arr_no = str_split($card_number);
        $last_n = $arr_no[count($arr_no) - 1];
        krsort($arr_no);
        $i = 1;
        $total = 0;
        foreach ($arr_no as $n) {
            if ($i % 2 == 0) {
                $ix = $n * 2;
                if ($ix >= 10) {
                    $nx = 1 + ($ix % 10);
                    $total += $nx;
                } else {
                    $total += $ix;
                }
            } else {
                $total += $n;
            }
            $i++;
        }
        $total -= $last_n;
        $total *= 9;
        if ($last_n == ($total % 10)) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * 验证请求参数
     * @param int $type 0不做限制 1必须是post 2必须是get
     */
    public function checkRequestWay($type = 0)
    {
        $return = array();
        switch ($type) {
            case 0:
                return $_REQUEST;
                break;
            case 1:
                if (Yii::$app->request->isPost)
                    if (Yii::$app->request->post())
                        return Yii::$app->request->post();
                break;
            case 2:
                if (Yii::$app->request->isGet)
                    if (Yii::$app->request->get())
                        return Yii::$app->request->get();
                break;
            default:
                return $return;
                break;
        }

        return false;
    }

    /**
     * 根据银行卡匹配银行
     *
     */
    public function bankInfo($card, $bankList)
    {
        $card_8 = substr($card, 0, 8);
        if (isset($bankList[$card_8])) {
            return $bankList[$card_8];
        }
        $card_6 = substr($card, 0, 6);
        if (isset($bankList[$card_6])) {
            return $bankList[$card_6];
        }
        $card_5 = substr($card, 0, 5);
        if (isset($bankList[$card_5])) {
            return $bankList[$card_5];
        }
        $card_4 = substr($card, 0, 4);
        if (isset($bankList[$card_4])) {
            return $bankList[$card_4];
        }

        return false;
    }

    public function getOrderId()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $a = $yCode[intval(date('Y')) - 2011];
        $b = strtoupper(dechex(date('m')));
        $c = date('d');
        $d = substr(time(), -5);
        $e = substr(microtime(), 2, 5);
        $f = sprintf('%02d', rand(0, 99));

        $orderSn = $a . $b . $c . $d . $e . $f;

        return $orderSn;
    }

    /**
     * 通知data服加减金币
     *
     * @param $playerId
     * @param $amount
     * @param $type - 处理金币方式 1增 2减
     * @return mixed
     */
    public function disposeGold($playerId, $amount, $type)
    {
        $present_data = [
            'sourceType' => Tool::RECHARGE_OTHER,
            'propsType' => 3,//固定为元宝
            'count' => $amount,
            'operateType' => $type,//固定为减少
            'gameId' => 1114112,//固定为大厅的id
            'userId' => $playerId
        ];
        $present_url = Yii::$app->params['recharge_Url'];
        $curl = new Curl();
        $present_data = 'msg=' . json_encode($present_data, JSON_UNESCAPED_UNICODE);
        $info = $curl->get($present_url . '?' . $present_data);
        $info = json_decode($info, true);

        return $info;
    }

    /**
     * 生成签名，规则和微信的规则一样
     *
     * @param $data (值为空的过滤掉)
     * @return bool
     */
    public function getSign($data, $apiKey = '')
    {
        if (!is_array($data) && !empty($data)) {
            return false;
        }
        /** 去空 */
        unset($data[array_search('', $data)]);

        ksort($data);
        $str = '';
        foreach ($data as $key => $val) {
            if ($str) {
                $str .= '&' . $key . '=' . $val;
            } else {
                $str .= $key . '=' . $val;
            }
        }

        if ($apiKey) {
            $str .= '&key=' . $apiKey;
        }

        $sign = strtoupper(md5($str));

        return $sign;
    }

    /**
     * 拼接请求微信接口数据
     *
     * @param $data
     * @return mixed
     */
    public function getWechatPostData($data)
    {
        $sign = $this->getSign($data, $this->config['API_KEY']);

        $data["sign"] = $sign;//签名

        return $data;

    }


    /**
     * 更新订单状态
     *
     * @param $order_id
     * @param $status
     * @param string $remark
     * @param string $reason
     * @return bool|int
     */
    protected function updatePayOrderStatus($order_id, $status, $remark = '', $reason = '')
    {
        Yii::info("更新订单状态!");
        $exchangeRecordModel = new ExchangeRecord();
        $data = ['status' => $status, 'finish_time' => date('Y-m-d H:i:s'), 'memo' => $remark . '.' . $reason];
        $res = $exchangeRecordModel->updateRecordInfo($order_id, $data);

        return $res;
    }

}











































