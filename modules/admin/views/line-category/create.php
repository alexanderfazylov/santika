<?php

use yii\helpers\Html;

/**
 * @var int $shop_id
 * @var yii\web\View $this
 * @var app\models\LineCategory $model
 */

$this->title ='Создание связи линия-категория';
$this->params['breadcrumbs'][] = ['label' => 'Связь линия-категория', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="line-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'shop_id' => $shop_id,
    ]) ?>

</div>
