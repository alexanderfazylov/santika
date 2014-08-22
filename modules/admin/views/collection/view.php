<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\models\Collection $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Коллекции', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'shop.name',
            'name',
            'parent.name',
            'description',
            'photo.fileShowLink:raw',
            'catalog_photo.fileShowLink:raw',
//            'sort',
            'url',
            'meta_title',
            'meta_description',
            'meta_keywords',
        ],
    ]) ?>

</div>
