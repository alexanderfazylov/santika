<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\ProductSearch $searchModel
 */

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
  'modelClass' => 'Product',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'shop_name',
                'value' => 'shop.name'
            ],
            [
                'attribute' => 'collection_name',
                'value' => 'collection.name'
            ],
            [
                'attribute' => 'category_name',
                'value' => 'category.name'
            ],
            // 'coat_id',
            // 'drawing_id',
            // 'article',
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>