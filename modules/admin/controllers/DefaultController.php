<?php

namespace app\modules\admin\controllers;

use app\components\UploadHandler;
use app\models\Upload;
use app\modules\admin\components\AdminController;
use Yii;

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

    public function actionFileShow($id, $thumbnail = false)
    {
        $upload = Upload::findOne($id);
        if ($thumbnail) {
            $file = $upload->thumbnail;
        } else {
            $file = $upload->path;
        }
        $path = addslashes(Upload::getUploadsPath() . $file);
        if (!file_exists($path) || !is_file($path)) {
            //если нет файла, то вернем картинку по умолчанию
            $path = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'default.jpeg';
        }
        $imginfo = getimagesize($path);
        header("Content-type: {$imginfo['mime']}");
        readfile($path);
    }
}
