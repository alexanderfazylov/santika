<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Shop $model
 */

$this->title = 'Создатьсалон';
$this->params['breadcrumbs'][] = ['label' => 'Салоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
