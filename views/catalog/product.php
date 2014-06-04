<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 04.06.14
 * Time: 10:11
 * @var Line $line
 * @var Category $category
 * @var Product $product
 */
use app\models\Category;
use app\models\Line;
use app\models\Product;

?>

<?= $line->name; ?>
<?php if (!is_null($category)): ?>
    <?= $category->name; ?>
<?php endif; ?>
<?= $product->name; ?>