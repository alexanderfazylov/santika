<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\LineProduct $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
  'modelClass' => 'Line Product',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Line Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="line-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
