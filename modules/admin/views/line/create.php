<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Line $model
 */
$this->title = 'Создание линии';
$this->params['breadcrumbs'][] = ['label' => 'Линии', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="line-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
