<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 27.05.14
 * Time: 16:36
 * @var Product $model
 */
use app\models\Product;
use dosamigos\fileupload\FileUpload;
use dosamigos\fileupload\FileUploadUI;
use yii\helpers\Html;
use yii\web\JsExpression;

?>

<?= $model->name ?>
<?= Html::activeHiddenInput($model, 'id'); ?>

<span class="btn btn-success fileinput-button">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Выбрать файл</span>
    <?php
    echo FileUpload::widget([
        'name' => 'files',
        'url' => ['/admin/default/file-upload'],
        'options' => [
            'accept' => 'image/*',
            'name' => 'files',
        ],
        'clientOptions' => [
            'formData' => [],
            'done' => new JsExpression('function (e, data){
                    var $product_id = $("#product-id");
                    savePhoto($product_id.val(), data.result.files[0].name, data.result.files[0].origin_name);
                }'),
            'progress' => new JsExpression('function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $(this).parent().siblings(".progress").find(".bar").css(
                        "width",
                        progress + "%"
                    );
                }'
                )
        ]
    ]);
    ?>
    </span>

<div class="progress">
    <div class="bar" style="width: 0%;"></div>
</div>

<?php foreach ($model->photoGalleries as $photo_gallery): ?>
    <?php
    if (empty($photo_gallery->upload)) {
        continue;
    }
    echo $photo_gallery->upload->name;
    ?>
<?php endforeach; ?>
