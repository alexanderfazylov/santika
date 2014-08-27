<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 04.06.14
 * Time: 10:11
 * @var yii\web\View $this
 * @var Line $line
 * @var Category $category
 * @var Collection $collection
 * @var Product[] $products
 * @var Category[] $categories
 * @var string $category_url
 * @var Collection[] $collections
 * @var string $collection_url
 */
use app\models\Category;
use app\models\Collection;
use app\models\Line;
use app\models\Product;
use app\models\Upload;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$type = '';
$name = '';
if (!is_null($line)) {
    $type = 'Линия';
    $name = 'GESSI ' . $line->name;
} elseif (!is_null($collection)) {
    $type = 'Коллекция';
    $name = $collection->name;
} elseif (!is_null($category)) {
    $type = 'Категория';
    $name = $category->name;
}
$this->title = $type . ' ' . $name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/catalog']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="app-rzd">
    <div class="b-rzd__title">
        <img src="/images/rzd1.jpg" alt=""/>

        <div><?= $type ?> <span><?= $name ?></span></div>
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
                <?php $size = ($big ? Upload::SIZE_SQUARE_510 : Upload::SIZE_SQUARE_245); ?>
                <?php $src = !empty($product->photo_id) ? $product->photo->getFileShowUrl($size) : Upload::defaultFileUrl($size) ?>
                <?php $product_url = is_null($line) ? $product->canonical : $product->createUrlByLine($line->url) ?>
                <div class="image"> <?= Html::a(Html::img($src), $product_url); ?></div>
                <div class="descr">
                    <span><?= 'Art. ' . $product->article; ?></span>
                    <?= $product->name; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>