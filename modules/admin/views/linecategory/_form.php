<?php

use app\models\Category;
use app\models\Line;
use app\models\Shop;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var int $shop_id
 * @var yii\web\View $this
 * @var app\models\LineCategory $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="line-category-form">

    <?php $shops_array = ArrayHelper::map(Shop::find()->all(), 'id', 'name'); ?>
    <?php $lines_array = ArrayHelper::map(Line::find()->byShop($shop_id)->all(), 'id', 'name'); ?>
    <?php $categories_array = ArrayHelper::map(Category::withOutLine($shop_id, $model->id), 'id', 'name'); ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::dropDownList('shop_id', $shop_id, $shops_array, ['id' => 'linecategory-shop_id']) ?>
    <?= $form->field($model, 'line_id')->dropDownList($lines_array, ['promt' => 'Выберите линию', 'line_category_id' => $model->id]); ?>
    <?= $form->field($model, 'category_id')->dropDownList($categories_array); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
