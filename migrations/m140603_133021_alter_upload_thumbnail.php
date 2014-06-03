<?php

use yii\db\Schema;

class m140603_133021_alter_upload_thumbnail extends \yii\db\Migration
{
    public function up()
    {
        $this->alterColumn('{{%upload}}', 'thumbnail', Schema::TYPE_STRING . '(255) NULL');
    }

    public function down()
    {
    }
}
