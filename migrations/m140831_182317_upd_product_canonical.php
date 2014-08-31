<?php

use app\models\Product;
use yii\db\Schema;
use yii\db\Migration;

class m140831_182317_upd_product_canonical extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%product}}', 'canonical', Schema::TYPE_STRING . '(500) NULL');
        $this->execute("Update {{%product}} set canonical = CONCAT('/catalog/product/' , url )");
    }

    public function safeDown()
    {
    }
}
