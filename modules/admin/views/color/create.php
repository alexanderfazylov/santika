<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Color $model
 */

$this->title = 'Создание покрытия';
$this->params['breadcrumbs'][] = ['label' => 'Покрытия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="color-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
