<?php

use yii\db\Schema;

class m140612_114554_add_import_id_in_price extends \yii\db\Migration
{
    public function up()
    {

        $this->addColumn('{{%price}}', 'import_id', Schema::TYPE_INTEGER . ' NULL');
        $this->addForeignKey('FK_price_import_to_upload', '{{%price}}', 'import_id', '{{%upload}}', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('FK_price_import_to_upload', '{{%price}}');
        $this->dropColumn('{{%price}}', 'import_id');
    }
}
