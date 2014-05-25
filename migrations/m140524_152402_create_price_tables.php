<?php

use yii\db\Schema;

class m140524_152402_create_price_tables extends \yii\db\Migration
{
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%price}}', [
            'id' => Schema::TYPE_PK,
            'shop_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'start_date' => Schema::TYPE_DATE . ' NOT NULL',
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%price_product}}', [
            'id' => Schema::TYPE_PK,
            'price_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'cost_eur' => Schema::TYPE_DECIMAL . '(13,2) NOT NULL',
            'cost_rub' => Schema::TYPE_DECIMAL . '(13,2) NULL',
        ], $tableOptions);


        $this->addForeignKey('FK_price_product_to_price', '{{%price_product}}', 'price_id', '{{%price}}', 'id');
        $this->addForeignKey('FK_price_product_to_product', '{{%price_product}}', 'product_id', '{{%product}}', 'id');


        $this->createIndex('UNIQ_price_product', '{{%price_product}}', ['price_id', 'product_id'], true);
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_price_product_to_price', '{{%price_product}}');
        $this->dropForeignKey('FK_price_product_to_product', '{{%price_product}}');

        $this->dropTable('{{%price_product}}');
        $this->dropTable('{{%price}}');
    }
}
