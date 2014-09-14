<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 04.06.14
 * Time: 10:11
 * @var Line|Collection $model
 * @var Interactive[] $intaractives
 */
use app\models\Collection;
use app\models\Interactive;
use app\models\Line;
use app\models\Upload;
use dosamigos\gallery\Carousel;
use newerton\fancybox\FancyBoxAsset;
use yii\helpers\Url;
use yii\web\JsExpression;

FancyBoxAsset::register($this);

$this->title = ($type == Interactive::TYPE_LINE ? 'Линия ' : 'Коллекция ') . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/catalog']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-collection">

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
                'line_url' => ($type == Interactive::TYPE_LINE ? $model->url : null)
            ];
        }

        $items[] = [
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

    <div class="b-collection__descr">
        <div class="title"><?= $model->name; ?></div>
        <div class="text">
            ??? Специально для любителей утонченных вещей, фабрика Gessi выпустила серию
            аксессуаров для ванной комнаты, в число которых входят несколько смесителей.
            Любую деталь интерьера дизайнеры Gessi могут с легкостью переосмыслить
            и вынести ее под таким углом, что невольно восхищаешься их творческим
            потенциалом.
        </div>
        <div class="text right">
            ??? Смесители Gessi Mimi не исключение. Лишь посмотрев на один из них, можно прийти
            в восторг от красоты, вроде совсем непримечательной и обыденной, на первый взгляд
            вещи. Вся конструкция очень изящна, грани отточены и миниатюрны.
        </div>
        <div class="all">
            <?php if ($type == Interactive::TYPE_LINE): ?>
                <a href="<?= Url::to(['/catalog/filter/', 'line_url' => $model->url]); ?>" class="btn">Перейти к
                    линии</a>
            <?php else: ?>
                <a href="<?= Url::to(['filter', 'collection_url' => $model->url]); ?>"
                   class="btn">Перейти к коллекции</a>
            <?php endif ?>
        </div>
    </div>
</div>

<div class="hidden">
    <div id="quick" class="popup quick product-info ">
        <a href="" class="product-link">
            <img class="product-photo" src="<?= Upload::defaultFileUrl(1); ?>"/>
        </a>

        <div class="name product-name"></div>
        <div class="article">art. <span class="product-article"></span></div>
        <div class="descr">
            <div><span class="attribute">Д х Ш х В:</span><span class="product-lwh"></span></div>
            <div><span class="attribute">Цвет:</span> <span class="product-color"></span></div>
            <div><span class="attribute">Страна:</span> <span class="product-country"></span></div>
            <div><span class="attribute">Монтаж:</span> <span class="product-installation"></span></div>
        </div>
        <div class="text product-description"></div>
        <div class="price">Стоимость<span class="product-price">???</span></div>
        <a href="" class="add product-link">Дополнительная информация</a>
    </div>
</div>