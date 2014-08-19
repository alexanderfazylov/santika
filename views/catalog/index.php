<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 04.06.14
 * Time: 10:10
 * @var Line[] $lines
 * @var int $shop_id
 * @var yii\web\View $this
 */
use app\models\Category;
use app\models\Collection;
use app\models\Line;
use app\models\Product;
use app\models\Upload;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Каталог';
$this->params['breadcrumbs'][] = $this->title;
$categories = Category::find()->byShop($shop_id)->all();
$collections = Collection::find()->byShop($shop_id)->all();
?>
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

<div class="app-catalog">
    <div class="b-catalog__desription">
        <p>Gessi сантехника — это не только признанные среди профессионалов дизайнерские смесители, но и раковины, а с
            недавнего времени и дизайнерские ванны.
            Весь опыт инноваций и поиска своего пути в области оздоровительных процедур выразился в spa сантехнике и
            wellness решениях для частных интерьеров.</p>

        <p>Купить сантехнику и смесители Gessi вы можете с фирменной гарантией и доставкой в любой город России.</p>
    </div>
    <form method="get" action="<?= Url::to('/catalog/line') ?>">

        <div class="b-catalog__filter">
            <div class="b-catalog__filter__wrap">
                <div class="b-catalog__filter__select">
                    <?=
                    Html::dropDownList('line_url', null, ArrayHelper::map($lines, 'url', 'name'), [
                        'prompt' => 'Линия товаров',
                        'class' => 'form-control',
                        'id' => 'line_url',
                    ]); ?>
                </div>
                <div class="b-catalog__filter__select">

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
                <div class="b-catalog__filter__select">
                    <?=
                    Html::dropDownList('collection_url', null, ArrayHelper::map($collections, 'url', 'name'), [
                        'prompt' => 'Коллекция',
                        'class' => 'form-control',
                        'id' => 'collection_url'
                    ]); ?>
                </div>
                <div class="b-catalog__filter__select">
                    <div>
                        <input type="text" placeholder="Артикул">
                    </div>
                </div>
                <?=
                Html::a('Найти', '#', [
                    'type' => 'button',
                    'class' => 'btn',
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
        </div>

    </form>
    <?php foreach ($lines as $key => $line): ?>
        <a href="<?= $line->createUrl(); ?>"
           class="<?= ($key % 2 == 0) ? 'b-catalog__item--left' : 'b-catalog__item--right'; ?>">
            <div class="b-catalog__item__image">
                <?php $src = !empty($line->photo_id) ? $line->photo->getFileShowUrl(Upload::SIZE_RECTANGLE_600_450) : Upload::defaultFileUrl(Upload::SIZE_RECTANGLE_600_450) ?>
                <img src="<?= $src; ?>">
            </div>

            <div class="descr">
                <div class="title"><?= $line->name ?></div>
                <div class="text"><?= nl2br($line->description) ?></div>
            </div>
        </a>
    <?php endforeach; ?>

    <?= $this->render('/site/_services'); ?>

</div>