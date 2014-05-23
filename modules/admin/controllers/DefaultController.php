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

    /*
     * @TODO перенести в другой контроллер
     */
    public function actionFileUpload()
    {
        $options = [
            'upload_dir' => Upload::getTmpUploadsPath(),
            'param_name' => 'files',
            'upload_url' => '/admin/default/tmp-file-download?file=',
        ];

        $upload_handler = new UploadHandler($options, false);
        $result = $upload_handler->post(false);

        Yii::$app->response->format = 'json';
        return $result;
    }

    public function actionTmpFileDownload($file)
    {
        $path = addslashes(Upload::getTmpUploadsPath() . $file);
        $imginfo = getimagesize($path);
        header("Content-type: {$imginfo['mime']}");
        readfile($path);
    }

    public function actionFileDownload($file)
    {
        $path = addslashes(Upload::getUploadsPath() . $file);
        $imginfo = getimagesize($path);
        header("Content-type: {$imginfo['mime']}");
        readfile($path);
    }
}
