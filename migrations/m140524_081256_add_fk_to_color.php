<?php

use yii\db\Schema;

class m140524_081256_add_fk_to_color extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%color}}', 'upload_id', Schema::TYPE_INTEGER , ' NOT NULL');
        $this->addForeignKey('FK_color_to_upload', '{{%color}}', 'upload_id', '{{%upload}}', 'id');

        $this->renameColumn('{{%product}}', 'coat_id', 'color_id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_color_to_upload', '{{%color}}');
        $this->renameColumn('{{%product}}', 'color_id', 'coat_id');
    }
}
