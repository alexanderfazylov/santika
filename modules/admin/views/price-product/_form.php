<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\PriceProduct $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="price-product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'price_id')->textInput() ?>

    <?= $form->field($model, 'product_id')->textInput() ?>

    <?= $form->field($model, 'cost_eur')->textInput() ?>

    <?= $form->field($model, 'cost_rub')->textInput(['maxlength' => 10]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
