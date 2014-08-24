<?php

use yii\db\Schema;
use yii\db\Migration;

class m140824_190432_add_show_in_catalog extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%collection}}', 'show_in_catalog', Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT FALSE');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%collection}}', 'show_in_catalog');
    }
}
