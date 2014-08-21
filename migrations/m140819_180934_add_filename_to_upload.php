<?php

use app\components\UploadHandler;
use app\models\Upload;
use yii\db\Schema;
use yii\db\Migration;

class m140819_180934_add_filename_to_upload extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%upload}}', 'file_name', Schema::TYPE_STRING . '(255) NULL');

        $uploads_dir = Upload::getUploadsPath();
        foreach (Upload::getAllTypes() as $type_id) {
            foreach (Upload::getAllSizes() as $size) {
                $type = Upload::getDirByType($type_id);
                $dir = $uploads_dir . $type . DIRECTORY_SEPARATOR;
                if (!file_exists($dir) && !is_dir($dir)) {
                    mkdir($dir, 0777);
                }
                $dir .= $size;
                if (!file_exists($dir) && !is_dir($dir)) {
                    mkdir($dir, 0777);
                }
            }
        }

        $_SERVER['SERVER_NAME'] = '';
        $_SERVER['SERVER_PORT'] = '';
        $uploads = Upload::find()->all();
        foreach ($uploads as $upload) {
            $path = explode('\\', $upload->path);
            $path = explode('/', end($path));
            $upload->file_name = end($path);
            $upload->save();
            if ($upload->isFileExist()) {
                $options = [
                    'upload_dir' => $uploads_dir . Upload::getUploadsPathByType($upload->type),
                    'no_cache' => true,
                ];
                $upload_handler = new UploadHandler($options, false);
                $upload_handler->recreate_images($upload->file_name);
            }
        }

    }

    public function safeDown()
    {
        $this->dropColumn('{{%upload}}', 'file_name');
    }
}
