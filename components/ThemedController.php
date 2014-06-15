<?php

namespace app\components;

use Yii;
use yii\base\Theme;
use yii\web\Controller;

class ThemedController extends Controller
{
    public function beforeAction($action)
    {
        /**
         * @TODO тут надо будет определять какую тему подключить в зависимости от url
         */
        $theme = new Theme([
            'pathMap' => [
                '@app/views' => [
                    '@app/themes/gessi',
                ],
            ]
        ]);
        $this->view->theme = $theme;
        return true;
    }
}
