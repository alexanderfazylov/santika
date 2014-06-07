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
 * @var int $category_url
 * @var Collection[] $collections
 * @var int $collection_url
 */
use app\models\Category;
use app\models\Collection;
use app\models\Line;
use app\models\Product;
use app\models\Upload;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

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
        Html::dropDownList('Product[category_url]', $category_url, ArrayHelper::map($categories, 'url', 'name'), [
            'prompt' => 'Категории',
            'class' => 'form-control',
            'id' => 'product-category_url'
        ]); ?>
    </div>
    <div class="col-md-4">
        <?=
        Html::dropDownList('Product[collection_url]', $collection_url, ArrayHelper::map($collections, 'url', 'name'), [
            'prompt' => 'Коллекции',
            'class' => 'form-control',
            'id' => 'product-collection_url'
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
        <?php $src = !empty($product->photo_id) ? $product->photo->getFileShowUrl(true) : Upload::defaultFileUrl(true) ?>
        <?= Html::a(Html::img($src), $product->createUrlByLine($line->url)); ?>
        <div>
            <div><?= 'Art. ' . $product->article; ?></div>
            <div><?= $product->name; ?></div>
        </div>
    </div>
<?php endforeach; ?>

<?php
$this->registerJs(
    '
    $(document).on("change", "#product-category_url, #product-collection_url", function () {
        var category_url= $("#product-category_url").val();
        var collection_url = $("#product-collection_url").val();
        window.location = "' . Url::to(['/catalog/line-product/', 'line_url' => $line->url]) . '?category_url=" + category_url + "&collection_url=" + collection_url;
    });'

);
?>
<script>
</script>