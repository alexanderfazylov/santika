<?php

use app\models\Collection;
use app\models\Interactive;
use app\models\Line;
use app\models\Shop;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Interactive $model
 * @var yii\widgets\ActiveForm $form
 * @var int $shop_id
 */
?>

<div class="interactive-form">

    <?php $shops_array = Shop::listData(); ?>
    <?php $objects_array = $model->type == Interactive::TYPE_LINE ? Line::listData($shop_id) : Collection::listData($shop_id); ?>

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <label>Салон</label>
        <?php echo Html::dropDownList('shop_id', $shop_id, $shops_array, ['id' => 'interactive-shop_id', 'class' => 'form-control']) ?>
    </div>


    <?= $form->field($model, 'object_id')->dropDownList($objects_array, ['prompt' => 'Выберите ' . $model->lcTextSelect()]); ?>

    <?php
    echo $this->render('/default/_file_upload.php', [
        'form' => $form,
        'model' => $model,
        'attribute' => 'upload',
        'accept' => 'image/*'
    ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
