<?php

use yii\db\Schema;

class m140527_122706_create_photo_gallery extends \yii\db\Migration
{
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%photo_gallery}}', [
            'id' => Schema::TYPE_PK,
            'object_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'upload_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'sort' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->addForeignKey('FK_photo_gallery_to_upload', '{{%photo_gallery}}', 'upload_id', '{{%upload}}', 'id');

    }

    public function sfeDown()
    {
        $this->dropForeignKey('FK_photo_gallery_to_upload', '{{%photo_gallery}}');
        $this->dropTable('{{%photo_gallery}}');

    }
}
