<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "shop".
 *
 * @property integer $id
 * @property string $name
 * @property string $short_about
 * @property string $full_about
 *
 * @property Category[] $categories
 * @property Collection[] $collections
 * @property Line[] $lines
 * @property Product[] $products
 */
class Shop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'short_about', 'full_about'], 'required'],
            [['name', 'short_about', 'full_about'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'short_about' => Yii::t('app', 'Short About'),
            'full_about' => Yii::t('app', 'Full About'),
        ];
    }

    public function beforeDelete()
    {
        /**
         * Проверка на существование связанных записей, что бы не было ошибок по FK
         */
        $errors = [];
        if ($this->getCategories()->count() != 0) {
            $errors[] = 'Категории';
        }
        if ($this->getCollections()->count() != 0) {
            $errors[] = 'Коллекции';
        }
        if ($this->getLines()->count() != 0) {
            $errors[] = 'Линии';
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
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['shop_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollections()
    {
        return $this->hasMany(Collection::className(), ['shop_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLines()
    {
        return $this->hasMany(Line::className(), ['shop_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['shop_id' => 'id']);
    }

    /**
     * Массив всех салонов
     * @return array
     */
    public static function listData()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'name');
    }
}
