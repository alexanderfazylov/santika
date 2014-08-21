<?php
use app\models\Collection;
use app\models\Line;
use app\models\Shop;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);

$shop_id = Shop::getIdFromUrl();
$lines = Line::find()->byShop($shop_id)->all();
//$collections = Collection::find()->byShop($shop_id)->all();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?php echo Html::csrfMetaTags(); ?>
</head>
<body>

<?php $this->beginBody() ?>
<div class="wrapper">

    <header class="header">
        <a href="/">
            <span class="logo"></span>
            <span class="dealer">Официальный дилер</span>
        </a>

        <div class="b-top-bar">
            <a class="notepad" href="">Блокнот (21)</a>
            <a class="menu" href="">меню</a>
        </div>
    </header>
    <!-- .header-->

    <div class="b-panel-r">
        <ul class="b-menu-right">
            <li><a href="">Компания</a></li>
            <li><a href="">проекты</a>
<!--                <ul>-->
<!--                    --><?php //foreach ($collections as $collection): ?>
<!--                        <li><a href="--><?php //= $collection->createUrl(); ?><!--">--><?php //= $collection->name; ?><!--</a></li>-->
<!--                    --><?php //endforeach; ?>
<!--                </ul>-->
            </li>
            <li><a href="<?= Url::to('/catalog')?>">Каталог</a>
                <ul>
                    <?php foreach ($lines as $line): ?>
                        <li><a href="<?= $line->createUrl(); ?>"><?= $line->name; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <li><a href="">Услуги и сервис</a></li>
            <li><a href="">Дизайнерам</a></li>
            <li><a href="">Регистрация</a></li>
        </ul>
        <div class="contacts">
            +7 (843) 524 71 76<br>
            info@gessi-promo.ru
        </div>
    </div>

    <main class="content <!--container-->">
        <?= $content ?>
    </main>
    <!-- .content -->

</div>
<!-- .wrapper -->

<footer class="footer">
    <div class="copy">© 2014 Gessi</div>
    <ul class="b-menu-foot">
        <li><a href="">О компании</a></li>
        <li><a href="">Услуги и сервис</a></li>
        <li><a href="">Коллекции</a></li>
        <li><a href="">Дизайнерам</a></li>
    </ul>
</footer>
<!-- .footer -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
