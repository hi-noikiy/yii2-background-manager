<?php

use yii\db\Migration;

/**
 * Class m181128_140642_create_table_conf_rebate_ratio
 */
class m181128_140642_create_table_conf_rebate_ratio extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('conf_rebate_ratio', [
            'id' => \yii\db\Schema::TYPE_UPK,
            'level' => $this->tinyInteger()->unsigned()->notNull()->comment('代理级别'),
            'min' => $this->integer()->unsigned()->notNull()->comment('最低消耗'),
            'max' => $this->integer()->unsigned()->notNull()->comment('最高消耗'),
            'ratio' => $this->decimal(10,2)->unsigned()->notNull()->comment('返利比例'),
            'create_time' => $this->dateTime()->notNull()->comment('创建时间'),
            'update_time' => $this->dateTime()->notNull()->comment('更新时间'),
        ]);

        $t = date('Y-m-d H:i:s');
        $this->batchInsert('conf_rebate_ratio', [
            'level',
            'min',
            'max',
            'ratio',
            'create_time',
            'update_time',
        ], [
//            [1, 0, 1100000, 0.3, $t, $t],
//            [2, 1100110, 2200000, 0.35, $t, $t],
//            [3, 2200110, 5500000, 0.4, $t, $t],
//            [4, 5500110, 11000000, 0.45, $t, $t],
//            [5, 11000110, 22000000, 0.5, $t, $t],
//            [6, 22000110, 44000000, 0.6, $t, $t],
//            [7, 44000110, 88000000, 0.7, $t, $t],
//            [8, 88000000, 4294967290, 0.8, $t, $t],

            [1, 0, 110000, 0.24, $t, $t],
            [2, 110000, 275000, 0.28, $t, $t],
            [3, 275000, 825000, 0.32, $t, $t],
            [4, 825000, 1650000, 0.36, $t, $t],
            [5, 1650000, 2750000, 0.4, $t, $t],
            [6, 2750000, 5500000, 0.44, $t, $t],
            [7, 5500000, 11000000, 0.48, $t, $t],
            [8, 11000000, 16500000, 0.52, $t, $t],
            [9, 16500000, 22000000, 0.56, $t, $t],
            [10, 22000000, 44000000, 0.60, $t, $t],
            [11, 44000000, 88000000, 0.64, $t, $t],
            [12, 88000000, 132000000, 0.68, $t, $t],
            [13, 132000000, 330000000, 0.72, $t, $t],
            [14, 330000000, 4294967290, 0.8, $t, $t],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('conf_rebate_ratio');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181128_140642_create_table_conf_rebate_ratio cannot be reverted.\n";

        return false;
    }
    */
}
