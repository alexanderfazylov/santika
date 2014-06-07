<?php

namespace app\modules\admin\controllers;

use app\modules\admin\components\AdminController;
use Yii;

class DefaultController extends AdminController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
