<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<div class="wrapper">

    <header class="header">
        <span class="logo"></span>
        <span class="dealer">Официальный дилер</span>
        <div class="b-top-bar">
            <a class="notepad" href="">Блокнот (21)</a>
            <a class="menu" href="">меню</a>
        </div>
    </header><!-- .header-->

    <div class="b-panel-r">
        <ul class="b-menu-right">
            <li><a href="">Компания</a></li>
            <li><a href="">проекты</a>
                <ul>
                    <li><a href="">Goccia</a></li>
                    <li><a href="">Rettangolo</a></li>
                    <li><a href="">Ovale</a></li>
                    <li><a href="">Riflessi e Trasparenze</a></li>
                    <li><a href="">Mimi</a></li>
                </ul>
            </li>
            <li><a href="">Линии</a></li>
            <li><a href="">Услуги и сервис</a></li>
            <li><a href="">Дизайнерам</a></li>
            <li><a href="">Регистрация</a></li>
        </ul>
        <div class="contacts">
            +7 (843) 524 71 76<br>
            info@gessi-promo.ru
        </div>
    </div>

    <main class="content container">
    <?= $content ?>
    </main><!-- .content -->

</div><!-- .wrapper -->

<footer class="footer">
    <div class="copy">© 2014 Gessi</div>
    <ul class="b-menu-foot">
        <li><a href="">О компании</a></li>
        <li><a href="">Услуги и сервис</a></li>
        <li><a href="">Коллекции</a></li>
        <li><a href="">Дизайнерам</a></li>
    </ul>
</footer><!-- .footer -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
