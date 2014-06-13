<?php

use dosamigos\fileupload\FileUpload;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Color $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="color-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>


    <?php
    echo $this->render('/default/_file_upload.php', [
        'form' => $form,
        'model' => $model,
        'attribute' => 'upload',
        'accept' => 'image/*'
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
