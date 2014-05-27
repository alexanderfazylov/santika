<?php

use app\models\Category;
use app\models\Collection;
use app\models\Color;
use app\models\Line;
use app\models\LineProduct;
use app\models\Shop;
use dosamigos\fileupload\FileUpload;
use dosamigos\fileupload\FileUploadUI;
use yii\chosen\Chosen;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
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
    $colors_array = ArrayHelper::map(Color::find()->all(), 'id', 'name');
    ?>
    <?php ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'shop_id')->dropDownList(ArrayHelper::map(Shop::find()->all(), 'id', 'name')) ?>

    <?=
    $form->field($model, 'line_ids')->widget(Chosen::className(), [
        'multiple' => true,
        'items' => $lines_array,
        'options' => [
            'class' => 'form-control',
            'data-placeholder' => 'Выберите линии',
        ]
    ]) ?>

    <?= $form->field($model, 'article')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'collection_id')->dropDownList($collection_array, ['prompt' => 'Выберите коллекцию']) ?>

    <?= $form->field($model, 'category_id')->dropDownList($category_array, ['prompt' => 'Выберите категорию']) ?>

   <?= $form->field($model, 'color_id')->dropDownList($colors_array, ['prompt' => 'Выберите покрытие'])  ?>

    <?= $form->field($model, 'length')->textInput() ?>

    <?= $form->field($model, 'width')->textInput() ?>

    <?= $form->field($model, 'height')->textInput() ?>

    <?= $form->field($model, 'is_promotion')->checkbox() ?>

    <?php
    /**
     * @TODO придумать что нить с загрузкой файлов
     */
    ?>
    <?php
    echo $this->render('/default/_file_upload.php', [
        'form' => $form,
        'model' => $model,
        'attribute' => 'photo',
    ]);
    ?>

    <?php
    echo $this->render('/default/_file_upload.php', [
        'form' => $form,
        'model' => $model,
        'attribute' => 'manual',
    ]);
    ?>

    <?php
    echo $this->render('/default/_file_upload.php', [
        'form' => $form,
        'model' => $model,
        'attribute' => 'drawing',
    ]);
    ?>

    <?= $form->field($model, 'canonical')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
