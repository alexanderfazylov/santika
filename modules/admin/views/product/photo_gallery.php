<?php
/**
 * Created by PhpStorm.
 * User: ilgiz
 * Date: 27.05.14
 * Time: 16:36
 * @var Product $model
 * @var integer $color_id
 */
use app\models\Product;
use dosamigos\fileupload\FileUpload;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\Sortable;
use yii\web\JsExpression;

$this->title = 'Фотогалерея ';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="photo-gallery">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    /**
     * @TODO возможны проблемы с url, если перенести в другой контроллер
     */
    ?>

    <?= Html::activeHiddenInput($model, 'id'); ?>

    <?php $colors = ArrayHelper::map($model->productColors, 'color_id', 'color.name'); ?>
    <div class="form-group">
        <label>Покрытие</label>
        <?php echo Html::dropDownList('color_id', $color_id, $colors, ['id' => 'product-color_id', 'class' => 'form-control', 'prompt' => 'Без покрытия',
            'onChange' => new JsExpression('
                window.location = "/admin/product/photo-gallery?id=' . $model->id . '&color_id=" + $(this).val();
')
        ]) ?>
    </div>

    <br/>
    <span class="btn btn-success fileinput-button">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Выбрать файл</span>
        <?php
        echo FileUpload::widget([
            'name' => 'files[]',
            'url' => ['/default/file-upload'],
            'options' => [
                'accept' => 'image/*',
                'name' => 'files',
                'multiple' => ""
            ],
            'clientOptions' => [
                'formData' => [],
                'done' => new JsExpression('function (e, data){
                    var product_id = $("#product-id").val();
                    var color_id = $("#product-color_id").val();
                    savePhoto(product_id, color_id, data.result.files[0].name, data.result.files[0].origin_name);
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

    <?php
    $items = [];

    foreach ($model->photoGalleries as $photo_gallery) {

        if (empty($photo_gallery->upload)) {
            continue;
        }
        $item = [
            'content' => $photo_gallery->renderSortItem(),
        ];
        $items[] = $item;
    }
    ?>

    <?= Html::button('Сохранить сортировку', ['class' => 'btn btn-info save-photo_gallery_sort']) ?>
    <?php echo Sortable::widget(
        [
            'items' => $items,
            'options' => ['id' => 'photo_sorter'],
        ]
    );
    ?>
</div>