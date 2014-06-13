<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\models\Price $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Прайс листы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php if (Yii::$app->getSession()->hasFlash('importPrice')): ?>
        <div class="alert alert-info"><?= Yii::$app->getSession()->getFlash('importPrice'); ?></div>
    <?php endif; ?>
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'shop.name',
            'start_date',
            'typeText',
        ],
    ]) ?>

</div>
