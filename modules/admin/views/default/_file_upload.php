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
 * @var string $accept
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
$field = $form->field($model, $attr_id);
echo $field->begin();
echo Html::activeLabel($model, $attr_id, ['class' => 'control-label']);
echo Html::activeHiddenInput($model, $attr_id, ['class' => 'uploaded-file-id']);
echo Html::activeHiddenInput($model, $attr_tmp, ['class' => 'tmp-file-path']);
echo Html::activeHiddenInput($model, $attr_name, ['class' => 'tmp-file-name']);
?>
<?php if (!empty($model->$attribute)) : ?>
    <br/>
    <?= Html::tag('span', $model->$attribute->fileShowLink, ['class' => 'uploaded-file']); ?>
    &nbsp;
    <?= Html::a('Удалить', '#', ['class' => 'delete-uploaded-file']); ?>
<?php endif; ?>
    <br/>
    <span class="btn btn-success fileinput-button">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Выбрать файл</span>
        <?php
        echo FileUpload::widget([
            'name' => 'files',
            'url' => ['/default/file-upload'],
            'options' => [
                'accept' => $accept,
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
                    .siblings(".tmp-file")
                    .text(data.result.files[0].origin_name);

                    $(this)
                    .parents(".fileinput-button")
                    .siblings(".delete-tmp-file")
                    .removeClass("hidden")
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

<?php echo Html::tag('span', '', ['class' => 'tmp-file']) ?>
    &nbsp;
<?php echo Html::a('Удалить', '#', ['class' => 'delete-tmp-file hidden']) ?>
    <div class="progress">
        <div class="bar" style="width: 0%;"></div>
    </div>
<?php echo $field->end(); ?>