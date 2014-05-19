<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
Доступные действия:
<br/>
<?= Html::a('Линии', Url::to('/admin/line')); ?>
<br/>
<?= Html::a('Коллекции', Url::to('/admin/collection')); ?>
<br/>
<?= Html::a('Категории', Url::to('/admin/category')); ?>
<br/>
<?= Html::a('Линия Категория', Url::to('/admin/linecategory')); ?>
