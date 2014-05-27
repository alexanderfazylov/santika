<?php

use yii\db\Schema;

class m140527_050705_cbr_table extends \yii\db\Migration
{
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%currency}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'value' => Schema::TYPE_FLOAT . '(10,4) NOT NULL',
            'cdate' => Schema::TYPE_DATE . ' NOT NULL',
        ], $tableOptions);


        $this->addColumn('{{%product}}', 'canonical', Schema::TYPE_STRING . '(255)  NULL');
    }

    public function safeDown()
    {
        $this->dropTable('{{%currency}}');
        $this->dropColumn('{{%product}}', 'canonical');

    }
}
