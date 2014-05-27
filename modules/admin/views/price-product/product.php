<?php
/**
 * Created by PhpStorm.
 * User: KURT
 * Date: 24.05.14
 * Time: 23:48
 * @var Product[] $products
 * @var int $shop_id
 * @var int $price_id
 */
use app\models\Price;
use app\models\PriceProduct;
use app\models\Product;
use app\models\Shop;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;


$this->title = 'Редактирование стоимости товаров';
//$this->params['breadcrumbs'][] = ['label' => 'Стоимость товаров', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование стоимости товаров';
?>
<div class="price-product-product">

    <h1><?= Html::encode($this->title) ?></h1>
    <label>Салон</label>
    <?php echo Html::dropDownList('shop_id', $shop_id, ArrayHelper::map(Shop::find()->all(), 'id', 'name')) ?>
    <br/>
    <label>Прайс</label>
    <?php $func = function ($array, $defaultValue) {
        return $array->start_date . ' (' . $array->typeText . ')';
    }?>
    <?php $prices = ArrayHelper::map(Price::find()->all(), 'id', function ($array, $defaultValue) {
        return $array->start_date . ' (' . $array->typeText . ')';
    })?>

    <?php echo Html::dropDownList('price_id', $price_id, $prices,
        [
            'id' => 'price_id',
            'prompt' => 'Выберите из сипска',
            'onChange' => new JsExpression('
            if($(this).val() != "") {
                window.location = "/admin/price-product/product?price_id=" + $(this).val();
            }
')
        ]) ?>
    <table id="price-product-table" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>
                Товар
            </th>
            <th>
                Стоимость (евро)
            </th>
            <th>
                Стоимость (руб)
            </th>
            <th>
            </th>
        </tr>
        </thead>
        <?php foreach ($products as $product) : ?>
            <?php
            $price_exist = !empty($product->priceProduct);
            /** @var PriceProduct $price_product */
            $price_product = ($price_exist) ? $product->priceProduct[0] : null;
            ?>
            <tr data-product_id="<?php echo $product->id ?>">
                <td>
                    <?php echo $product->name; ?>
                </td>
                <td>
                    <?php $cost_eur = $price_exist ? $product->priceProduct[0]->cost_eur : '0.00'; ?>
                    <?php echo Html::tag('span', $cost_eur, ['class' => 'hide-on-edit cost_eur']); ?>
                    <?php
                    /**
                     * @TODO masked input
                     */
                    ?>
                    <?php echo Html::textInput('cost_eur', $cost_eur, ['class' => 'show-on-edit ']); ?>
                </td>
                <td>
                    <?php $cost_rub = $price_exist ? $product->priceProduct[0]->cost_rub : '0.00'; ?>
                    <?php echo Html::tag('span', $cost_rub, ['class' => 'cost_rub']); ?>
                </td>
                <td>
                    <?php echo Html::a('Сохранить', '#', ['class' => 'save-product_price show-on-edit']); ?>
                    <?php echo Html::a('Отмена', '#', ['class' => 'cancel-product_price show-on-edit']); ?>
                    <?php echo Html::a('Редактировать', '#', ['class' => 'edit-product_price hide-on-edit']); ?>
                    <?php echo Html::a('Удалить', '#', ['class' => 'delete-product_price hide-on-edit']); ?>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

</div>
