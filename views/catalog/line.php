<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 04.06.14
 * Time: 10:11
 * @var Line $line
 * @var Interactive[] $intaractives
 */
use app\models\Interactive;
use app\models\Line;
use dosamigos\gallery\Carousel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\JsExpression;

$this->title = 'Линия ' . $line->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/catalog']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<a href="<?= Url::to(['/catalog/line-product/', 'line_url' => $line->url]); ?>">Все товары</a>
<?php
$items = [];
foreach ($intaractives as $intaractive) {
    $products = [];
    foreach ($intaractive->interactiveProducts as $ip) {
        $products[] = [
            'id' => $ip->id,
            'product_id' => $ip->product_id,
            'left_percent' => $ip->left,
            'top_percent' => $ip->top,
            'name' => $ip->product->name,
            'description' => $ip->product->description,
            'article' => $ip->product->article,
            'lwh' => $ip->product->getLwh(),
            'color' => !empty($ip->product->color_id) ? $ip->product->color->name : '',
            'photo' => !empty($ip->product->photo) ? $ip->product->photo->getFileShowUrl(true) : '',
            'link' => $ip->product->createUrlByLine($line->url),
        ];
    }


    $items[] = [
        'title' => $intaractive->name,
        'href' => $intaractive->upload->getFileShowUrl(),
        'products' => $products
    ];
}

?>
<?=
Carousel::widget([
    'items' => $items,
    'json' => true,
    'clientOptions' => [
        'onopened' => new JsExpression('
    function () {
        $.each(this.list,function(index, list_element){
            $.each(list_element.products, function(ind, product) {
                InteractivePoint(product, index);
            });
        });
    } ')
    ]
]);?>
<div class="product-info">
    <label>Всплывающее окно с информацией о товаре</label>

    <div>
        <a href="" class="product-link">
            <img class="product-photo" src="/admin/default/file-show?id=45&thumbnail=1"/>
        </a></div>
    <div>
        <div class="product-name"></div>
        <div>ART.<span class="product-article"></span></div>
        <div class=""></div>
        <div>ДхШхВ<span class="product-lwh"></span></div>
        <div>Цвет<span class="product-color"></span></div>
        <div class="product-description"></div>
    </div>
</div>


<script>

</script>
