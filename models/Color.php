<?php

namespace app\models;

use app\models\scopes\ColorScope;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "color".
 *
 * @property integer $id
 * @property string $name
 * @property integer $upload_id
 * @property string $article
 *
 * @property Upload $upload
 * @property PhotoGallery[] $photoGalleries
 * @property PriceProduct[] $priceProducts
 * @property Product[] $products
 * @property ProductColor[] $productColors
 */
class Color extends \yii\db\ActiveRecord
{
    public $upload_tmp;
    public $upload_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'color';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'article'], 'required'],
            [['upload_id'], 'integer'],
            [['name', 'article'], 'string', 'max' => 255],
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
            'name' => 'Наименование',
            'article' => 'Артикул',
            'upload_id' => 'Изображение',
            'upload.name' => 'Изображение',
            'upload_name' => 'Изображение',
            'upload.fileShowLink' => 'Изображение',
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
        $this->saveFileFromAttribute('upload', Upload::TYPE_COLOR);
        return parent::beforeValidate();
    }

    public function beforeDelete()
    {
        /**
         * Проверка на существование связанных записей, что бы не было ошибок по FK
         */
        $errors = [];
        if ($this->getPriceProducts()->count() != 0) {
            $errors[] = 'Стоимость товара';
        }
        if ($this->getProductColors()->count() != 0) {
            $errors[] = 'Товары';
        }
        if ($this->getPhotoGalleries()->count() != 0) {
            $errors[] = 'Фотогалерея товара';
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
    public function getPhotoGalleries()
    {
        return $this->hasMany(PhotoGallery::className(), ['color_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceProducts()
    {
        return $this->hasMany(PriceProduct::className(), ['color_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['color_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductColors()
    {
        return $this->hasMany(ProductColor::className(), ['color_id' => 'id']);
    }

    public static function find()
    {
        return new ColorScope(get_called_class());
    }

    /**
     * Возвращает url на файл или миниатюру
     * @param string $size
     * @return string
     */
    public function getFileShowUrl($size = Upload::SIZE_ORIGIN)
    {
        return !is_null($this->upload_id) ? $this->upload->getFileShowUrl($size) : Upload::defaultFileUrl($size);
    }

}
