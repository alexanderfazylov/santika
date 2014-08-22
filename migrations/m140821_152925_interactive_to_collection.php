<?php

use yii\db\Schema;
use yii\db\Migration;

class m140821_152925_interactive_to_collection extends Migration
{
    public function safeUp()
    {
        $this->dropForeignKey('FK_interactive_to_line', '{{%interactive}}');
        $this->renameColumn('{{%interactive}}', 'line_id', 'object_id');
        $this->addColumn('{{%interactive}}', 'type', Schema::TYPE_INTEGER . '  NULL');
        $this->update('{{%interactive}}',['type'=>\app\models\Interactive::TYPE_LINE]);


    }

    public function saafeDown()
    {
        $this->renameColumn('{{%interactive}}', 'object_id', 'line_id');
        $this->dropColumn('{{%interactive}}', 'type');
        $this->addForeignKey('FK_interactive_to_line', '{{%interactive}}', 'line_id', '{{%line}}', 'id');
    }
}
