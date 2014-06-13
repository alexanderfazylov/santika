<?php

use app\models\Price;
use app\models\Shop;
use dosamigos\datepicker\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Price $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="price-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'shop_id')->dropDownList(ArrayHelper::map(Shop::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'start_date')->hiddenInput() ?>

    <?php
    if (!empty($model->start_date)) {
        $date = DateTime::createFromFormat('Y-m-d', $model->start_date);
        $value = $date->format('d.m.Y');
    } else {
        $value = '';
    }
    echo DatePicker::widget([
        'name' => 'Test',
        'value' => $value,
        'template' => '{addon}{input}',
        'language' => 'ru',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'dd.mm.yyyy'
        ],
        'clientEvents' => [
            'changeDate' => new JsExpression('function(e){
                $("#price-start_date").val(e.format("yyyy-mm-dd"));
            }')
        ]
    ]);
    ?>

    <?= $form->field($model, 'type')->dropDownList(Price::getTypesText()) ?>

    <?php
    echo $this->render('/default/_file_upload.php', [
        'form' => $form,
        'model' => $model,
        'attribute' => 'import',
        'accept' => '**'
    ]);
    ?>

    <?= $form->field($model, 'article_column')->textInput() ?>

    <?= $form->field($model, 'cost_column')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
