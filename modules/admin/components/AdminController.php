<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 27.05.14
 * Time: 10:22
 */

namespace app\modules\admin\components;


use yii\web\Controller;

class AdminController extends Controller
{
    public function beforeAction($action)
    {
        $this->getView()->params['breadcrumbs'][] = ['label' => 'Панель управления', 'url' => ['/admin']];
        return \yii\base\Controller::beforeAction($action);
    }
} 