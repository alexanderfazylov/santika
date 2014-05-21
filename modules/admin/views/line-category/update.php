<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\LineCategory $model
 */

$this->title = 'Редактирование '. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Line Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="line-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'shop_id' => $shop_id,
    ]) ?>

</div>
