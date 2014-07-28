<?php

namespace app\models;

use app\models\scopes\CategoryScope;
use app\models\scopes\CollectionScope;
use Yii;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * This is the model class for table "collection".
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
 * @property integer $parent_id
 *
 * @property Collection $parent
 * @property Collection[] $childs
 * @property Shop $shop
 * @property Product[] $products
 */
class Collection extends \yii\db\ActiveRecord
{
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
            [['shop_id', 'name', 'description', 'url'], 'required'],
            [['shop_id', 'sort', 'parent_id'], 'integer'],
            [['parent_id'], 'checkParentId'],
            [['name', 'description', 'url', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255]
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
            'shop_id' => 'Салон', //Yii::t('app', 'Shop ID'),
            'shop.name' => 'Салон',
            'name' => 'Название', //Yii::t('app', 'Name'),
            'description' => 'Описание', //Yii::t('app', 'Description'),
            'sort' => 'Сортировка', //Yii::t('app', 'Sort'),
            'parent_id' => 'Родительская коллекция',
            'parent_name' => 'Родительская коллекция',
            'parent.name' => 'Родительская коллекция',
            'url' => Yii::t('app', 'Url'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'meta_keywords' => Yii::t('app', 'Meta Keywords'),
        ];
    }

    public function beforeValidate()
    {
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
     * Возвращает ссылку на коллекцию
     * @return string
     */
    public function createUrl()
    {
        return Url::to(['/catalog/collection/', 'url' => $this->url, 'parent_url' => !empty($this->parent_id) ? $this->parent->url : null]);
    }
}
