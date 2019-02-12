<?php
/**
 * User: SeaReef
 * Date: 2018/8/21 16:22
 *
 * 玩家表
 */
namespace app\models;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use yii\db\ActiveRecord;
use yii\db\Query;


class Player extends ActiveRecord
{
    public static function tableName()
    {
        return 't_player';
    }

    public function exportPlayers($data)
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        //设置工作表标题名称
        $worksheet->settitle('认证用户表');
        //表头
        //设置单元格
        $worksheet->setCellValueByColumnAndRow(1,1,'认证用户表');
        $worksheet->setCellValueByColumnAndRow(1,2,'玩家认证日期');
        $worksheet->setCellValueByColumnAndRow(2,2,'玩家注册游戏日期');
        $worksheet->setCellValueByColumnAndRow(3,2,'玩家ID');
        $worksheet->setCellValueByColumnAndRow(4,2,'玩家昵称');
        $worksheet->setCellValueByColumnAndRow(5,2,'玩家手机号');
        //$worksheet->setCellValueByColumnAndRow(7,2,'微信号');
        $worksheet->setCellValueByColumnAndRow(6,2,'ip');
        $worksheet->setCellValueByColumnAndRow(7,2,'设备信息（Android/iOS）');

        //合并单元格
        $worksheet->mergeCells('A1:J1');

        $styleArray = [
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ];
        //设置单元格样式
        $worksheet->getStyle('A1')->applyFromArray($styleArray)->getFont()->setSize(28);
        $worksheet->getStyle('A2:G2')->applyFromArray($styleArray)->getFont()->setSize(14);

        $len = count($data);
        for ($i = 0;$i < $len;$i++) {
            $j = $i+3;
            $worksheet->setCellValueByColumnAndRow(1,$j,$data[$i]['auth_time']);
            $worksheet->setCellValueByColumnAndRow(2,$j,$data[$i]['reg_time']);
            $worksheet->setCellValueByColumnAndRow(3,$j,$data[$i]['player_id']);
            $worksheet->setCellValueByColumnAndRow(4,$j,$data[$i]['nickname']);
            $worksheet->setCellValueByColumnAndRow(5,$j,$data[$i]['phone_num']);
            $worksheet->setCellValueByColumnAndRow(6,$j,$data[$i]['ip']);
            $worksheet->setCellValueByColumnAndRow(7,$j,$data[$i]['machine_code']);
        }
        $styleArrayBody = [
            'borders' => [
                'allBorders' => [
                    'bordersStyle' => Border::BORDER_THIN,
                    'color' => ['argb'=>'666666']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ];

        $total_rows = $len + 2;
        $worksheet->getStyle('A1:G'.$total_rows)->applyFromArray($styleArrayBody);
        return $spreadsheet;
    }

    public function getPlayerById($id,$fields="*"){
        $data = (new Query())
            ->select($fields)
            ->from(self::tableName())
            ->where(['player_id'=>$id])
            ->one();

        return $data;
    }

    public function getPlayerByCon($con,$fields="*"){
        $data = (new Query())
            ->select($fields)
            ->from(self::tableName())
            ->where($con)
            ->one();

        return $data;
    }
}