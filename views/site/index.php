<?php
/**
 * @var yii\web\View $this
 * @var Shop $shop
 * @var Product[] $products
 */
use app\models\Product;
use app\models\Shop;
use app\models\Upload;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $shop->name;
?>

<div class="app-index">

    <div id="slideshowWrapper" class="slider">
        <img src="/images/slide1.jpg" class="tst">
        <ul id="slideshow">
            <li>
                <img src="/images/slide1.jpg" alt=""/>

                <div class="descr">111 Gessi presents<br>
                    the new ispa<br>
                    philosophy
                </div>
            </li>
            <li>
                <img src="/images/slide2.jpg" alt=""/>

                <div class="descr">222 Gessi presents<br>
                    the new ispa<br>
                    philosophy
                </div>
            </li>
        </ul>
    </div>

    <div class="b-about">
        <img class="b-about__image" src="i/about1.jpg">

        <div class="b-about__wrap">
            <div class="b-about__title">О компании</div>
            <div class="b-about__descr">
                <?= $shop->short_about; ?>
            </div>
        </div>
        <div class="b-about__reg">
            Зарегистрируйтесь, чтобы получать уникальные предложения на покупку сантехники
            <a class="b-about__reg__link" href="">Связаться с менеджером</a>
        </div>
    </div>

    <?php if (count($products) > 0): ?>
        <div class="b-carusel">
            <div class="gallery">
                <ul>
                    <?php foreach ($products as $product): ?>
                        <?php $src = !empty($product->photo_id) ? $product->photo->getFileShowUrl(Upload::SIZE_SQUARE_245) : Upload::defaultFileUrl(Upload::SIZE_SQUARE_245) ?>
                        <li>
                            <div class="image"> <?= Html::a(Html::img($src), $product->canonical); ?></div>
                            <div class="descr">
                                <span><?= 'Art. ' . $product->article; ?></span>
                                <?= $product->name; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="nav">
                    <button class="prev"></button>
                    <button class="next"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="b-gessi">
        <img class="b-gessi__image" src="/i/gessi1.jpg">

        <div class="b-gessi__wrap">
            <div class="b-gessi__title">Сантехника Gessi</div>
            <div class="b-gessi__descr">Gessi сантехника — это не только признанные среди профессионалов
                дизайнерские смесители, но и раковины, а с недавнего времени
                и дизайнерские ванны. Весь опыт инноваций и поиска своего пути
                в области оздоровительных процедур выразился в spa сантехнике
                и wellness решениях для частных интерьеров.
            </div>
        </div>
    </div>

    <?= $this->render('/site/_services'); ?>

</div>
