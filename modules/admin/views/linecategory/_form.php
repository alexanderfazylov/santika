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

    <?php $form = ActiveForm::begin(); ?>
    <?= Html::dropDownList('shop_id', $shop_id, ArrayHelper::map(Shop::find()->all(), 'id', 'name'), ['id' => 'linecategory-shop_id']) ?>
    <?= $form->field($model, 'line_id')->dropDownList(ArrayHelper::map(Line::find()->byShop($shop_id)->all(), 'id', 'name'), ['promt' => 'Выберите линию']) ?>
    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::withOutLine($shop_id, $model->id), 'id', 'name')); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
