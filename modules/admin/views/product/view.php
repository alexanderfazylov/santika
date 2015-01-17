<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\models\Product $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Фотогалерея', ['photo-gallery', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?=
        Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger ajax-delete',
        ]) ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'shop_id',
            'collection.name',
            'category.name',
//            'manual_id',
//            'productColors.color.name',
//            'drawing_id',
            'article',
            'photo.fileShowLink:raw',
            'manual.fileShowLink:raw',
            'drawing.fileShowLink:raw',
            'name',
            'description',
            'length',
            'width',
            'height',
            'diameter',
            'is_promotion',
            'is_published',
            'canonical',
            'url',
            'meta_title',
            'meta_description',
            'meta_keywords',
        ],
    ]) ?>

</div>
