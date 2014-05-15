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
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
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
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
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
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
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
            'is_promotion' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
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

    }

    public function safeDown()
    {
        $this->dropTable('{{%shop}}');
        $this->dropTable('{{%line}}');
        $this->dropTable('{{%collection}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%product}}');
        $this->dropTable('{{%line_product}}');
        $this->dropTable('{{%line_category}}');
    }

}
