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
 */
use app\models\Category;
use app\models\Line;
use app\models\PriceProduct;
use app\models\Product;
use dosamigos\gallery\Carousel;
use yii\helpers\Html;
use yii\web\JsExpression;


$this->title = 'Товар ' . $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/catalog']];
$this->params['breadcrumbs'][] = ['label' => $line->name, 'url' => $line->createUrl()];
$this->params['breadcrumbs'][] = $this->title;
?>
    <h1><?= Html::encode($this->title) ?></h1>

<?php
if (!is_null($prev_product)) {
    $prev = Html::tag('span', 'Предыдущий товар') . '<br/>' . Html::tag('span', $prev_product->name);
    echo Html::a($prev, $prev_product->createUrlByLine($line->url));
}
?>

<?php
if (!is_null($next_product)) {
    $next = Html::tag('span', 'Следующий товар') . '<br/>' . Html::tag('span', $next_product->name);
    echo Html::a($next, $next_product->createUrlByLine($line->url));
}
?>

<?php
$items = [];
foreach ($product->photoGalleries as $photo_gallery) {
    $products = [];

    $items[] = [
        'title' => $product->name,
        'href' => $photo_gallery->upload->getFileShowUrl(),
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

    <div>
        <div>
            <?= $product->name; ?>
        </div>
        <div>
            <?= $product->shop->name; ?>
            <?= !empty($product->collection) ? $product->collection->name : ''; ?>
            <?= 'ART.' . $product->article; ?>
        </div>
        <div>
            <div>ДхШхВ<span class="product-lwh"><?= $product->getLwh(); ?></span></div>
            <div>Цвет<span class="product-color">
                    <?= !empty($product->color_id) ? $product->color->name : ''; ?>
                </span>
            </div>
        </div>
    </div>


    <div>
        <div> Стоимость</div>
        <div><?= !is_null($price_product) ? $price_product->cost_rub : '' ?></div>
        <div>* - цена указана в рублях</div>
    </div>


    <h2>Товары линии <?= $line->name ?></h2>
<?php
foreach ($other_products as $other_product) {
    echo Html::a(Html::img($other_product->photo->getFileShowUrl(true)), $other_product->createUrlByLine($line->url));
}
?>