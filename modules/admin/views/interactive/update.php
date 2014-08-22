<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Interactive $model
 * @var int $shop_id
 */

$this->title = 'Редактирование ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Интерьерные фотографии ' . $model->lcTextAlter(), 'url' => ['index', 'type' => $model->type]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="interactive-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'shop_id' => $shop_id,
    ]) ?>

</div>
