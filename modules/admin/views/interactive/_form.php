<?php

use app\models\Line;
use app\models\Shop;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Interactive $model
 * @var yii\widgets\ActiveForm $form
 * @var int $shop_id
 */
?>

<div class="interactive-form">

    <?php $shops_array = ArrayHelper::map(Shop::find()->all(), 'id', 'name'); ?>
    <?php $lines_array = ArrayHelper::map(Line::find()->byShop($shop_id)->all(), 'id', 'name'); ?>

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <label>Салон</label>
        <?php echo Html::dropDownList('shop_id', $shop_id, ArrayHelper::map(Shop::find()->all(), 'id', 'name'), ['id' => 'interactive-shop_id', 'class' => 'form-control']) ?>
    </div>


    <?= $form->field($model, 'line_id')->dropDownList($lines_array, ['promt' => 'Выберите линию']); ?>

    <?php
    echo $this->render('/default/_file_upload.php', [
        'form' => $form,
        'model' => $model,
        'attribute' => 'upload',
        'accept' => 'image/*'
    ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
