<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 27.05.14
 * Time: 10:18
 */

namespace app\modules\admin\assets;


use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $publishOptions = ['forceCopy' => true];
    //папка где лежат js/css
    public $sourcePath = '@admin';
    public $css = [
        'css/admin.css',
    ];
    public $js = [
        'js/admin.js',
    ];
    //нужно, что бы подключить js/css проекта
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

} 