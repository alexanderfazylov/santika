<?php

namespace app\modules\admin\controllers;

use app\components\UploadHandler;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionFileUpload()
    {
        $upload_dir = '/tmp/santika';
        if (!file_exists($upload_dir) && !is_dir($upload_dir)) {
            mkdir($upload_dir, 0777);
        }

        $options = [
            'upload_dir' => $upload_dir
        ];
        $upload_handler = new UploadHandler();
    }
}
