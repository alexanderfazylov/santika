<?php

use yii\db\Schema;
use yii\db\Migration;

class m140805_132548_create_add_photo_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%collection}}', 'photo_id', Schema::TYPE_INTEGER . ' NULL');
        $this->addColumn('{{%category}}', 'photo_id', Schema::TYPE_INTEGER . ' NULL');
        $this->addForeignKey('FK_collection_photo_to_upload', '{{%collection}}', 'photo_id', '{{%upload}}', 'id');
        $this->addForeignKey('FK_category_photo_to_upload', '{{%category}}', 'photo_id', '{{%upload}}', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('FK_collection_photo_to_upload', '{{%collection}}');
        $this->dropForeignKey('FK_category_photo_to_upload', '{{%category}}');
        $this->dropColumn('{{%category}}', 'photo_id');
        $this->dropColumn('{{%collection}}', 'photo_id');
    }
}
