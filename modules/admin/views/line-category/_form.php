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

    <?php $shops_array = Shop::listData(); ?>
    <?php $lines_array = Line::listData($shop_id); ?>
    <?php $categories_array = ArrayHelper::map(Category::find()->byShop($shop_id)->all(), 'id', 'name'); ?>
    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <label>Салон</label>
        <?php echo Html::dropDownList('shop_id', $shop_id, $shops_array, ['id' => 'linecategory-shop_id', 'class' => 'form-control']) ?>
    </div>

    <?= $form->field($model, 'line_id')->dropDownList($lines_array, ['prompt' => 'Выберите линию', 'line_category_id' => $model->id]); ?>
    <?= $form->field($model, 'category_id')->dropDownList($categories_array, ['prompt' => 'Выберите категорию']); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
