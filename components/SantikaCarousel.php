<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 17.10.14
 * Time: 16:26
 */

namespace app\components;


use dosamigos\gallery\Carousel;
use yii\helpers\Html;

class SantikaCarousel extends Carousel
{

    /**
     * @return string the carousel template
     */
    public function renderTemplate()
    {
        $template[] = '<div class="slides"></div>';
        $template[] = '<h3 class="title"></h3>';
        $template[] = '<a class="prev">Далее</a>';
        $template[] = '<a class="next">Назад</a>';
        $template[] = '<a class="play-pause"></a>';
        $template[] = '<ol class="indicator"></ol>';

        return Html::tag('div', implode("\n", $template), $this->templateOptions);
    }
} 