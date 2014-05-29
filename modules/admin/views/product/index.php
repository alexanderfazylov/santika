<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\ProductSearch $searchModel
 */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'article',
//            [
//                'attribute' => 'shop_name',
//                'value' => 'shop.name'
//            ],
            [
                'attribute' => 'collection_name',
                'value' => 'collection.name'
            ],
            [
                'attribute' => 'category_name',
                'value' => 'category.name'
            ],
            // 'color_id',
            // 'drawing_id',

            // 'series',
            // 'name',
            // 'description',
            // 'length',
            // 'width',
            // 'height',
            // 'is_promotion',
            // 'url:url',
            // 'meta_title',
            // 'meta_description',
            // 'meta_keywords',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {photo-gallery} {update} {delete}',
                'buttons' => [
                    'photo-gallery' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-picture"></span>', $url, [
                                'title' => 'Фотогалерея',
                                'data-pjax' => '0',
                            ]);
                        }
                ]

            ],
        ],
    ]); ?>

</div>
