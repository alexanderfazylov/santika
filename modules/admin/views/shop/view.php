<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\models\Shop $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Салоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <!--        --><?php
        //        echo Html::a('Удалить', ['delete', 'id' => $model->id], [
        //            'class' => 'btn btn-danger',
        //            'data' => [
        //                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
        //                'method' => 'post',
        //            ],
        //        ])
        //
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'short_about',
            'full_about',
        ],
    ]) ?>

</div>
