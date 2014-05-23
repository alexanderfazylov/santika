<?php

use yii\db\Schema;

class m140523_133101_create_color extends \yii\db\Migration
{
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%color}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'upload_id' => Schema::TYPE_STRING . '(255) NULL',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%color}}');
    }
}
