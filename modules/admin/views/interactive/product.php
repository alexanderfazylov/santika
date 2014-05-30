<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 30.05.14
 * Time: 12:28
 * @var yii\web\View $this
 * @var Interactive $interactive
 * @var int $shop_id
 */
use app\models\Interactive;
use app\models\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\DraggableAsset;
use yii\web\JqueryAsset;

DraggableAsset::register($this);

$this->title = $interactive->name;
$this->params['breadcrumbs'][] = ['label' => 'Интерьерные фотографии', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="interactive-product">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= Html::button('Сохранить', ['class' => 'btn btn-primary save-interactive-product', 'type' => 'button']) ?>
        <?php $products_array = ArrayHelper::map(Product::find()->byShop($shop_id)->all(), 'id', 'name'); ?>

        <?php echo Html::activeHiddenInput($interactive, 'id'); ?>
        <div class="interactive-add">
            <?= Html::dropDownList('product_id', null, $products_array, ['id' => 'interactive_product-product_id', 'prompt' => 'Выберите товар']) ?>
            <button class="interactive-add-product">Добавить на фото</button>
        </div>

        <div class="interactive-products"></div>
        <div class="interactive-info">
            <label class="product-name"></label>
            <label class="left-percent"></label>
            <label class="top-percent"></label>
        </div>
        <div class="interactive-owner">
            <img src="<?= $interactive->upload->getFileShowUrl() ?>">
        </div>
    </div>

<?php
$this->registerJs('getInteractiveProducts("' . $interactive->id . '");');
?>