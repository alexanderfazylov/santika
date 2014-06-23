<?php

use app\models\Category;
use app\models\Line;
use app\models\LineCategory;
use app\models\Shop;
use yii\chosen\Chosen;
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
    <?php $lines_array = Line::listData($model->shop_id); ?>
    <?php $selected_lines = ArrayHelper::map(LineCategory::find()->andWhere(['category_id' => $model->id])->all(), 'id', 'line_id'); ?>
    <?php $parent_categories = ArrayHelper::map(Category::byLineIds($model->shop_id, $selected_lines, $model->id), 'id', 'name'); ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'shop_id')->dropDownList(Shop::listData()) ?>

    <?=
    $form->field($model, 'line_ids')->widget(Chosen::className(), [
        'multiple' => true,
        'items' => $lines_array,
        'options' => [
            'class' => 'form-control',
            'data-placeholder' => 'Выберите линии',
            'skip_id' => $model->id
        ]
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <!--    --><?php //= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>

    <?php
    /**
     * @TODO добавиьт проверку по shop_id и paranet_id != id
     */
    ?>
    <?= $form->field($model, 'parent_id')->dropDownList($parent_categories, ['prompt' => 'Выберите родительскую категорию']) ?>

    <!--    --><?php //= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
