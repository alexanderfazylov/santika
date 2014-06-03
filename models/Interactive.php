<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "interactive".
 *
 * @property integer $id
 * @property integer $line_id
 * @property integer $upload_id
 * @property string $name
 *
 * @property Upload $upload
 * @property Line $line
 * @property InteractiveProduct[] $interactiveProducts
 */
class Interactive extends \yii\db\ActiveRecord
{
    public $line_name;
    public $upload_tmp;
    public $upload_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'interactive';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['line_id'], 'required'],
            [['line_id', 'upload_id'], 'integer'],
            /**
             * @TODO сделать проверку на наличие файла в других моделях
             */
            [['upload_id'], 'required'
                , 'when' => function ($model, $attribute) {
                    return empty($model->upload_tmp) && empty($model->upload_id);
                }
                , 'whenClient' => 'function (attribute, value) {
                    return  $("#interactive-upload_id").val() == "" &&   $("#interactive-upload_tmp").val() == "";
                }',
            ],
            [['name'], 'string', 'max' => 255],
            [['upload_tmp', 'upload_name'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'line_id' => 'Линия',
            'name' => 'Наименование',
            'upload_id' => 'Изображение',
            'upload.name' => 'Изображение',
            'upload_name' => 'Изображение',
            'upload.fileShowLink' => 'Изображение',
            'line.name' => Yii::t('app', 'Линия'),
            'line_name' => Yii::t('app', 'Линия'),
        ];
    }


    public function behaviors()
    {
        return [
            'fileSaveBehavior' => [
                'class' => 'app\behaviors\FileSaveBehavior',
            ]
        ];
    }

    public function beforeValidate()
    {
        $this->saveFileFromAttribute('upload', Upload::TYPE_INTERACTIVE);
        return parent::beforeValidate();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpload()
    {
        return $this->hasOne(Upload::className(), ['id' => 'upload_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInteractiveProducts()
    {
        return $this->hasMany(InteractiveProduct::className(), ['interactive_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLine()
    {
        return $this->hasOne(Line::className(), ['id' => 'line_id']);
    }
}
