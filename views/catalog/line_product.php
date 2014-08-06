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


    <?php
    echo $this->render('/catalog/_line_product_menu', array(
        'categories' => $categories,
        'collections' => $collections,
    ));
    ?>

    <ul class="b-rzd__items">
        <?php $big_count = 0; ?>
        <?php $small_count = 0; ?>
        <?php foreach ($products as $key => $product): ?>
            <?php $src = !empty($product->photo_id) ? $product->photo->getFileShowUrl(true) : Upload::defaultFileUrl(true) ?>
            <?php
            //рисуем 4 маленьких и 2 больших товара
            if ($small_count == 4) {
                $big = true;
                $big_count++;
                if ($big_count == 2) {
                    $small_count = 0;
                }
            } else {
                $big = false;
                $big_count = 0;
                $small_count++;
            }
            ?>
            <li class="b-rzd__item <?= $big ? 'big' : '' ?>">
                <div class="image"> <?= Html::a(Html::img($src), $product->createUrlByLine($line->url)); ?></div>
                <div class="descr">
                    <span><?= 'Art. ' . $product->article; ?></span>
                    <?= $product->name; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>