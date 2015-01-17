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
use app\components\SantikaCarousel;
use app\models\Category;
use app\models\Line;
use app\models\PriceProduct;
use app\models\Product;
use app\models\Upload;
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
        SantikaCarousel::widget([
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
            <?php if ($lwhString = $product->getLwhString()): ?>
                <div class="b-product__size">
                    <label><?= $lwhString; ?></label>
                    <span><?= $product->getLwh(); ?></span>
                </div>
            <?php endif; ?>
            <?php if (!is_null($product->diameter)): ?>
                <div class="b-product__size">
                    <label>Диаметр:</label>
                    <span><?= $product->diameter; ?></span>
                </div>
            <?php endif; ?>
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
            <?php
            if (!empty($product->drawing_id)) {
                echo Html::a('Спецификация', $product->drawing->getFileShowUrl(), ['target' => '_blank']);
            } else {
                echo Html::a('Спецификация', '#', ['class' => 'spec']);
            } ?>
            <?php
            if (!empty($product->manual_id)) {
                echo Html::a('Инструкция по установке', $product->manual->getFileShowUrl(), ['target' => '_blank']);
            } else {
                echo Html::a('Инструкция по установке', '#', ['class' => 'spec']);
            } ?>
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
                        <?php $src = !empty($installation_product->photo_id) ? $installation_product->photo->getFileShowUrl(Upload::SIZE_SQUARE_245) : Upload::defaultFileUrl(Upload::SIZE_SQUARE_245) ?>
                        <a href="<?= $installation_product->canonical; ?> ">
                            <img src="<?= $src; ?>" alt=""/>
                        </a>

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
                <div class="title"><?= nl2br($product->collection->name); ?></div>
                <p><?= nl2br($product->description); ?></p>
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
                                <?php $src = !empty($other_product->photo_id) ? $other_product->photo->getFileShowUrl(Upload::SIZE_SQUARE_245) : Upload::defaultFileUrl(Upload::SIZE_SQUARE_245) ?>
                                <img src="<?= $src; ?>" alt=""/>
                            </div>
                            <div class="descr">
                                <span><?= 'ART. ' . $other_product->article; ?></span>
                                <?= $other_product->name; ?>
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