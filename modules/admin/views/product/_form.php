<?php

use app\models\Category;
use app\models\Collection;
use app\models\Line;
use app\models\LineProduct;
use app\models\Shop;
use dosamigos\fileupload\FileUpload;
use yii\chosen\Chosen;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Product $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="product-form">
    <?php
    $line_id = 1;
    $lines_array = ArrayHelper::map(Line::find()->byShop($model->shop_id)->all(), 'id', 'name');
    $collection_array = ArrayHelper::map(Collection::find()->byShop($model->shop_id)->all(), 'id', 'name');
    $selected_lines = ArrayHelper::map(LineProduct::find()->andWhere(['product_id' => $model->id])->all(), 'id', 'line_id');
    $category_array = ArrayHelper::map(Category::byLineIds($model->shop_id, $selected_lines), 'id', 'name');
    ?>
    <?php ?>

    <?php $form = ActiveForm::begin(); ?>

    Выберите линии:
    <?=
    Chosen::widget(
        [
            'name' => 'Product[line_ids]',
            'multiple' => true,
            'value' => $selected_lines,
            'items' => $lines_array,
            'options' => [
                'id' => 'product-line_ids',
                'class' => 'form-control',
                'data-placeholder' => false
            ]
        ]
    );
    ?>

    <?= $form->field($model, 'shop_id')->dropDownList(ArrayHelper::map(Shop::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'article')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'collection_id')->dropDownList($collection_array, ['prompt' => 'Выберите коллекцию']) ?>

    <?= $form->field($model, 'category_id')->dropDownList($category_array, ['prompt' => 'Выберите категорию']) ?>

    <!--    --><?php //= $form->field($model, 'manual_id')->textInput() ?>
    <!---->
    <!--    --><?php //= $form->field($model, 'coat_id')->textInput() ?>
    <!---->
    <!--    --><?php //= $form->field($model, 'drawing_id')->textInput() ?>

    <?= $form->field($model, 'length')->textInput() ?>

    <?= $form->field($model, 'width')->textInput() ?>

    <?= $form->field($model, 'height')->textInput() ?>

    <?= $form->field($model, 'is_promotion')->checkbox() ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span>Add files...</span>
    <?=
    FileUpload::widget([
        'id' => 'aaaaaaaaa',
        'name' => 'files',
        'url' => ['/admin/default/file-upload'],
        'options' => ['accept' => 'image/*'],
        'clientOptions' => [
            'maxFileSize' => 2000000,
        ]
    ]);?>

</span>