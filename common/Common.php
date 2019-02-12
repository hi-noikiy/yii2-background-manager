<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/30
 * Time: 20:45
 */

namespace app\common;

use Yii;
use wsl\ip2location\Ip2Location;

class Common
{
    private static $common;
    
    /**
     * 智能化时间区间处理
     *
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function disposeTemporalInterval($startTime,$endTime){
        if((!$startTime && $endTime) || ($startTime && !$endTime)){
            if(!$startTime){
                $startTime = date('Y-m-d');
            }
            if(!$endTime){
                $endTime = date('Y-m-d');
            }
        }

        if(!$startTime && !$endTime){
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d",strtotime("+1 day"));
        }

        if(strtotime($startTime) > strtotime($endTime)){
            $t = $startTime;
            $startTime = $endTime;
            $endTime = $t;
        }

        $data['startTime'] = $startTime;
        $data['endTime']   = $endTime;

        return $data;

    }

    public function Date_segmentation($start_date, $end_date)
    {
        //如果为空，则从今天的0点为开始时间
        if (!empty($start_date))
            $start_date = date('Y-m-d H:i:s', strtotime($start_date));
        else
            $start_date = date('Y-m-d 00:00:00', time());

        //如果为空，则以明天的0点为结束时间（不存在24:00:00，只会有00:00:00）
        if (!empty($end_date))
            $end_date = date('Y-m-d H:i:s', strtotime($end_date));
        else
            $end_date = date('Y-m-d 00:00:00', strtotime('+1 day'));

        //between 查询 要求必须是从低到高
        if ($start_date > $end_date) {
            $ttt = $start_date;
            $start_date = $end_date;
            $end_date = $ttt;
        } elseif ($start_date == $end_date) {
            echo '时间输入错误';
            die;
        }

        $time_s = strtotime($start_date);
        $time_e = strtotime($end_date);
        $seconds_in_a_day = 86400;

        //生成中间时间点数组（时间戳格式、日期时间格式、日期序列）
        $days_inline_array = array();
        $times_inline_array = array();

        //日期序列
        $days_list = array();

        //判断开始和结束时间是不是在同一天
        $days_inline_array[0] = $start_date;

        //初始化第一个时间点
        $times_inline_array[0] = $time_s;

        //初始化第一个时间点
        $days_list[] = date('Y-m-d', $time_s);

        //初始化第一天
        if (
            date('Y-m-d', $time_s) == date('Y-m-d', $time_e)) {
            $days_inline_array[1] = $end_date;
            $times_inline_array[1] = $time_e;
        } else {
            /**
             * A.取开始时间的第二天凌晨0点
             * B.用结束时间减去A
             * C.用B除86400取商，取余
             * D.用A按C的商循环+86400，取得分割时间点，如果C没有余数，则最后一个时间点 与 循环最后一个时间点一致
             */
            $A_temp = date('Y-m-d 00:00:00', $time_s + $seconds_in_a_day);
            $A = strtotime($A_temp);
            $B = $time_e - $A;
            $C_quotient = floor($B / $seconds_in_a_day);

            //商舍去法取整
            $C_remainder = fmod($B, $seconds_in_a_day);

            //余数
            $days_inline_array[1] = $A_temp;
            $times_inline_array[1] = $A;
            $days_list[] = date('Y-m-d', $A);

            //第二天
            for ($increase_time = $A, $c_count_t = 1; $c_count_t <= $C_quotient; $c_count_t++) {
                $increase_time += $seconds_in_a_day;
                $days_inline_array[] = date('Y-m-d H:i:s', $increase_time);
                $times_inline_array[] = $increase_time;
                $days_list[] = date('Y-m-d', $increase_time);
            }

            $days_inline_array[] = $end_date;
            $times_inline_array[] = $time_e;
        }
        return array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'days_list' => $days_list,
            'days_inline' => $days_inline_array,
            'times_inline' => $times_inline_array
        );
    }

    /**
     * Get timezone list
     *
     * @param $startTime
     * @param $dateTime
     * @param $type (start_date,end_date,days_list-2018-10-11,days_inline-20181011,times_inline-时间戳)  -default:days_list
     * @return array
     */
    public static function getDateList($startTime,$endTime,$type='days_list'){
        if(!self::$common){
            self::$common = new Common();
        }
        $date = self::$common->disposeTemporalInterval($startTime,$endTime);
        $dateList = [];
        if($date['startTime'] == $date['endTime']){
            $dateList[] = $date['startTime'];
        }else{
            $dateList = self::$common->Date_segmentation($date['startTime'],$date['endTime'])[$type];
        }

        return $dateList;
    }

    public static function getWeekList($start_time,$end_time){
        if(!self::$common){
            self::$common = new Common();
        }
        $date = self::$common->disposeTemporalInterval($start_time,$end_time);
        $start_time = strtotime($date['startTime']);
        $week_time = date('Y-m-d',strtotime(date('Y-m-d', $start_time)) - date('w',strtotime(date('Y-m-d', $start_time - 86400))) * 86400);
        $weekList[]=$week_time;
        while ((strtotime($week_time) + 86400 * 7) < strtotime($end_time)){
            $week_time = date('Y-m-d',strtotime($week_time) + 86400 * 7);
            $weekList[] = $week_time;
        }

        return $weekList;
    }

    /**
     * 获取本周一和本周日
     *
     * ---------------------
     * 作者：nextvary
     * 来源：CSDN
     * 原文：https://blog.csdn.net/nextvary/article/details/70281040
     *
     * 版权声明：本文为博主原创文章，转载请附上博文链接！
     * @param $time
     * @return mixed
     */
    public static function getWeekday($time){
        $time=empty($time)?time():$time;
        $benzhou=date('w',$time);//1
        $month=date('m',$time);
        $day=date('d',$time);
        $year=date('Y',$time);
        $data['first']=date('Y-m-d',mktime(0,0,0,$month,$day-$benzhou+1,$year));
        $data['end']=date('Y-m-d',mktime(0,0,0,$month,$day+7-$benzhou,$year));
        return $data;
    }

    /**
     *  参数由数组转为xml
     */
    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            /*if (is_numeric($val)) {
                $xml .= "<".$key.">".$val."</".$key.">";
            } else {
                $xml .= "<".$key."><![CDATA[".$val."]]></".$key.">";
            }*/
        }
        $xml .= "</xml>";
        return $xml;
    }


    /**
     *
     * @return array
     */
    public static function getIp(){
        $request = Yii::$app->request;
        $request->ipHeaders = [
            'ali-cdn-real-ip',
        ];
        $ip = Yii::$app->request->userIp;

        $ipLocation = new Ip2Location();
        $locationModel = $ipLocation->getLocation($ip);
        return $locationModel->toArray();
    }

    /**
     * 保留两位，不四舍五入
     *
     * @param $n
     * @return bool|string
     */
    public static function disposeStr($n){
        if($n < 0){ return 0; }
        return floor($n*100)/100;//floor
    }

}