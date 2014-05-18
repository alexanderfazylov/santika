<?php

use app\models\Line;
use app\models\Shop;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\LineCategory $model
 * @var ActiveForm $form
 * @var $shop_id int
 */
?>
<div class="line-line_category">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::dropDownList('shop_id', $shop_id, ArrayHelper::map(Shop::find()->all(), 'id', 'name')) ?>
    <?= Html::dropDownList('line_id', null, ArrayHelper::map(Line::find()->byShop(1)->all(), 'id', 'name')) ?>
    <?= $form->field($model, 'line_id') ?>
    <?= $form->field($model, 'category_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- line-line_category -->
