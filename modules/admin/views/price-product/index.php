<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\PriceProductSearch $searchModel
 */

$this->title = Yii::t('app', 'Price Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать стоимость товаров', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <p>
        <?= Html::a('Список цен', ['product', 'price_id' => 1], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'price_id',
            'product_id',
            'cost_eur',
            'cost_rub',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
