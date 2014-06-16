<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\LineSearch $searchModel
 */

$this->title = 'Линии';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="line-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать линию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
//            'shop_id',
            'name',
            'description',
//            'sort',
            'url',
            // 'meta_title',
            // 'meta_description',
            // 'meta_keywords',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('yii', 'Delete'),
                                'class' => 'custom-delete',
                                'data-pjax' => '0',
                            ]);
                        }
                ]
            ],
        ],
    ]); ?>

</div>
