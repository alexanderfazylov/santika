<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Price $model
 */

$this->title = 'Создание прайс листа';
$this->params['breadcrumbs'][] = ['label' => 'Прайс листы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
