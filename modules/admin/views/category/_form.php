<?php

use app\models\Category;
use app\models\Shop;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Category $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'shop_id')->dropDownList(ArrayHelper::map(Shop::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <!--    --><?php //= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>

    <?php
    /**
     * @TODO добавиьт проверку по shop_id и paranet_id != id
     */
    ?>
    <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map(Category::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
