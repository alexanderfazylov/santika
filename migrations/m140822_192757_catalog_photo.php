<?php

use yii\db\Schema;
use yii\db\Migration;

class m140822_192757_catalog_photo extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%line}}', 'catalog_photo_id', Schema::TYPE_INTEGER . ' NULL');
        $this->addColumn('{{%collection}}', 'catalog_photo_id', Schema::TYPE_INTEGER . ' NULL');

        $this->addForeignKey('FK_line_catalog_photo_to_upload', '{{%line}}', 'catalog_photo_id', '{{%upload}}', 'id');
        $this->addForeignKey('FK_collection_catalog_photo_to_upload', '{{%collection}}', 'catalog_photo_id', '{{%upload}}', 'id');

        $this->execute('Update {{%line}} set catalog_photo_id = photo_id, photo_id = null ');
    }

    public function safeDown()
    {
        $this->execute('Update {{%line}} set photo_id = catalog_photo_id');
        $this->dropForeignKey('FK_line_catalog_photo_to_upload', '{{%line}}');
        $this->dropColumn('{{%line}}', 'catalog_photo_id');
        $this->dropForeignKey('FK_collection_catalog_photo_to_upload', '{{%collection}}');
        $this->dropColumn('{{%collection}}', 'catalog_photo_id');
    }
}
