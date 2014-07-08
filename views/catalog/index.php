<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 04.06.14
 * Time: 10:10
 * @var Line[] $line
 * @var int $shop_id
 */
use app\models\Category;
use app\models\Collection;
use app\models\Line;
use app\models\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Каталог';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<?php
$lines = Line::find()->byShop($shop_id)->all();
$categories = Category::find()->byShop($shop_id)->all();
$collections = Collection::find()->byShop($shop_id)->all();
?>
<div class="row">
    <form method="get" action="<?= Url::to('/catalog/line') ?>">
        <div class="col-md-2">
            <?=
            Html::dropDownList('line_url', null, ArrayHelper::map($lines, 'url', 'name'), [
                'prompt' => 'Линия',
                'class' => 'form-control',
                'id' => 'line_url'
            ]); ?>
        </div>
        <div class="col-md-2">
            <?=
            Html::dropDownList('collection_url', null, ArrayHelper::map($collections, 'url', 'name'), [
                'prompt' => 'Коллекция',
                'class' => 'form-control',
                'id' => 'collection_url'
            ]); ?>
        </div>
        <div class="col-md-2">
            <?=
            /**
             * @TODO подгрузка данных ajax-ом по линии
             */
            Html::dropDownList('category_url', null, ArrayHelper::map($categories, 'url', 'name'), [
                'prompt' => 'Категория',
                'class' => 'form-control',
                'id' => 'category_url'
            ]); ?>
        </div>
        <div class="col-md-2">
        </div>
        <div class="col-md-2">
            <?=
            Html::button('Показать', [
                'type' => 'button',
                'onClick' => '
        var line_url= $("#line_url").val();
        if(line_url ==""){
            return false;
        }
        var category_url= $("#category_url").val();
        var collection_url = $("#collection_url").val();
        window.location = "' . Url::to(['/catalog/line/']) . '/" + line_url + "' . '?category_url=" + category_url + "&collection_url=" + collection_url;
            ']) ?>
        </div>
    </form>
</div>

<?php foreach ($lines as $key => $line): ?>
    <a href="<?= $line->createUrl(); ?>"
       class="<?= ($key % 2 == 0) ? 'b-catalog__item--left' : 'b-catalog__item--right'; ?>">
        <img src="i/cat1.jpg">

        <div class="descr">
            <div class="title"><?= $line->name ?></div>
            <div class="text">Дизайнер: Prospero Rasulo</div>
            <div class="text">Революционный дизайн, сочетающий совершенные пропорции
                с чистыми формами.
            </div>
        </div>
    </a>
<?php endforeach; ?>

<div style="display: none;">
    <?php
    /**
     * @TODO УДАЛИТЬ
     */
    $line = Line::find()->one();
    $collection = Collection::find()->one();
    $category = Category::find()->one();
    $product = Product::find()->one();
    ?>
    <label>Url examples</label><br/>
    <label>каталог</label> <?= Url::to(['/catalog']); ?><br/>
    <label>линия</label> <?= Url::to(['/catalog/line', 'url' => $line->url]); ?><br/>
    <label>коллекция</label> <?= Url::to(['/catalog/collection/', 'url' => $collection->url]); ?><br/>
    <label>категория</label> <?= Url::toRoute(['/catalog/category', 'line_url' => $line->url, 'url' => $category->url]); ?>
    <br/>
    <label>товар без
        категории</label> <?= Url::toRoute(['/catalog/product', 'line_url' => $line->url, 'url' => $product->url]); ?>
    <br/>
    <label>товара с
        категорией</label> <?= Url::toRoute(['/catalog/product', 'line_url' => $line->url, 'category_url' => $category->url, 'url' => $product->url]); ?>
    <br/>


</div>