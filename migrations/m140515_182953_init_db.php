<?php

use yii\db\Schema;

class m140515_182953_init_db extends \yii\db\Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%shop}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'short_about' => Schema::TYPE_STRING . '(255) NOT NULL',
            'full_about' => Schema::TYPE_STRING . '(255) NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%line}}', [
            'id' => Schema::TYPE_PK,
            'shop_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'description' => Schema::TYPE_STRING . '(255) NOT NULL',
            'sort' => Schema::TYPE_INTEGER . ' NULL',
            'url' => Schema::TYPE_STRING . '(255) NOT NULL',
            'meta_title' => Schema::TYPE_STRING . '(255) NULL',
            'meta_description' => Schema::TYPE_STRING . '(255) NULL',
            'meta_keywords' => Schema::TYPE_STRING . '(255) NULL',
        ], $tableOptions);

        $this->createTable('{{%collection}}', [
            'id' => Schema::TYPE_PK,
            'shop_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'description' => Schema::TYPE_STRING . '(255) NOT NULL',
            'sort' => Schema::TYPE_INTEGER . ' NULL',
            'url' => Schema::TYPE_STRING . '(255) NOT NULL',
            'meta_title' => Schema::TYPE_STRING . '(255) NULL',
            'meta_description' => Schema::TYPE_STRING . '(255) NULL',
            'meta_keywords' => Schema::TYPE_STRING . '(255) NULL',
        ], $tableOptions);

        $this->createTable('{{%category}}', [
            'id' => Schema::TYPE_PK,
            'shop_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'parent_id' => Schema::TYPE_INTEGER . ' NULL',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'sort' => Schema::TYPE_INTEGER . ' NULL',
            'url' => Schema::TYPE_STRING . '(255) NOT NULL',
            'meta_title' => Schema::TYPE_STRING . '(255) NULL',
            'meta_description' => Schema::TYPE_STRING . '(255) NULL',
            'meta_keywords' => Schema::TYPE_STRING . '(255) NULL',
        ], $tableOptions);

        $this->createTable('{{%product}}', [
            'id' => Schema::TYPE_PK,
            'shop_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'collection_id' => Schema::TYPE_INTEGER . ' NULL',
            'category_id' => Schema::TYPE_INTEGER . ' NULL',
            'manual_id' => Schema::TYPE_INTEGER . ' NULL',
            'coat_id' => Schema::TYPE_INTEGER . ' NULL',
            'drawing_id' => Schema::TYPE_INTEGER . ' NULL',
            'article' => Schema::TYPE_STRING . '(255) NOT NULL',
            'series' => Schema::TYPE_STRING . '(255) NOT NULL',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'description' => Schema::TYPE_STRING . '(255) NOT NULL',
            'length' => Schema::TYPE_INTEGER . ' NULL',
            'width' => Schema::TYPE_INTEGER . ' NULL',
            'height' => Schema::TYPE_INTEGER . ' NULL',
            'is_promotion' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE',
            'url' => Schema::TYPE_STRING . '(255) NOT NULL',
            'meta_title' => Schema::TYPE_STRING . '(255) NULL',
            'meta_description' => Schema::TYPE_STRING . '(255) NULL',
            'meta_keywords' => Schema::TYPE_STRING . '(255) NULL',
        ], $tableOptions);

        $this->createTable('{{%line_product}}', [
            'id' => Schema::TYPE_PK,
            'line_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'product_id' => Schema::TYPE_INTEGER . ' NULL',
        ], $tableOptions);

        $this->createTable('{{%line_category}}', [
            'id' => Schema::TYPE_PK,
            'line_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'category_id' => Schema::TYPE_INTEGER . ' NULL',
        ], $tableOptions);

        $this->addForeignKey('FK_line_to_shop', '{{%line}}', 'shop_id', '{{%shop}}', 'id');
        $this->addForeignKey('FK_collection_to_shop', '{{%collection}}', 'shop_id', '{{%shop}}', 'id');
        $this->addForeignKey('FK_category_to_shop', '{{%category}}', 'shop_id', '{{%shop}}', 'id');
        $this->addForeignKey('FK_category_to_category', '{{%category}}', 'parent_id', '{{%category}}', 'id');
        $this->addForeignKey('FK_product_to_shop', '{{%product}}', 'shop_id', '{{%shop}}', 'id');
        $this->addForeignKey('FK_product_to_collection', '{{%product}}', 'collection_id', '{{%collection}}', 'id');
        $this->addForeignKey('FK_product_to_category', '{{%product}}', 'category_id', '{{%category}}', 'id');

        $this->addForeignKey('FK_line_product_to_line', '{{%line_product}}', 'line_id', '{{%line}}', 'id');
        $this->addForeignKey('FK_line_product_to_product', '{{%line_product}}', 'product_id', '{{%product}}', 'id');
        $this->addForeignKey('FK_line_category_to_line', '{{%line_category}}', 'line_id', '{{%line}}', 'id');
        $this->addForeignKey('FK_line_category_to_category', '{{%line_category}}', 'category_id', '{{%category}}', 'id');
//        $this->addForeignKey('', '{{%}}', '', '{{%}}', '');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_line_to_shop', '{{%line}}');
        $this->dropForeignKey('FK_collection_to_shop', '{{%collection}}');
        $this->dropForeignKey('FK_category_to_shop', '{{%category}}');
        $this->dropForeignKey('FK_category_to_category', '{{%category}}');
        $this->dropForeignKey('FK_product_to_shop', '{{%product}}');
        $this->dropForeignKey('FK_product_to_collection', '{{%product}}');
        $this->dropForeignKey('FK_product_to_category', '{{%product}}');
        $this->dropForeignKey('FK_line_product_to_line', '{{%line_product}}');
        $this->dropForeignKey('FK_line_product_to_product', '{{%line_product}}');
        $this->dropForeignKey('FK_line_category_to_line', '{{%line_category}}');
        $this->dropForeignKey('FK_line_category_to_category', '{{%line_category}}');

        $this->dropTable('{{%shop}}');
        $this->dropTable('{{%line}}');
        $this->dropTable('{{%collection}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%product}}');
        $this->dropTable('{{%line_product}}');
        $this->dropTable('{{%line_category}}');
    }

}
