<?php

use yii\db\Schema;
use yii\db\Migration;

class m140803_103404_line_name_fix extends Migration
{
    public function safeUp()
    {
        $this->update(
            '{{%line}}',
            ['name' => 'Кухня'],
            ['name' => 'Кухонные смесители']
        );

        $this->update(
            '{{%line}}',
            ['name' => 'Ванная комната'],
            ['name' => 'Ванные комнаты']
        );
    }

    public function safeDown()
    {
        $this->update(
            '{{%line}}',
            ['name' => 'Ванные комнаты'],
            ['name' => 'Ванная комната']
        );
        $this->update(
            '{{%line}}',
            ['name' => 'Кухонные смесители'],
            ['name' => 'Кухня']
        );

    }
}
