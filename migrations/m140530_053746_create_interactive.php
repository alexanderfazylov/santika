<?php

use yii\db\Schema;

class m140530_053746_create_interactive extends \yii\db\Migration
{
    public function up()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%interactive}}', [
            'id' => Schema::TYPE_PK,
            'line_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'upload_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . '(255)  NULL',
        ], $tableOptions);

        $this->createTable('{{%interactive_product}}', [
            'id' => Schema::TYPE_PK,
            'interactive_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'left' => Schema::TYPE_INTEGER . ' NOT NULL',
            'top' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);


        $this->addForeignKey('FK_interactive_to_upload', '{{%interactive}}', 'upload_id', '{{%upload}}', 'id');
        $this->addForeignKey('FK_interactive_to_line', '{{%interactive}}', 'line_id', '{{%line}}', 'id');
        $this->addForeignKey('FK_interactive_product_to_interactive', '{{%interactive_product}}', 'interactive_id', '{{%interactive}}', 'id');
        $this->addForeignKey('FK_interactive_product_to_product', '{{%interactive_product}}', 'product_id', '{{%product}}', 'id');

    }

    public function down()
    {

        $this->dropForeignKey('FK_interactive_to_upload', '{{%interactive}}');
        $this->dropForeignKey('FK_interactive_to_line', '{{%interactive}}');
        $this->dropForeignKey('FK_interactive_product_to_interactive', '{{%interactive}}');
        $this->dropForeignKey('FK_interactive_product_to_product', '{{%interactive}}');

        $this->dropTable('{{%interactive_product}}');
        $this->dropTable('{{%interactive}}');

    }
}
