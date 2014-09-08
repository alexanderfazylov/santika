<?php

use app\models\Line;
use app\models\Upload;
use yii\db\Schema;
use yii\db\Migration;

class m140727_192509_data_to_design extends Migration
{
    public function safeUp()
    {
        $this->update(
            '{{%shop}}',
            ['short_about' => 'Недавно открытый отель Armani в Милане воспринимается как символ Haute Couture Hotellerie, подчеркивая комфорт и изысканную сдержанную роскошь. После грандиозного проекта Dubai, Gessi были утверждены в качестве поставщика для Armani Hotels & Resorts.'],
            ['name' => 'GESSI']
        );

        $this->update(
            '{{%line}}',
            ['description' => 'Дизайнер: Prospero Rasulo
Революционный дизайн, сочетающий совершенные пропорции с чистыми формами.'],
            ['name' => 'Cono']
        );

        $this->update(
            '{{%line}}',
            ['description' => 'Дизайнер: Prospero Rasulo
Революционный дизайн, сочетающий совершенные пропорции с чистыми формами.'],
            ['name' => 'Eleganza']
        );

        $this->update(
            '{{%line}}',
            ['description' => 'На Cersaie 2011 Gessi представила коллекцию iSpa - вольную интерпретацию культурных икон современной техники. В рамках коллекции iSpa, бренд со своим умением вызвать восхищение совершенными формами.'],
            ['name' => 'Ispa']
        );

        $this->update(
            '{{%line}}',
            ['description' => 'Коллекцию Private Wilness от итальянской фирмы Gessi можно с легкостью назвать воплощением будущего в формах настоящего. Суперсовременные души от этой фабрики, которые обладают не только уникальной формой.'],
            ['name' => 'Private Wellness']
        );

        $this->update(
            '{{%line}}',
            ['description' => 'Дизайнер: Gessi Style Studio
Революционный дизайн, сочетающий совершенные пропорции с чистыми формами.',
                'name' => 'Кухонные смесители',
            ],
            ['name' => 'Кухня']
        );

        $this->addColumn('{{%line}}', 'photo_id', Schema::TYPE_INTEGER . ' NULL');
        $this->addForeignKey('FK_line_photo_to_upload', '{{%line}}', 'photo_id', '{{%upload}}', 'id');


        $this->update(
            '{{%line}}',
            ['name' => 'Ванные комнаты'],
            ['name' => 'Ванная комната']
        );

        //Обновляем картинки у линий
        $photos = [
            'Cono' => 'cat1.jpg',
            'Ванные комнаты' => 'cat4.jpg',
            'Private Wellness' => 'cat5.jpg',
            'Сенсорные смесители' => 'cat6.jpg',
            'Кухонные смесители' => 'cat7.jpg',
        ];
        $uploads_dir = Upload::getUploadsPath();
        //путь до временного файла
        $dest_folder = Upload::getUploadsPathByType(Upload::TYPE_TMP);
        foreach ($photos as $line_name => $photo_tmp) {
            $source = Yii::getAlias('@app')
                . DIRECTORY_SEPARATOR
                . 'other'
                . DIRECTORY_SEPARATOR
                . 'design'
                . DIRECTORY_SEPARATOR
                . 'i'
                . DIRECTORY_SEPARATOR
                . $photo_tmp;
            $dest = $uploads_dir . $dest_folder . $photo_tmp;
            if (!copy($source, $dest)) {
                continue;
            }
            $line = Line::findOne(['name' => $line_name]);
            if (is_null($line)) {
                continue;
            }
            $line->photo_tmp = $photo_tmp;
            $line->photo_name = $photo_tmp;
            $line->save();
        }
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_line_photo_to_upload', '{{%line}}');
        $this->dropColumn('{{%line}}', 'photo_id');

        $this->update(
            '{{%line}}',
            ['name' => 'Кухня'],
            ['name' => 'Кухонные смесители']
        );
        $this->update(
            '{{%line}}',
            ['name' => 'Ванная комната'],
            ['name' => 'Ванные комнаты']
        );
    }
}
