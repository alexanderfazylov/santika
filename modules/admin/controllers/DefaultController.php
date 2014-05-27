<?php

namespace app\modules\admin\controllers;

use app\components\UploadHandler;
use app\models\Upload;
use app\modules\admin\components\AdminController;
use Yii;
use yii\web\UploadedFile;

class DefaultController extends AdminController
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
            'upload_url' => '/admin/default/tmp-file-show?file=',
            /**
             * @TODO удаление загруженного файла, мульти загрузка
             *  'script_url' => '/admin/default/file-upload',
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

    public function actionFileShow($file)
    {
        $path = addslashes(Upload::getUploadsPath() . $file);
        $imginfo = getimagesize($path);
        header("Content-type: {$imginfo['mime']}");
        readfile($path);
    }
}
