<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "interactive".
 *
 * @property integer $id
 * @property integer $object_id
 * @property integer $upload_id
 * @property string $name
 * @property integer $type
 *
 * @property Upload $upload
 * @property Line $line
 * @property Collection $collection
 * @property InteractiveProduct[] $interactiveProducts
 */
class Interactive extends \yii\db\ActiveRecord
{
    const TYPE_LINE = 1;
    const TYPE_COLLECTION = 2;
    public $object_name;
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
            [['object_id'], 'required'],
            [['object_id', 'upload_id'], 'integer'],
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
            'object_id' => $this->lcTextUpper(),
            'name' => 'Наименование',
            'upload_id' => 'Изображение',
            'upload.name' => 'Изображение',
            'upload_name' => 'Изображение',
            'upload.fileShowLink' => 'Изображение',
            'object.name' => $this->lcTextUpper(),
            'objec_name' => $this->lcTextUpper(),
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


    public function beforeDelete()
    {
        /**
         * Проверка на существование связанных записей, что бы не было ошибок по FK
         */
        $errors = [];
        if ($this->getInteractiveProducts()->count() != 0) {
            $errors[] = 'Связь фотография-товар';
        }
        if (!empty($errors)) {
            $this->addError('id', 'Нельзя удалить, т.к. есть закрепленные ' . implode(', ', $errors));
            return false;
        }

        return parent::beforeDelete();
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
        return $this->hasOne(Line::className(), ['id' => 'object_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollection()
    {
        return $this->hasOne(Collection::className(), ['id' => 'object_id']);
    }

    /**
     * Возвращает текст в зависимости от типа интерактивного фото
     * @return string
     */
    public function lcText()
    {
        return $this->type == Interactive::TYPE_LINE ? 'линии' : 'коллекции';
    }

    /**
     * Возвращает текст в зависимости от типа интерактивного фото
     * @return string
     */
    public function lcTextUpper()
    {
        return $this->type == Interactive::TYPE_LINE ? 'Линия' : 'Коллекция';
    }

    /**
     * Возвращает текст в зависимости от типа интерактивного фото
     * @return string
     */
    public function lcTextAlter()
    {
        return $this->type == Interactive::TYPE_LINE ? 'линий' : 'коллекций';
    }

    /**
     * Возвращает текст в зависимости от типа интерактивного фото
     * @return string
     */
    public function lcTextSelect()
    {
        return $this->type == Interactive::TYPE_LINE ? 'линию' : 'коллекцию';
    }
}
