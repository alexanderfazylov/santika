<?php

use app\models\Interactive;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\InteractiveSearch $searchModel
 * @var int $type
 */

$this->title = 'Интерьерные фотографии ' . $searchModel->lcTextAlter();
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interactive-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?=
        Html::a('Создать интерьерную фотографию ' . $searchModel->lcText(), ['create', 'type' => $type], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'object_name',
                'value' => $type == Interactive::TYPE_LINE ? 'line.name' : 'collection.name'
            ],
            'upload.fileShowLink:raw',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {product} {update} {delete}',
                'buttons' => [
                    'product' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-picture"></span>', $url, [
                                'title' => 'Отметить товары',
                                'data-pjax' => '0',
                            ]);
                        },
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
