<?php

namespace app\modules\admin\controllers;

use app\components\UploadHandler;
use app\models\Upload;
use yii\web\Controller;
use Yii;
use yii\web\UploadedFile;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionFileUpload()
    {
        $options = [
            'upload_dir' => Upload::getTmpUploadsPath(),
        ];

        $uploaded_file = UploadedFile::getInstanceByName('Product[cover_id]');
        $upload_handler = new UploadHandler($options, false);
        $upload_handler->handle_file_upload();
    }
}
