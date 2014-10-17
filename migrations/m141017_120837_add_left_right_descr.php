<?php

use yii\db\Schema;
use yii\db\Migration;

class m141017_120837_add_left_right_descr extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%collection}}', 'left_description', Schema::TYPE_STRING . '(1000)  NULL');
        $this->addColumn('{{%collection}}', 'right_description', Schema::TYPE_STRING . '(1000)  NULL');
        $this->addColumn('{{%line}}', 'left_description', Schema::TYPE_STRING . '(1000)  NULL');
        $this->addColumn('{{%line}}', 'right_description', Schema::TYPE_STRING . '(1000)  NULL');

        $this->update('{{%collection}}', ['left_description' => 'Специально для любителей утонченных вещей, фабрика Gessi выпустила серию аксессуаров для ванной комнаты, в число которых входят несколько смесителей. Любую деталь интерьера дизайнеры Gessi могут с легкостью переосмыслить и вынести ее под таким углом, что невольно восхищаешься их творческим потенциалом.']);
        $this->update('{{%collection}}', ['right_description' => 'Смесители Gessi Mimi не исключение. Лишь посмотрев на один из них, можно прийти в восторг от красоты, вроде совсем непримечательной и обыденной, на первый взгляд вещи. Вся конструкция очень изящна, грани отточены и миниатюрны.']);
        $this->update('{{%line}}', ['left_description' => 'Специально для любителей утонченных вещей, фабрика Gessi выпустила серию аксессуаров для ванной комнаты, в число которых входят несколько смесителей. Любую деталь интерьера дизайнеры Gessi могут с легкостью переосмыслить и вынести ее под таким углом, что невольно восхищаешься их творческим потенциалом.']);
        $this->update('{{%line}}', ['right_description' => 'Смесители Gessi Mimi не исключение. Лишь посмотрев на один из них, можно прийти в восторг от красоты, вроде совсем непримечательной и обыденной, на первый взгляд вещи. Вся конструкция очень изящна, грани отточены и миниатюрны.']);
    }

    public function safeDown()
    {
        $this->dropColumn('{{%collection}}', 'left_description');
        $this->dropColumn('{{%collection}}', 'right_description');
        $this->dropColumn('{{%line}}', 'left_description');
        $this->dropColumn('{{%line}}', 'right_description');
    }
}
