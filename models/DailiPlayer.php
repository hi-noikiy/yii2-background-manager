<?php
/**
 * User: SeaReef
 * Date: 2018/7/12 21:25
 *
 * 代理控制器
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;

class DailiPlayer extends CommonModel
{
    public static function tableName()
    {
        return 't_daili_player';
    }

    public $DaiLiNum = 0;

    /**
     * @param $id
     * @param $data
     * @param int $type 1 根据表id更新  2 根据玩家id更新
     * @return bool
     */
    public function updateDailiPlayer($id, $data,$type=1)
    {
        if($type == 1){
            $model = self::findOne(['id' => $id]);
        }elseif($type == 2){
            $model = self::findOne(['player_id' => $id]);
        }else{
            return false;
        }

        foreach ($data as $k => $v) {
            $model->$k = $v;
        }
        $res = $model->save();
        return $res;
    }

    public function getById($id, $type = 1)
    {
        if ($type == 1) {
            return self::findOne(['player_id' => $id]);
        } elseif ($type == 2) {
            if(self::findOne(['player_id' => $id])){
                return self::findOne(['player_id' => $id])->toArray();
            }else{
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 根据条件获取数据
     *
     * @param $con
     * @param string $fields
     * @param int $type $type 查询类型 1查询所有 2查询一条 3标量查询
     * @return bool|mixed
     */
    public function getDataByCon($con, $fields = "*", $type = 1)
    {
        return $this->commonSelect(self::tableName(), $con, $fields, $type);
    }

    /**
     * 获取玩家伞下代理数量
     *
     */
    public function getDaiLiAndPlayerNum($playerId, $date = '', &$dailiNum = 0, &$playerNum = 0)
    {
        $con = 'PLAYER_INDEX = ' . $playerId;
        if ($date) {
            $con .= " AND BIND_TIME > '" . strtotime($date) . "'" . " AND BIND_TIME < " . strtotime($date . "23:59:59");
        }
        $member = new PlayerMember();
        $memberInfo = $member->getDataByCon($con);

        foreach ($memberInfo as $k => $v) {
            $daili = $this->getDataByCon(['player_id' => $v['MEMBER_INDEX']], 'player_id');
            if ($daili) {
                foreach ($daili as $key => $val) {
                    $dailiNum++;
                    if (!self::getDaiLiAndPlayerNum($val['player_id'], $date, $dailiNum, $playerNum)) {
                        continue;
                    }
                }
            } else {
                $Player = new Player();
                $player = $Player->getPlayerById($v['MEMBER_INDEX']);
                if ($player) {
                    $playerNum++;
                }
            }
        }

        return array('dailiNum' => $dailiNum, 'playerNum' => $playerNum);
    }

    /**
     * 获取直属下级代理数量
     *
     * @param $playerId -父级id
     * @return array|bool
     */
    public function getDailiNum($playerId)
    {
        $data = (new Query())
            ->select('count(id) as num')
            ->from('t_daili_player')
            ->where(['parent_index' => $playerId])
            ->one();

        return $data;
    }

    /**
     * 获取指定id List 中代理的数量
     *
     */
    public function getAgentNumByIdList($idList){
        return (new Query())
            ->select('id')
            ->from('t_daili_player')
            ->where('player_id in (' . $idList . ')')
            ->count();

    }

    /**
     * 获取用户可提现金额
     *
     */
    public function getWithdrawCash($playerId){
        //元宝转换比例
        $proportion = Yii::$app->params['gold_withdraw_deposit'];
        $pay_back_gold = $this->getDataByCon(['player_id' => $playerId],'pay_back_gold',3);

        return $pay_back_gold * $proportion;
    }

    public function getDataByPage($where,$limit,$page){
        $data = (new Query())
            ->select('*')
            ->from(self::tableName())
            ->where($where)
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->all();

        return $data;
    }

    public function getDataCount($where){
        return (new Query())
            ->select('id')
            ->from(self::tableName())
            ->where($where)
            ->count();
    }

}