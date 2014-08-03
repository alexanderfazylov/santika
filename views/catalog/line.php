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
use app\models\Upload;
use dosamigos\gallery\Carousel;
use newerton\fancybox\FancyBoxAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\JsExpression;

FancyBoxAsset::register($this);

$this->title = 'Линия ' . $line->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/catalog']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-collection">

    <?php
    $items = [];
    foreach ($intaractives as $intaractive) {
        /**
         * @TODO подтягивать инфу о товарах через ajax
         */
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
                'price' => '100 р.',
            ];
        }


        $items[] = [
//            'title' => $intaractive->name,
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
    <!--    <div class="product-info">-->
    <!--        <label>Всплывающее окно с информацией о товаре</label>-->
    <!---->
    <!--        <div>-->
    <!--            <a href="" class="product-link">-->
    <!--                <img class="product-photo" src="--><?php //= Upload::defaultFileUrl(1); ?><!--"/>-->
    <!--            </a></div>-->
    <!--        <div>-->
    <!--            <div class="product-name"></div>-->
    <!--            <div>ART.<span class="product-article"></span></div>-->
    <!--            <div class=""></div>-->
    <!--            <div>ДхШхВ<span class="product-lwh"></span></div>-->
    <!--            <div>Цвет<span class="product-color"></span></div>-->
    <!--            <div class="product-description"></div>-->
    <!--        </div>-->
    <!--    </div>-->

    <!--    <div id="slideshowWrapper" class="slider">-->
    <!--        <img src="/images/collection1.jpg" class="tst">-->
    <!--        <ul id="slideshow">-->
    <!--            <li>-->
    <!--                <img src="/images/collection1.jpg" alt=""/>-->
    <!--                <a href="#quick" class="pop" style="left: 35%; top:45%"></a>-->
    <!--            </li>-->
    <!--            <li>-->
    <!--                <img src="/images/producer1.jpg" alt=""/>-->
    <!--                <a href="#quick" class="pop" style="left: 55%; top:55%"></a>-->
    <!--            </li>-->
    <!--        </ul>-->
    <!--    </div>-->

    <div class="b-collection__descr">
        <div class="title"><?= $line->name; ?></div>
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
            <a href="<?= Url::to(['/catalog/line-product/', 'line_url' => $line->url]); ?>" class="btn">Перейти к
                коллекции ??? линии</a>
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
            <div><span>Д х Ш х В:</span><span class="product-lwh"></span></div>
            <div><span>Цвет:</span> <span class="product-color"></span></div>
            <div><span>Страна:</span> ????</div>
            <div><span>Монтаж:</span> ????</div>
        </div>
        <div class="text product-description"></div>
        <div class="price">Стоимость<span class="product-price">???</span></div>
        <a href="" class="add product-link">Дополнительная информация</a>
    </div>
</div>