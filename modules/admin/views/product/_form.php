<?php

use app\models\Category;
use app\models\Collection;
use app\models\Color;
use app\models\Installation;
use app\models\Line;
use app\models\LineProduct;
use app\models\Product;
use app\models\Shop;
use nex\chosen\Chosen;
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
    $shops_array = Shop::listData();
    $lines_array = Line::listData($model->shop_id);
    $collection_array = ArrayHelper::map(Collection::find()->byShop($model->shop_id)->all(), 'id', 'name');
    $selected_lines = ArrayHelper::map(LineProduct::find()->andWhere(['product_id' => $model->id])->all(), 'id', 'line_id');
    $category_array = ArrayHelper::map(Category::byLineIds($model->shop_id, $selected_lines), 'id', 'name');
    $colors_array = ArrayHelper::map(Color::find()->all(), 'id', 'name');
    $installations_array = ArrayHelper::map(Installation::find()->all(), 'id', 'name');

    $name_article = function ($product, $defaultValue) {
        return $product->article . ' ' . $product->name;
    };
    $ip_query = Product::find()->byShop($model->shop_id);
    if (!$model->isNewRecord) {
        $ip_query->andWhere(['not in', 'id', [$model->id]]);
    }
    $installation_products = $ip_query->all();
    $installation_products_array = ArrayHelper::map($installation_products, 'id', $name_article);
    ?>
    <?php ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'shop_id')->dropDownList($shops_array) ?>

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

    <?=
    $form->field($model, 'color_ids')->widget(Chosen::className(), [
        'multiple' => true,
        'items' => $colors_array,
        'options' => [
            'class' => 'form-control',
            'data-placeholder' => 'Выберите покрытия',
        ]
    ]) ?>

    <?=
    $form->field($model, 'installation_ids')->widget(Chosen::className(), [
        'multiple' => true,
        'items' => $installations_array,
        'options' => [
            'class' => 'form-control',
            'data-placeholder' => 'Выберите способы монтажа',
        ]
    ]) ?>

    <?=
    $form->field($model, 'installation_product_ids')->widget(Chosen::className(), [
        'multiple' => true,
        'items' => $installation_products_array,
        'options' => [
            'class' => 'form-control',
            'data-placeholder' => 'Выберите монтажные элементы и комплектующие',
        ]
    ]) ?>

    <?= $form->field($model, 'length')->textInput() ?>

    <?= $form->field($model, 'width')->textInput() ?>

    <?= $form->field($model, 'height')->textInput() ?>

    <?= $form->field($model, 'is_promotion')->checkbox() ?>

    <?= $form->field($model, 'is_published')->checkbox() ?>

    <?php
    echo $this->render('/default/_file_upload.php', [
        'form' => $form,
        'model' => $model,
        'attribute' => 'photo',
        'accept' => 'image/*'
    ]);
    ?>

    <?php
    echo $this->render('/default/_file_upload.php', [
        'form' => $form,
        'model' => $model,
        'attribute' => 'manual',
        'accept' => '*'
    ]);
    ?>

    <?php
    echo $this->render('/default/_file_upload.php', [
        'form' => $form,
        'model' => $model,
        'attribute' => 'drawing',
        'accept' => 'image/*'
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
