<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 04.06.14
 * Time: 10:11
 * @var yii\web\View $this
 * @var Line $line
 * @var Product[] $products
 * @var Category[] $categories
 * @var int $category_url
 * @var Collection[] $collections
 * @var int $collection_url
 */
use app\models\Category;
use app\models\Collection;
use app\models\Line;
use app\models\Product;
use app\models\Upload;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Линия ' . $line->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/catalog']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="app-rzd">
    <div style="display: none;">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="row">
            <div class="col-md-4">
                <?= Html::a('Назад к интерьерным фото', $line->createUrl()); ?>
            </div>
            <div class="col-md-4">
                <?=
                Html::dropDownList('Product[category_url]', $category_url, ArrayHelper::map($categories, 'url', 'name'), [
                    'prompt' => 'Категории',
                    'class' => 'form-control',
                    'id' => 'product-category_url'
                ]); ?>
            </div>
            <div class="col-md-4">
                <?=
                Html::dropDownList('Product[collection_url]', $collection_url, ArrayHelper::map($collections, 'url', 'name'), [
                    'prompt' => 'Коллекции',
                    'class' => 'form-control',
                    'id' => 'product-collection_url'
                ]); ?>
            </div>
        </div>

        <?php
        $this->registerJs(
            '
            $(document).on("change", "#product-category_url, #product-collection_url", function () {
                var category_url= $("#product-category_url").val();
                var collection_url = $("#product-collection_url").val();
                window.location = "' . Url::to(['/catalog/line-product/', 'line_url' => $line->url]) . '?category_url=" + category_url + "&collection_url=" + collection_url;
    });'

        );
        ?>
    </div>
    <div class="b-rzd__title">
        <img src="/images/rzd1.jpg" alt=""/>

        <div>линия <span>Gessi Mimi</span></div>
    </div>

    <ul class="b-rzd__menu">
        <li class="parent">
            <a class="parent-link" href="">Категории</a>
            <ul class="child-items">
                <?php foreach ($categories as $category): ?>
                    <li class="child-item">
                        <?php if ($category->getChilds()->count() > 0): ?>
                            <span class="child-link" href="">
                               <div><img src="/images/tv1.jpg"></div>
                                <?= $category->name; ?>
                           </span>
                            <ul>
                                <?php foreach ($category->childs as $child): ?>
                                    <li>
                                        <a href="">
                                            <div><img src="/images/tv2.jpg"></div>
                                            <?= $child->name; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="nav">
                                <span></span>
                                <span class="active"></span>
                            </div>
                        <?php else: ?>
                            <a class="child-link" href="">
                                <div><img src="/images/tv2.jpg"></div>
                                <?= $category->name; ?>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="nav">
                <span></span>
                <span class="active"></span>
            </div>
        </li>
        <li class="parent">
            <a class="parent-link" href="">Коллекции</a>
            <ul class="child-items">
                <?php foreach ($collections as $collection): ?>
                    <li class="child-item">
                        <?php if ($collection->getChilds()->count() > 0): ?>
                            <span class="child-link" href="">
                               <div><img src="/images/tv1.jpg"></div>
                                <?= $collection->name; ?>
                           </span>
                            <ul>
                                <?php foreach ($collection->childs as $child): ?>
                                    <li>
                                        <a href="">
                                            <div><img src="/images/tv2.jpg"></div>
                                            <?= $child->name; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="nav">
                                <span></span>
                                <span class="active"></span>
                            </div>
                        <?php else: ?>
                            <a class="child-link" href="">
                                <div><img src="/images/tv2.jpg"></div>
                                <?= $collection->name; ?>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="nav">
                <span></span>
                <span class="active"></span>
            </div>
        </li>
    </ul>

    <ul class="b-rzd__items">
        <?php foreach ($products as $key => $product): ?>
            <?php $src = !empty($product->photo_id) ? $product->photo->getFileShowUrl(true) : Upload::defaultFileUrl(true) ?>

            <!--        <li class="b-rzd__item ">-->
            <li class="b-rzd__item <?= ($key > 0 && ($key % 4 == 0 || $key % 5 == 0)) ? 'big' : '' ?>">
                <div class="image"> <?= Html::a(Html::img($src), $product->createUrlByLine($line->url)); ?></div>
                <div class="descr">
                    <span><?= 'Art. ' . $product->article; ?></span>
                    <?= $product->name; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>