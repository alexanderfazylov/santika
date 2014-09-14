<?php

use yii\db\Schema;
use yii\db\Migration;

class m140914_091956_add_installation extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%installation}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%product_installation}}', [
            'id' => Schema::TYPE_PK,
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'installation_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%product_installation_product}}', [
            'id' => Schema::TYPE_PK,
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'installation_product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->addForeignKey('FK_product_installation_to_product', '{{%product_installation}}', 'product_id', '{{%product}}', 'id');
        $this->addForeignKey('FK_product_installation_to_installation', '{{%product_installation}}', 'installation_id', '{{%installation}}', 'id');
        $this->addForeignKey('FK_product_installation_product_to_product', '{{%product_installation_product}}', 'product_id', '{{%product}}', 'id');
        $this->addForeignKey('FK_product_installation_product_to_product2', '{{%product_installation_product}}', 'installation_product_id', '{{%product}}', 'id');

    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_product_installation_to_product', '{{%product_installation}}');
        $this->dropForeignKey('FK_product_installation_to_installation', '{{%product_installation}}');
        $this->dropForeignKey('FK_product_installation_product_to_product', '{{%product_installation_product}}');
        $this->dropForeignKey('FK_product_installation_product_to_product2', '{{%product_installation_product}}');

        $this->dropTable('{{%installation}}');
        $this->dropTable('{{%product_installation}}');
        $this->dropTable('{{%product_installation_product}}');

    }
}
