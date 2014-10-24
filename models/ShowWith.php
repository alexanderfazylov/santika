<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "show_with".
 *
 * @property integer $id
 * @property integer $object_id
 * @property integer $product_id
 * @property integer $type
 * @property integer $sort
 */
class ShowWith extends \yii\db\ActiveRecord
{
    const TYPE_LINE = 1;
    const TYPE_COLLECTION = 2;
    const TYPE_CATEGORY = 3;
    const TYPE_PRODUCT = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'show_with';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'product_id', 'type'], 'required'],
            [['object_id', 'product_id', 'type', 'sort'], 'integer'],
            [['object_id'], 'unique', 'targetAttribute' => ['object_id', 'product_id', 'type'], 'message' => 'Товар уже отбражается на выбранной странице.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'object_id' => 'Страница',
            'product_id' => 'Товар',
            'type' => 'Тип страницы',
            'sort' => Yii::t('app', 'Sort'),
        ];
    }

    public function transactions()
    {
        return [
            'default' => self::OP_DELETE,
        ];
    }

    /**
     * Возвращает массив названий для типов
     * @return array
     */
    public static function getTypes()
    {
        return [
            static::TYPE_LINE => 'Линия',
            static::TYPE_COLLECTION => 'Коллекция',
            static::TYPE_CATEGORY => 'Категория',
            static::TYPE_PRODUCT => 'Товар',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        /**
         * @TODO добавить type? ->onCondition(['type' => PhotoGallery::TYPE_PRODUCT])
         */
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
