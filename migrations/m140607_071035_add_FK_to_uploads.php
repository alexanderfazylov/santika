<?php

use yii\db\Schema;

class m140607_071035_add_FK_to_uploads extends \yii\db\Migration
{
    public function safeUp()
    {
        $this->execute('Update {{%product}} set photo_id= NULL where photo_id NOT IN (Select id from {{%upload}})');
        $this->addForeignKey('FK_product_photo_to_upload', '{{%product}}', 'photo_id', '{{%upload}}', 'id');

        $this->execute('Update {{%product}} set manual_id = NULL where manual_id NOT IN (Select id from {{%upload}})');
        $this->addForeignKey('FK_product_manual_to_upload', '{{%product}}', 'manual_id', '{{%upload}}', 'id');

        $this->execute('Update {{%product}} set drawing_id = NULL where drawing_id NOT IN (Select id from {{%upload}})');
        $this->addForeignKey('FK_product_drawing_to_upload', '{{%product}}', 'drawing_id', '{{%upload}}', 'id');

        $this->addForeignKey('FK_product_to_color', '{{%product}}', 'color_id', '{{%color}}', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_product_photo_to_upload', '{{%product}}');
        $this->dropForeignKey('FK_product_manual_to_upload', '{{%product}}');
        $this->dropForeignKey('FK_product_drawing_to_upload', '{{%product}}');
        $this->dropForeignKey('FK_product_to_color', '{{%product}}');
    }
}
