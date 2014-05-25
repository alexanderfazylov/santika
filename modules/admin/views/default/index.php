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
        ['label' => 'Связь Линия Категория', 'url' => ['/admin/line-category']],
        ['label' => 'Связь Линия Товар', 'url' => ['/admin/line-product']],
        ['label' => 'Покрытия', 'url' => ['/admin/color']],
        ['label' => 'Прайс листы', 'url' => ['/admin/price']],
        ['label' => 'Стоимость товаров', 'url' => ['/admin/price-product/product', 'price_id' => 1]],
    ]
]);