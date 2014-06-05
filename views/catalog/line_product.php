<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 04.06.14
 * Time: 10:11
 * @var yii\web\View $this
 * @var Line $line
 * @var Product[] $products
 * @var Category[] $categories
 * @var int $category_id
 * @var Collection[] $collections
 * @var int $collection_id
 */
use app\models\Category;
use app\models\Collection;
use app\models\Line;
use app\models\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = 'Линия ' . $line->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/catalog']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<div class="row">
    <div class="col-md-4">
        <?= Html::a('Назад к интерьерным фото', $line->createUrl()); ?>
    </div>
    <div class="col-md-4">
        <?=
        Html::dropDownList('Product[category_id]', $category_id, ArrayHelper::map($categories, 'id', 'name'), [
            'prompt' => 'Категории',
            'class' => 'form-control',
            'id' => 'product-category_id'
        ]); ?>
    </div>
    <div class="col-md-4">
        <?=
        Html::dropDownList('Product[collection_id]', $collection_id, ArrayHelper::map($collections, 'id', 'name'), [
            'prompt' => 'Коллекции',
            'class' => 'form-control',
            'id' => 'product-collection_id'
        ]); ?>
    </div>
</div>

<?php foreach ($products as $product): ?>
    <div>
        <?php
        /**
         * @TODO ссылка с картинкой используется в разных местах, мб сделать функцию
         */
        ?>
        <?= Html::a(Html::img($product->photo->getFileShowUrl(true)), $product->createUrlByLine($line->url)); ?>
        <div>
            <div><?= 'Art. ' . $product->article; ?></div>
            <div><?= $product->name; ?></div>
        </div>
    </div>
<?php endforeach; ?>

<?php
$this->registerJs(
    '
    $(document).on("change", "#product-category_id, #product-collection_id", function () {
        var category_id = $("#product-category_id").val();
        var collection_id = $("#product-collection_id").val();
        window.location = "' . Url::to(['/catalog/line-product/', 'url' => $line->url]) . '?category_id=" + category_id + "&collection_id=" + collection_id;
    });'

);
?>
<script>
</script>