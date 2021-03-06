<?php

use app\models\Shop;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Line $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="line-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'shop_id')->dropDownList(Shop::listData()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => 255]) ?>

    <?= $form->field($model, 'left_description')->textarea(['maxlength' => 1000]) ?>

    <?= $form->field($model, 'right_description')->textarea(['maxlength' => 1000]) ?>

    <!--    --><?php //= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>

    <?php
    echo $this->render('/default/_file_upload.php', [
        'form' => $form,
        'model' => $model,
        'attribute' => 'photo',
        'accept' => 'image/*'
    ]);
    ?>

    <?php
    echo $this->render('/default/_file_upload.php', [
        'form' => $form,
        'model' => $model,
        'attribute' => 'catalog_photo',
        'accept' => 'image/*'
    ]);
    ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
