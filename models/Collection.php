<?php

namespace app\models;

use app\models\scopes\CategoryScope;
use app\models\scopes\CollectionScope;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * This is the model class for table "collection".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $name
 * @property string $description
 * @property string $left_description
 * @property string $right_description
 * @property integer $sort
 * @property string $url
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property integer $parent_id
 * @property integer $photo_id
 * @property integer $catalog_photo_id
 * @property integer $show_in_catalog
 *
 * @property Collection $parent
 * @property Collection[] $childs
 * @property Shop $shop
 * @property Product[] $products
 * @property Upload $photo
 * @property Upload $catalog_photo
 */
class Collection extends \yii\db\ActiveRecord
{
    public $photo_tmp;
    public $photo_name;
    public $catalog_photo_tmp;
    public $catalog_photo_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'name', 'url'], 'required'],
            [['shop_id', 'sort', 'parent_id', 'photo_id', 'catalog_photo_id', 'show_in_catalog'], 'integer'],
            [['parent_id'], 'checkParentId'],
            [['name', 'description', 'url', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255],
            [[ 'left_description', 'right_description', ], 'string', 'max' => 1000],
            [['photo_tmp', 'photo_name', 'catalog_photo_tmp', 'catalog_photo_name'], 'safe']
        ];
    }

    /**
     * Проверка значения в parent_id
     * @param $attribute
     */
    public function checkParentId($attribute)
    {
        if ($this->getChilds()->count() != 0) {
            $this->addError('parent_id', 'Нельзя назначить родительскую коллекцию коллекции, которая является родительской.');
        }
        if ($this->getParent()->andWhere(['parent_id' => null])->count() == 0) {
            $this->addError('parent_id', 'Нельзя назначить дочернюю коллекцию как родительскую.');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'shop_id' => 'Салон',
            'shop.name' => 'Салон',
            'name' => 'Название',
            'description' => 'Описание',
            'left_description' => 'Описание (левый столбец)',
            'right_description' => 'Описание (правый столбец)',
            'sort' => 'Сортировка',
            'parent_id' => 'Родительская коллекция',
            'parent_name' => 'Родительская коллекция',
            'parent.name' => 'Родительская коллекция',
            'url' => Yii::t('app', 'Url'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'meta_keywords' => Yii::t('app', 'Meta Keywords'),
            'photo.fileShowLink' => 'Фото',
            'photo_id' => 'Фото',
            'catalog_photo.fileShowLink' => 'Фото (каталог)',
            'catalog_photo_id' => 'Фото (каталог)',
            'show_in_catalog' => 'Отображать на главной странице каталога',
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
        /**
         * @TODO при поиске в админке выполняются эти функции., мб придумать что то другое?
         */
        $this->saveFileFromAttribute('photo', Upload::TYPE_COLLECTION);
        $this->saveFileFromAttribute('catalog_photo', Upload::TYPE_COLLECTION);
        $this->url = Inflector::slug($this->name);
        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     * @return CollectionScope
     */
    public static function find()
    {
        return new CollectionScope(get_called_class());
    }

    public function beforeDelete()
    {
        /**
         * Проверка на существование связанных записей, что бы не было ошибок по FK
         */
        $errors = [];
        if ($this->getChilds()->count() != 0) {
            $errors[] = 'Коллекции';
        }
        if ($this->getProducts()->count() != 0) {
            $errors[] = 'Товары';
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
    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['collection_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Collection::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChilds()
    {
        return $this->hasMany(Collection::className(), ['parent_id' => 'id'])
            ->from(self::tableName() . ' AS child');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Upload::className(), ['id' => 'photo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog_photo()
    {
        return $this->hasOne(Upload::className(), ['id' => 'catalog_photo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInteractives()
    {
        return $this->hasMany(Interactive::className(), ['object_id' => 'id'])->onCondition('type = ' . Interactive::TYPE_COLLECTION);
    }

    /**
     * Возвращает ссылку на коллекцию
     * @return string
     */
    public function createUrl()
    {
        return Url::to(['/catalog/collection/', 'url' => $this->url, 'parent_url' => !empty($this->parent_id) ? $this->parent->url : null]);
    }

    /**
     * Массив коллекций по магазину
     * @param $shop_id
     * @return array
     */
    public static function listData($shop_id)
    {
        return ArrayHelper::map(static::find()->byShop($shop_id)->all(), 'id', 'name');
    }

    /**
     * Ссылка на страницу фильтра  с товарами
     * @return string
     */
    public function urlToFilter()
    {
        return Url::to(['filter', 'collection_url' => $this->url]);
    }
}
