<?php

use app\models\Collection;
use app\models\Shop;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Collection $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<?php
/**
 * @TODO вынести всю работу с данными из вьюх в функции классов
 */
$shops_array = Shop::listData();
$query = Collection::find()->byShop($model->shop_id);
if (!is_null($model->id)) {
    $query->andWhere(['NOT IN', 'id', [$model->id]]);
}
$query->andWhere(['parent_id' => null]);
$collections = $query->all();
$collections_array = ArrayHelper::map($collections, 'id', 'name');
?>

<div class="collection-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'shop_id')->dropDownList($shops_array) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => 255]) ?>

    <?= $form->field($model, 'left_description')->textarea(['maxlength' => 1000]) ?>

    <?= $form->field($model, 'right_description')->textarea(['maxlength' => 1000]) ?>

    <?= $form->field($model, 'show_in_catalog')->checkbox() ?>

    <?php
    $options = ['prompt' => ''];
    if ($model->getChilds()->count() != 0) {
        //Нельзя назначить родительскую коллекцию коллекции, которая является родительской
        $collections_array = [];
        $options['disabled'] = 'disabled';
    } ?>

    <?= $form->field($model, 'parent_id')->dropDownList($collections_array, $options) ?>

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
        'attribute' => 'catalog_photo',
        'accept' => 'image/*'
    ]);
    ?>

    <!--    <?php //= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>-->

    <!--    --><?php //= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
