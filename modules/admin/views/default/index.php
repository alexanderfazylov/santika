<?php
use app\models\Interactive;
use yii\widgets\Menu;

?>
    Доступные разделы:

<?php
echo Menu::widget([
    'items' => [
//        ['label' => 'Салоны', 'url' => ['/admin/shop']],
        ['label' => 'Линии', 'url' => ['/admin/line']],
        ['label' => 'Коллекции', 'url' => ['/admin/collection']],
        ['label' => 'Категории', 'url' => ['/admin/category']],
        ['label' => 'Товары', 'url' => ['/admin/product']],
        ['label' => 'Связь Линия Категория', 'url' => ['/admin/line-category']],
        ['label' => 'Связь Линия Товар', 'url' => ['/admin/line-product']],
        ['label' => 'Покрытия', 'url' => ['/admin/color']],
        ['label' => 'Прайс листы', 'url' => ['/admin/price']],
        ['label' => 'Стоимость товаров', 'url' => ['/admin/price-product/product']],
        ['label' => 'Интерактивные фото линий', 'url' => ['/admin/interactive', 'type' => Interactive::TYPE_LINE]],
        ['label' => 'Интерактивные фото коллекций', 'url' => ['/admin/interactive', 'type' => Interactive::TYPE_COLLECTION]],
        ['label' => 'Отображать на странице', 'url' => ['/admin/show-with/product']],
        ['label' => 'Способ монтажа', 'url' => ['/admin/installation']],
    ]
]);