<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Line $model
 */

$this->title = 'Редактирование ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Линии', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="line-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
