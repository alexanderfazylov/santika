<?php

use yii\db\Schema;

class m140531_172340_interactive_product_chante extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%interactive_product}}', 'top', Schema::TYPE_FLOAT . '(10,4) NOT NULL');
        $this->alterColumn('{{%interactive_product}}', 'left', Schema::TYPE_FLOAT . '(10,4) NOT NULL');

    }

    public function safeDown()
    {
    }
}
