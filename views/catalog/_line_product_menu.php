<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 06.08.14
 * Time: 10:22
 * @var Collection[] $collections
 * @var Category[] $categories
 */
use app\models\Category;
use app\models\Collection;

?>
<div class="b-rzd__menu" style="position: relative">
    <ul class="parents">
        <li class="parent"
            object_type="group"
            object_id="category"
            hide_level="level1">
            <a class="parent-link" href="">Категории</a>
        </li>
        <li class="parent"
            object_type="group"
            object_id="collection"
            hide_level="level1">
            <a class="parent-link" href="">Коллекции</a>
        </li>
    </ul>
    <?php
    echo $this->render('/catalog/_line_product_menu_item', array(
        'object_type' => "category",
        'parent_type' => "group",
        'parent_id' => 'category',
        'level' => 1,
        'models' => $categories,
    ));
    ?>

    <?php
    echo $this->render('/catalog/_line_product_menu_item', array(
        'object_type' => "collection",
        'parent_type' => "group",
        'parent_id' => 'collection',
        'level' => 1,
        'models' => $collections,
    ));
    ?>
</div>