<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\PriceProduct $model
 */

$this->title = 'Создание стоимости товара';
$this->params['breadcrumbs'][] = ['label' => 'Стоимость товаров', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
