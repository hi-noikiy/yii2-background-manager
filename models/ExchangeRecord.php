<?php
/**
 * User: moyu
 *
 * Date: 2018/7/12 21:25
 *
 * 代理控制器
 */

namespace app\models;

use Yii;
use yii\db\Query;
use app\common\Code;

class ExchangeRecord extends CommonModel
{
    public static function tableName()
    {
        return 't_exchange_record';
    }

    /**
     * 分页获取个人的直兑订单记录
     *
     * @param $page
     * @param $limit
     * @param $playerId
     * @return array
     */
    public function getRecordByPage($page, $limit, $where,$field='create_time',$orderType='desc',$channelUnderList=[])
    {
        $record = [];
        if ($page && $limit) {
            $record = (new Query())
                ->select("*")
                ->from(self::tableName())
                ->where($where)
                ->andFilterWhere(['in','player_id',$channelUnderList])
                ->orderBy($field.' '.$orderType)
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->all();
        }

        return $record;
    }

    /**
     * 获取页数
     *
     */
    public function getRecordCount($where,$channelUnderList=[])
    {
        $count = (new Query())
            ->select("*")
            ->from(self::tableName())
            ->where($where)
            ->andFilterWhere(['in','player_id',$channelUnderList])
            ->count();

        return $count;

    }

    /**
     * 创建直兑订单记录
     *
     * @param $data
     * @return bool
     */
    public function createRecord($data)
    {
        if ($data) {
            $model = new ExchangeRecord();
            foreach ($data as $k => $v) {
                $model->$k = $v;
            }
            if ($model->save()) {
                return true;
            }
        }

        return false;
    }

    /**
     * 获取直兑订单记录
     *
     * @param $where - 查询条件
     * @param string $fields - 查询字段
     * @param int $type - 查询类型
     *
     * @return array|bool|false|null|string
     */
    public function getRecord($where, $fields="*", $type=1)
    {
        return $this->commonSelect(self::tableName(),$where,$fields,$type);
    }

    /**
     * 更新直兑订单状态
     * @param $orderId
     * @param $status
     * @return bool
     */
    public function updateRecordStatus($orderId, $status)
    {
        $model = self::findOne(['order_id' => $orderId]);
        $model->status = $status;
        if ($model->save()) {
            return true;
        }

        return false;
    }

    /**
     * 更新订单信息
     *
     * @param $orderId
     * @param $data
     * @return bool
     */
    public function updateRecordInfo($orderId, $data)
    {
        $model = self::findOne(['order_id' => $orderId]);

        foreach ($data as $k => $val) {
            $model->$k = $val;
        }

        if ($model->save()) {
            return true;
        }

        return false;
    }

    /**
     * 验证微信直兑订单是否超时
     *
     * @param $orderId
     * @return int 1 订单没超时 2超时更改订单状态失败 3订单超时，更改订单状态成功
     */
    public function checkIsTimeOut($orderId)
    {
        $exchangeRecordModel = new ExchangeRecord();

        Yii::info('验证微信直兑订单是否超时！');
        $orderInfo = $exchangeRecordModel->getRecord(['order_id' => $orderId], 'create_time,player_id', 2);
        $createTime = $orderInfo['create_time'];
        $playerId = $orderInfo['player_id'];

        $timeOut = Yii::$app->params['bind_condition']['wechat']['time_out'];
        if (((time() - strtotime($createTime)) / 60) < $timeOut) {
            return 1;//没超时
        }

        if (!$exchangeRecordModel->updateRecordInfo($orderId, ['status' => Code::TIME_OUT, 'memo' => '订单超时'])) {
            Yii::info('订单超时，更改订单状态失败！');
            return 2;
        }

        return 3;
    }

    /**
     * 验证微信直兑订单是否有效
     *
     */
    public function checkOrderIsCanUse($orderId)
    {
        Yii::info('验证微信直兑订单是否有效！');
        $status = $this->checkOrder($orderId, 'status');
        if ($status == 0) {
            return true;
        }

        return false;
    }

    public function checkOrder($orderId, $fields)
    {
        Yii::info('验证微信直兑订单');
        $exchangeRecordModel = new ExchangeRecord();
        $where[] = "order_id='" . $orderId . "'";
        $where[] = 'type = 3';
        $con = implode(" and ", $where);
        $record = $exchangeRecordModel->getRecord($con, $fields, 2);

        return $record[$fields];

    }

}
