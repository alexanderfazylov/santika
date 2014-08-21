<?php

namespace app\models;

use app\models\scopes\LineScope;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * This is the model class for table "line".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $name
 * @property string $description
 * @property integer $sort
 * @property string $url
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 *
 * @property Interactive[] $interactives
 * @property Shop $shop
 * @property LineCategory[] $lineCategories
 * @property LineProduct[] $lineProducts
 */
class Line extends \yii\db\ActiveRecord
{
    public $photo_tmp;
    public $photo_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'line';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'name', 'description', 'url'], 'required'],
            [['shop_id', 'sort', 'photo_id'], 'integer'],
            [['name', 'description', 'url', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'shop_id' => 'Салон', //Yii::t('app', 'Shop ID'),
            'shop.name' => 'Салон',
            'name' => 'Название', //Yii::t('app', 'Name'),
            'description' => 'Описание', //Yii::t('app', 'Description'),
            'sort' => 'Сортировка', //Yii::t('app', 'Sort'),
            'url' => Yii::t('app', 'Url'),
            'photo.fileShowLink' => 'Фото',
            'photo_id' => 'Фото',
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'meta_keywords' => Yii::t('app', 'Meta Keywords'),
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
        $this->url = Inflector::slug($this->name);
        $this->saveFileFromAttribute('photo', Upload::TYPE_LINE);
        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     * @return LineScope
     */
    public static function find()
    {
        return new LineScope(get_called_class());
    }

    public function beforeDelete()
    {
        /**
         * Проверка на существование связанных записей, что бы не было ошибок по FK
         */
        $errors = [];
        if ($this->getLineProducts()->count() != 0) {
            $errors[] = 'Связь линия-товар';
        }
        if ($this->getLineCategories()->count() != 0) {
            $errors[] = 'Связь линия-категория';
        }
        if ($this->getInteractives()->count() != 0) {
            $errors[] = 'Интерьерные фотографии';
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
    public function getInteractives()
    {
        return $this->hasMany(Interactive::className(), ['line_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLineCategories()
    {
        return $this->hasMany(LineCategory::className(), ['line_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLineProducts()
    {
        return $this->hasMany(LineProduct::className(), ['line_id' => 'id']);
    }

    /**
     * Возвращает ссылку на коллекцию
     * @return string
     */
    public function createUrl()
    {
        return Url::to(['/catalog/line/', 'url' => $this->url]);
    }

    /**
     * Массив линий по магазину
     * @param $shop_id
     * @return array
     */
    public static function listData($shop_id)
    {
        return ArrayHelper::map(static::find()->byShop($shop_id)->all(), 'id', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Upload::className(), ['id' => 'photo_id']);
    }
}
