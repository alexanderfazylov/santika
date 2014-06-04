<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 04.06.14
 * Time: 10:10
 * @var Line[] $line
 */
use app\models\Category;
use app\models\Collection;
use app\models\Line;
use app\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php

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


<?php foreach ($lines as $line): ?>
    <?= Html::a($line->name, $line->createUrl()); ?>
    <br/>
<?php endforeach; ?>