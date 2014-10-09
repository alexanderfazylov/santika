<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 04.06.14
 * Time: 10:11
 * @var Line $line
 * @var Category $category
 * @var Product $product
 * @var Product $prev_product
 * @var Product $next_product
 * @var Product[] $other_products
 * @var PriceProduct $price_product
 * @var integer $color_id
 */
use app\models\Category;
use app\models\Line;
use app\models\PriceProduct;
use app\models\Product;
use app\models\Upload;
use dosamigos\gallery\Carousel;
use yii\helpers\Html;


$this->title = 'Товар ' . $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/catalog']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-product">
    <div class="b-navigation">
        <a href="/catalog" class="back">Вернуться назад<span>в каталог</span></a>
        <?php
        if (!is_null($next_product)) {
            $next = Html::tag('span', $next_product->name);
            echo Html::a('Следующий товар' . $next, $next_product->canonical, ['class' => 'next']);
        }
        ?>
        <?php
        if (!is_null($prev_product)) {
            $prev = Html::tag('span', $prev_product->name);
            echo Html::a('Предыдущий товар' . $prev, $prev_product->canonical, ['class' => 'prev']);
        }
        ?>
    </div>

    <div class="blueimp-gallery-container">
        <?php
        $items = [];
        foreach ($product->photoGalleries as $photo_gallery) {
            $items[] = [
//                'title' => $product->name,
                'href' => $photo_gallery->upload->getFileShowUrl(Upload::SIZE_SQUARE_510),
            ];
        }
        ?>
        <?=
        Carousel::widget([
            'items' => $items,
            'json' => true,
            'clientOptions' => [

            ]
        ]);?>
    </div>

    <div class="b-product">
        <div class="b-product__description">
            <div class="b-product__name">
                <?= $product->name; ?>
                <br>
                <span>
                <?= $product->shop->name; ?>
                <?= !empty($product->collection) ? $product->collection->name : ''; ?>
                    </span>
            </div>

            <div class="b-product__article"> <?= 'ART. ' . $product->article; ?></div>
            <div class="b-product__size">
                <label>Д х Ш х В:</label>
                <span><?= $product->getLwh(); ?></span>
            </div>
            <div class="b-product__color">
                <label>Цвет:</label>
                <span>
                    <?php foreach ($product->productColors as $product_color) : ?>
                        <?php ?>
                        <img
                            src="<?= $product_color->color->getFileShowUrl(Upload::SIZE_SQUARE_245) ?>"> <?= $product_color->color->article ?> <?= $product_color->color->name ?>
                        <br>
                    <?php endforeach; ?>
                    <?php //$colors = ArrayHelper::map($product->productColors, 'color_id', 'color.name'); ?>
                    <?php
                    //                    echo Html::dropDownList('color_id', $color_id, $colors, ['id' => 'product-color_id', 'class' => 'form-control', 'prompt' => 'Без покрытия',
                    //                        'onChange' => new JsExpression('
                    //                window.location = "' . $product->createUrlByLine($line->url) . '?color_id=" + $(this).val();
                    //')
                    //                    ]);
                    ?>
                </span>
            </div>
        </div>

        <div class="b-product__price">
            Стоимость<br>
            <span class="new"><?= !is_null($price_product) ? $price_product->cost_rub . ' р.' : '' ?></span>
            <?php
            /**
             * @TODO сделать предыдущую цену
             */
            ?>
            <span class="old">47 990.00 р.</span>
        </div>
        <div class="b-links">
            <a href="#note" class="pop">Добавить в блокнот</a>
            <a href="" class="spec">Спецификация</a>
            <a href="" class="spec">Инструкция по установке</a>
            <a href="">Связаться с менеджером</a>
        </div>
    </div>
    <?php if ($product->getProductInstallationProducts()->count()): ?>
        <div class="b-components">
            <div class="title">Монтажные элементы<br>и комплектующие</div>
            <ul>
                <?php foreach ($product->productInstallationProducts as $pip): ?>
                    <li>
                        <?php $installation_product = $pip->installationProduct; ?>
                        <a href="<?= $installation_product->canonical; ?> "><img src="<?= $installation_product->photo->getFileShowUrl(Upload::SIZE_SQUARE_245); ?>"/></a>

                        <div class="descr">
                            <a href="<?= $installation_product->canonical; ?> "><?= $installation_product->name; ?></a>
                            <span class="art">Art. <?= $installation_product->article; ?></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="b-producer">
        <div class="b-producer__wrap">
            <div class="b-producer__image">
                <img src="/images/producer1.jpg" alt=""/>
            </div>

            <div class="b-producer__descr">
                <div class="title">Goccia</div>
                <p>Gessi унитаз настенного монтажа (6 литров) из белой керамики Bianco Europa, интегрированный
                    сифон. Сиденье Soft-close (плавное опускание), с боковыми заглушками отверстий, включены
                    в 031 отделке, 147 отделка доступна по запросу.</p>

                <p>Сантехника для ванной Gessi предлагает вам уникальный ассортимент дизайнерских
                    продуктов для эстетов. Современные решения для ванной на все "случаи жизни".</p>
            </div>
        </div>
        <img src="/images/producer2.jpg" class="img" alt=""/>
        <img src="/images/producer3.jpg" class="img" alt=""/>
        <img src="/images/producer4.jpg" class="img" alt=""/>
    </div>
    <?php if (count($other_products)): ?>
        <div class="b-carusel">
            <div class="title">
                Сопутствующие товары
            </div>
            <div class="gallery">
                <ul>
                    <?php foreach ($other_products as $other_product) : ?>
                        <li>
                            <div class="image">
                                <img src="<?= $other_product->photo->getFileShowUrl(Upload::SIZE_SQUARE_245); ?>"
                                     alt=""/>
                            </div>
                            <div class="descr">
                                <span><?= 'ART. ' . $other_product->article; ?></span>
                                <?= $other_product->description; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="nav">
                <button class="prev"></button>
                <button class="next"></button>
            </div>
        </div>
    <?php endif; ?>
    <?= $this->render('/site/_services'); ?>

</div>