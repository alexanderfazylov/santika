<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 04.06.14
 * Time: 10:10
 * @var Line[] $lines
 * @var Collection[] $collections
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
$search_categories = Category::find()->byShop($shop_id)->all();
$search_collections = Collection::find()->byShop($shop_id)->all();
?>

<div class="app-catalog">
    <div class="b-catalog__desription">
        <p>Gessi сантехника — это не только признанные среди профессионалов дизайнерские смесители, но и раковины, а с
            недавнего времени и дизайнерские ванны.
            Весь опыт инноваций и поиска своего пути в области оздоровительных процедур выразился в spa сантехнике и
            wellness решениях для частных интерьеров.</p>

        <p>Купить сантехнику и смесители Gessi вы можете с фирменной гарантией и доставкой в любой город России.</p>
    </div>
    <form method="get" action="<?= Url::to('/catalog/filter') ?>">

        <div class="b-catalog__filter">
            <div class="b-catalog__filter__wrap">
                <div class="b-catalog__filter__select">
                    <?=
                    Html::dropDownList('line_url', null, ArrayHelper::map($lines, 'url', 'name'), [
                        'prompt' => 'Все линии',
                        'class' => 'form-control',
                        'id' => 'line_url',
                    ]); ?>
                </div>
                <div class="b-catalog__filter__select">

                    <?=
                    /**
                     * @TODO подгрузка данных ajax-ом по линии
                     */
                    Html::dropDownList('category_url', null, ArrayHelper::map($search_categories, 'url', 'name'), [
                        'prompt' => 'Все категории',
                        'class' => 'form-control',
                        'id' => 'category_url'
                    ]); ?>
                </div>
                <div class="b-catalog__filter__select">
                    <?=
                    Html::dropDownList('collection_url', null, ArrayHelper::map($search_collections, 'url', 'name'), [
                        'prompt' => 'Все коллекции',
                        'class' => 'form-control',
                        'id' => 'collection_url'
                    ]); ?>
                </div>
                <div class="b-catalog__filter__select">
                    <div>
                        <input name="article" type="text" placeholder="Артикул">
                    </div>
                </div>
                <?=
                Html::button('Найти',['class' => 'btn']);
                ?>
            </div>
        </div>

    </form>
    <?php foreach ($lines as $key => $line): ?>
        <a href="<?= $line->createUrl(); ?>"
           class="<?= ($key % 2 == 0) ? 'b-catalog__item--left' : 'b-catalog__item--right'; ?>">
            <div class="b-catalog__item__image">
                <?php $src = !empty($line->catalog_photo_id) ? $line->catalog_photo->getFileShowUrl(Upload::SIZE_RECTANGLE_600_450) : Upload::defaultFileUrl(Upload::SIZE_RECTANGLE_600_450) ?>
                <img src="<?= $src; ?>">
            </div>

            <div class="descr">
                <div class="title"><?= $line->name ?></div>
                <div class="text"><?= nl2br($line->description) ?></div>
            </div>
        </a>
    <?php endforeach; ?>
    <?php foreach ($collections as $key => $collection): ?>
        <a href="<?= $collection->createUrl(); ?>"
           class="<?= ($key % 2 == 0) ? 'b-catalog__item--left' : 'b-catalog__item--right'; ?>">
            <div class="b-catalog__item__image">
                <?php $src = !empty($collection->catalog_photo_id) ? $collection->catalog_photo->getFileShowUrl(Upload::SIZE_RECTANGLE_600_450) : Upload::defaultFileUrl(Upload::SIZE_RECTANGLE_600_450) ?>
                <img src="<?= $src; ?>">
            </div>

            <div class="descr">
                <div class="title"><?= $collection->name ?></div>
                <div class="text"><?= nl2br($collection->description) ?></div>
            </div>
        </a>
    <?php endforeach; ?>

    <?= $this->render('/site/_services'); ?>

</div>