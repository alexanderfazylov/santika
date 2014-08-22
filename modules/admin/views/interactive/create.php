<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Interactive $model
 * @var int $shop_id
 */

$this->title = 'Создание интерьерной фотографии ' . $model->lcText();
$this->params['breadcrumbs'][] = ['label' => 'Интерьерные фотографии ' . $model->lcTextAlter(), 'url' => ['index', 'type' => $model->type]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interactive-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'shop_id' => $shop_id,
    ]) ?>

</div>
