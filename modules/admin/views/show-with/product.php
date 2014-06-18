<?php

use app\models\Product;
use app\models\Shop;
use app\models\ShowWith;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\Sortable;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var ShowWith $model
 * @var Product[] $products
 * @var integer $shop_id
 */

$this->title = Yii::t('app', 'Отображать на странице');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="show-with-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <label>Салон</label>
        <?php echo Html::dropDownList('shop_id', $shop_id, ArrayHelper::map(Shop::find()->all(), 'id', 'name'), ['id' => 'showwith-shop_id', 'class' => 'form-control']) ?>
    </div>

    <?=
    $form->field($model, 'type')->dropDownList(ShowWith::getTypes(), [
        'class' => 'form-control',
        'prompt' => 'Выберите из списка',
    ]);
    ?>

    <?=
    $form->field($model, 'object_id')->dropDownList([], [
        'class' => 'form-control',
        'prompt' => 'Выберите из списка',
    ]);
    ?>

    <?=
    $form->field($model, 'product_id')->dropDownList(ArrayHelper::map($products, 'id', 'name'), [
        'class' => 'form-control',
        'prompt' => 'Выберите из списка',
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'id' => 'add-showwith']) ?>
    </div>

    <?= Html::button('Сохранить сортировку', ['class' => 'btn btn-info save-showwith_sort']) ?>
    <?php echo Sortable::widget(
        [
            'items' => [],
            'options' => ['id' => 'showwith-sorter'],
        ]
    );
    ?>

    <?php ActiveForm::end(); ?>

</div>
