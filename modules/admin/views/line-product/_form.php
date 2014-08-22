<?php

use app\models\Line;
use app\models\Product;
use app\models\Shop;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var int $shop_id
 * @var yii\web\View $this
 * @var app\models\LineProduct $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="line-product-form">

    <?php $shops_array = Shop::listData(); ?>
    <?php $lines_array = Line::listData($shop_id); ?>
    <?php $products_array = ArrayHelper::map(Product::find()->byShop($shop_id)->all(), 'id', 'name'); ?>

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <label>Салон</label>
        <?php echo Html::dropDownList('shop_id', $shop_id, $shops_array, ['id' => 'lineproduct-shop_id', 'class' => 'form-control']) ?>
    </div>

    <?= $form->field($model, 'line_id')->dropDownList($lines_array, ['prompt' => 'Выберите линию']); ?>
    <?= $form->field($model, 'product_id')->dropDownList($products_array, ['prompt' => 'Выберите товар']); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
