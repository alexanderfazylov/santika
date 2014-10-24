<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\ShowWith $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Show With',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Show Withs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="show-with-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
