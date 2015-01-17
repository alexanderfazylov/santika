<?php

use yii\db\Schema;
use yii\db\Migration;

class m150117_121705_add_diameter extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'diameter', Schema::TYPE_INTEGER . ' NULL');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%product}}', 'diameter');
    }
}
