<?php

namespace app\modules\admin;

use yii\base\Action;
use yii\base\ActionEvent;
use Yii;
use yii\helpers\Url;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';

    public $layout = '@admin/views/layouts/main';

    public function init()
    {
        //задаем алиас для модуля админки
        Yii::setAlias('@admin', '@app/modules/admin');
        parent::init();
    }

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->loginRequired();
        }
        return \yii\base\Module::beforeAction($action);
    }


}
