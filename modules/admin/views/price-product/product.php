<?php
/**
 * Created by PhpStorm.
 * User: KURT
 * Date: 24.05.14
 * Time: 23:48
 * @var int $shop_id
 * @var int $price_id
 * @var ArrayDataProvider $data_provider
 * @var DynamicModel $filter_model
 */
use app\models\Price;
use app\models\PriceProduct;
use app\models\Product;
use app\models\Shop;
use yii\base\DynamicModel;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;


$this->title = 'Редактирование стоимости товаров';
//$this->params['breadcrumbs'][] = ['label' => 'Стоимость товаров', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование стоимости товаров';
?>
<div class="price-product-product">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="form-group">
        <label>Салон</label>
        <?php echo Html::dropDownList('shop_id', $shop_id, Shop::listData(), ['class' => 'form-control']) ?>
    </div>
    <div class="form-group">
        <label>Прайс</label>
        <?php $prices = ArrayHelper::map(Price::find()->all(), 'id', function ($array, $defaultValue) {
            return $array->start_date . ' (' . $array->typeText . ')';
        })?>

        <?php echo Html::dropDownList('price_id', $price_id, $prices,
            [
                'id' => 'price_id',
                'class' => 'form-control',
                'prompt' => 'Выберите из сипска',
            ]) ?>
    </div>

    <?=
    GridView::widget([
        'id' => 'price-product-table',
        'dataProvider' => $data_provider,
        'filterModel' => $filter_model,
        'filterSelector' => '#price_id',
        'rowOptions' => function ($model, $key, $index, $grid) {
                return ['data-product_id' => $model['product_id'], 'data-color_id' => $model['color_id']];
            },
        'layout' => "{summary}\n{pager}\n{items}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'Артикул',
                'attribute' => 'article',
            ],
            [
                'label' => 'Товар',
                'attribute' => 'name',
            ],
            [
                'label' => 'Покрытие',
                'attribute' => 'color',
            ],
            [
                'label' => 'Стоимость (евро)',
                'attribute' => 'cost_eur',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $this) {
                        $html = Html::tag('span', $model['cost_eur'], ['class' => 'hide-on-edit cost_eur']);
                        $html .= MaskedInput::widget([
                            'mask' => '',
                            'name' => 'cost_eur',
                            'value' => $model['cost_eur'],
                            'options' => [
                                'class' => 'show-on-edit'
                            ],
                            'clientOptions' => [
                                'alias' => 'decimal',
                                'radixPoint' => ".",
                                'digits' => 2,
                                'allowMinus' => false,
                            ],
                        ]);
                        return $html;
                    }
            ],
            [
                'label' => 'Стоимость (руб)',
                'attribute' => 'cost_rub',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $this) {
                        return Html::tag('span', $model['cost_rub'], ['class' => 'cost_rub']);
                    }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{save-product_price} {cancel-product_price} {edit-product_price} {delete-product_price}',
                'buttons' => [
                    'save-product_price' => function ($url, $model) {
                            return Html::a('Сохранить', '#', ['class' => 'save-product_price show-on-edit']);
                        },
                    'cancel-product_price' => function ($url, $model) {
                            return Html::a('Отмена', '#', ['class' => 'cancel-product_price show-on-edit']);
                        },
                    'edit-product_price' => function ($url, $model) {
                            return Html::a('Редактировать', '#', ['class' => 'edit-product_price hide-on-edit']);
                        },
                    'delete-product_price' => function ($url, $model) {
                            return Html::a('Удалить', '#', ['class' => 'delete-product_price hide-on-edit']);
                        },
                ]
            ],
        ],
    ]); ?>
</div>

