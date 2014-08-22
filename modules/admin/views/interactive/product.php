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
use app\modules\admin\assets\InteractiveAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\DraggableAsset;
use yii\web\JqueryAsset;

DraggableAsset::register($this);
InteractiveAsset::register($this);
$this->title = $interactive->name;
$this->params['breadcrumbs'][] = ['label' => 'Интерьерные фотографии', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="interactive-product">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= Html::button('Сохранить', ['class' => 'btn btn-primary save-interactive-product', 'type' => 'button']) ?>
        <br/>
        <br/>
        <?php
        $products = $interactive->type == Interactive::TYPE_LINE ?
            Product::find()->byShop($shop_id)->byLine($interactive->object_id)->all()
            :
            Product::find()->byShop($shop_id)->byCollection($interactive->object_id)->all();
        $products_array = ArrayHelper::map($products, 'id', 'name');
        ?>

        <?php echo Html::activeHiddenInput($interactive, 'id'); ?>
        <div class="row">
            <div class="col-md-6">
                <?= Html::dropDownList('product_id', null, $products_array, ['id' => 'interactive_product-product_id', 'prompt' => 'Выберите товар', 'class' => 'form-control']) ?>
                <button class="interactive-add-product btn btn-info">Добавить на фото</button>
            </div>
            <div>
                <div class="interactive-products col-md-6"></div>
            </div>
        </div>

        <div class="interactive-owner">
            <img src="<?= $interactive->upload->getFileShowUrl() ?>">
        </div>
    </div>

<?php
$this->registerJs('refreshInteractiveProducts();');
?>