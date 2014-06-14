<?php

use yii\db\Schema;

class m140613_112145_change_product_color_logic extends \yii\db\Migration
{
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%product_color}}', [
            'id' => Schema::TYPE_PK,
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'color_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'photo_id' => Schema::TYPE_INTEGER . ' NULL'
        ], $tableOptions);

        $this->addColumn('{{%color}}', 'article', Schema::TYPE_STRING . '(255) NOT NULL');
        $this->addColumn('{{%product}}', 'is_published', Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE');
        $this->addColumn('{{%photo_gallery}}', 'color_id', Schema::TYPE_INTEGER . ' NULL');
        $this->addColumn('{{%price_product}}', 'color_id', Schema::TYPE_INTEGER . ' NULL');

        $this->addForeignKey('FK_photo_gallery_to_color', '{{%photo_gallery}}', 'color_id', '{{%color}}', 'id');
        $this->addForeignKey('FK_product_color_to_color', '{{%product_color}}', 'color_id', '{{%color}}', 'id');
        $this->addForeignKey('FK_product_color_to_product', '{{%product_color}}', 'product_id', '{{%product}}', 'id');
        $this->addForeignKey('FK_product_color_to_upload', '{{%product_color}}', 'photo_id', '{{%upload}}', 'id');

        $this->update('{{%product}}', ['is_published' => 1]);

        $this->execute('INSERT INTO {{%product_color}} (product_id, color_id )  (SELECT id, color_id from {{%product}} WHERE color_id is not null)');

        $products = \app\models\Product::findAll(['color_id is not null']);
        foreach ($products as $product) {
            $this->update('{{%price_product}}', ['color_id' => $product->color_id], ['product_id' => $product->id]);
            $this->update('{{%photo_gallery}}', ['color_id' => $product->color_id], ['object_id' => $product->id, 'type' => \app\models\PhotoGallery::TYPE_PRODUCT]);
        }

        $this->dropForeignKey('FK_price_product_to_price', '{{%price_product}}');
        $this->dropForeignKey('FK_price_product_to_product', '{{%price_product}}');
        $this->dropIndex('UNIQ_price_product', '{{%price_product}}');

        $this->createIndex('UNIQ_price_product', '{{%price_product}}', ['price_id', 'product_id', 'color_id'], true);
        $this->addForeignKey('FK_price_product_to_price', '{{%price_product}}', 'price_id', '{{%price}}', 'id');
        $this->addForeignKey('FK_price_product_to_product', '{{%price_product}}', 'product_id', '{{%product}}', 'id');
        $this->addForeignKey('FK_price_product_to_color', '{{%price_product}}', 'color_id', '{{%color}}', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_photo_gallery_to_color', '{{%photo_gallery}}');
        $this->dropForeignKey('FK_product_color_to_color', '{{%product_color}}');
        $this->dropForeignKey('FK_product_color_to_product', '{{%product_color}}');
        $this->dropForeignKey('FK_product_color_to_upload', '{{%product_color}}');

        $this->dropColumn('{{%color}}', 'article');
        $this->dropColumn('{{%product}}', 'is_published');
        $this->dropColumn('{{%photo_gallery}}', 'color_id');
        $this->dropColumn('{{%price_product}}', 'color_id');

        $this->dropTable('{{%product_color}}');

        $this->dropForeignKey('FK_price_product_to_color', '{{%price_product}}');
        $this->dropForeignKey('FK_price_product_to_price', '{{%price_product}}');
        $this->dropForeignKey('FK_price_product_to_product', '{{%price_product}}');
        $this->dropIndex('UNIQ_price_product', '{{%price_product}}');

        $this->addForeignKey('FK_price_product_to_price', '{{%price_product}}', 'price_id', '{{%price}}', 'id');
        $this->addForeignKey('FK_price_product_to_product', '{{%price_product}}', 'product_id', '{{%product}}', 'id');
        $this->createIndex('UNIQ_price_product', '{{%price_product}}', ['price_id', 'product_id'], true);
    }
}
