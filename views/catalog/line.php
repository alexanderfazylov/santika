<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 04.06.14
 * Time: 10:11
 * @var Line $line
 * @var Interactive[] $intaractives
 */
use app\models\Interactive;
use app\models\Line;
use dosamigos\gallery\Carousel;
use yii\helpers\VarDumper;
use yii\web\JsExpression;

?>

<?php
$items = [];
foreach ($intaractives as $intaractive) {
    $products = [];
    foreach ($intaractive->interactiveProducts as $ip) {
        $products[] = [
            'id' => $ip->id,
            'product_id' => $ip->product_id,
            'left' => $ip->left,
            'top' => $ip->top,
        ];
    }


    $items[] = [
        'title' => $intaractive->name,
        'href' => $intaractive->upload->getFileShowUrl(),
        'products' => $products
    ];
}

?>
<?=
Carousel::widget([
    'items' => $items,
    'json' => true,
    'clientOptions' => [
        'onslide' => new JsExpression('function (index, slide) {
        console.log(slide);
          console.log(this.list[index].products);
        }')
    ]
]);?>
<?= $line->name; ?>