<?php

use yii\db\Schema;

class m140614_174732_add_parent_id_in_collection extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('{{%collection}}', 'parent_id', Schema::TYPE_INTEGER . ' NULL');
        $this->addForeignKey('FK_collection_to_collection', '{{%collection}}', 'parent_id', '{{%collection}}', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('FK_collection_to_collection', '{{%collection}}');
        $this->dropColumn('{{%collection}}', 'parent_id');
    }
}
