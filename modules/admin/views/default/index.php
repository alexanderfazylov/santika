<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

?>
Доступные разделы:

<?php
echo Menu::widget([
'items' => [
['label' => 'Линии', 'url' => ['/admin/line']],
['label' => 'Коллекции', 'url' => ['/admin/collection']],
['label' => 'Категории', 'url' => ['/admin/category']],
['label' => 'Товары', 'url' => ['/admin/product']],
['label' => 'Линия Категория', 'url' => ['/admin/line-category']],
['label' => 'Линия Продукт', 'url' => ['/admin/line-product']],
]
]);