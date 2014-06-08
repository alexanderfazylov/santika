<?php
/**
 * @var yii\web\View $this
 * @var Shop $shop
 * @var Product[] $products
 */
use app\models\Product;
use app\models\Shop;
use app\models\Upload;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $shop->name;
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <h2>О компании</h2>

            <p><?= $shop->short_about; ?></p>
        </div>
        <div class="row">
            <?php
            /**
             * @TODO вынести в отдельную вьюху\шаблон для отображения всех картинок товаров
             */
            ?>
            <?php foreach ($products as $product): ?>
                <div>
                    <?php
                    /**
                     * @TODO ссылка с картинкой используется в разных местах, мб сделать функцию
                     */
                    ?>
                    <?php $src = !empty($product->photo_id) ? $product->photo->getFileShowUrl(true) : Upload::defaultFileUrl(true) ?>
                    <?= Html::a(Html::img($src), $product->canonical); ?>
                    <div>
                        <div><?= 'Art. ' . $product->article; ?></div>
                        <div><?= $product->name; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row">
            <h2><?= Html::a('Линии', Url::to('/catalog')); ?></h2>
        </div>
    </div>
</div>
