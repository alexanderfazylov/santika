<?php

use yii\db\Schema;

class m140515_192305_fill_db extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->insert('{{%shop}}', array(
            'name' => 'GESSI',
            'short_about' =>'short_about',
            'full_about' =>  'full_about',
        ));
    }

    public function safeDown()
    {
       $this->truncateTable('{{%shop}}');
    }

}
