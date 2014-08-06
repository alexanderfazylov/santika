<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/reset.css',
        'css/gessi.css',
        'css/demoStyleSheet.css',
        'css/jquery.selectbox.css',
        'css/site.css',
//        'css/jquery.fancybox-1.3.4.css',
    ];
    public $js = [
        'js/main.js',
        'js/fadeSlideShow.js',
        'js/jcarousellite.js',
        'js/jquery.selectbox-0.2.js',
//        'js/jquery.fancybox-1.3.4.pack.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
        'newerton\fancybox\FancyBoxAsset',
    ];

    public function init()
    {
        parent::init();
        /**
         * @TODO тут надо дополнительно подключить css файл нужной темы
         */
//        $this->css[] = 'css/gessi/style.css';
    }
}
