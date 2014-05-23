<?php

use yii\db\Schema;

class m140522_183731_create_uploads extends \yii\db\Migration
{
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%upload}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'path' => Schema::TYPE_STRING . '(255) NOT NULL',
            'ext' => Schema::TYPE_STRING . '(255) NULL',
        ], $tableOptions);

        $this->addColumn('{{%product}}', 'photo_id', Schema::TYPE_INTEGER . ' NULL');
        $this->dropColumn('{{%product}}', 'series');
    }

    public function safeDown()
    {
        $this->dropTable('{{%upload}}');
        $this->dropColumn('{{%product}}', 'photo_id');
        $this->addColumn('{{%product}}', 'series', Schema::TYPE_INTEGER . ' NULL');
    }
}
