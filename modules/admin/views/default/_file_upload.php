<?php
/**
 * Вьюха используется для функционала загрузки файлов через форму
 * Created by PhpStorm.
 * User: KURT
 * Date: 24.05.14
 * Time: 18:06
 * @var \yii\bootstrap\ActiveForm $form
 * @var \yii\db\ActiveRecord $model
 * @var string $attribute
 */
use dosamigos\fileupload\FileUpload;
use yii\helpers\Html;
use yii\web\JsExpression;


?>
<?php
$attr_tmp = $attribute . '_tmp';
$attr_name = $attribute . '_name';
$attr_id = $attribute . '_id';
$related_tmp = Html::getInputId($model, $attr_tmp);
$related_name = Html::getInputId($model, $attr_name);
$filed = $form->field($model, $attr_id);
echo $filed->begin();
echo Html::activeLabel($model, $attr_id, ['class' => 'control-label']);
echo Html::activeHiddenInput($model, $attr_id);
echo Html::activeHiddenInput($model, $attr_tmp);
echo Html::activeHiddenInput($model, $attr_name);
echo !empty($model->$attribute) ? '<br/>' . $model->$attribute->fileShowLink : "";
?>
    <br/>
    <span class="btn btn-success fileinput-button">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Выбрать файл</span>
        <?php
        echo FileUpload::widget([
            'name' => 'files',
            'url' => ['/admin/default/file-upload'],
            'options' => [
                'accept' => 'image/*',
                'related_tmp' => '#' . $related_tmp,
                'related_name' => '#' . $related_name,
                'name' => 'files',
            ],
            'clientOptions' => [
                'formData' => [],
                'done' => new JsExpression('function (e, data){
                    var related_tmp = $(this).attr("related_tmp");
                    $(related_tmp).val(data.result.files[0].name);

                    var related_name = $(this).attr("related_name");
                    $(related_name).val(data.result.files[0].origin_name);

                    $(this)
                    .parents(".fileinput-button")
                    .siblings(".file-show")
//                    .attr("href", data.result.files[0].url)
                    .text(data.result.files[0].origin_name);
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

    <?php echo Html::tag('span', '', ['class' => 'file-show', 'target' => 'blank']) ?>
    <div class="progress">
        <div class="bar" style="width: 0%;"></div>
    </div>
<?php echo $filed->end(); ?>