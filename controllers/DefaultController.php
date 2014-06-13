<?php

namespace app\controllers;

use app\components\UploadHandler;
use app\models\Upload;
use Yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionFileUpload()
    {
        $options = [
            'upload_dir' => Upload::getTmpUploadsPath(),
            'param_name' => 'files',
            'upload_url' => '/default/tmp-file-show?file=',
            /**
             * @TODO удаление загруженного файла, мульти загрузка
             *  'script_url' => '/default/file-upload',
             */
        ];
        /**
         * @TODO сделать свой uploadHandler
         */

        $upload_handler = new UploadHandler($options, false);
        $result = $upload_handler->post(false);
        Yii::$app->response->format = 'json';
        return $result;
    }

    public function actionTmpFileShow($file)
    {
        $path = addslashes(Upload::getTmpUploadsPath() . $file);
        $imginfo = getimagesize($path);
        header("Content-type: {$imginfo['mime']}");
        readfile($path);
    }

    public function actionFileShow($id, $thumbnail = false)
    {
        $upload = Upload::findOne($id);
        $path = null;
        if (!is_null($upload)) {
            $path = $upload->getFilePath($thumbnail);
        }
        if (is_null($path) || !file_exists($path) || !is_file($path)) {
            //если нет файла, то вернем картинку по умолчанию
            $path = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'empty.jpeg';
        }
        $imginfo = getimagesize($path);
        header("Content-type: {$imginfo['mime']}");
        readfile($path);
    }
}
