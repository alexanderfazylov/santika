<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_color".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $color_id
 * @property integer $photo_id
 *
 * @property Upload $photo
 * @property Color $color
 * @property Product $product
 */
class ProductColor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_color';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'color_id'], 'required'],
            [['product_id', 'color_id', 'photo_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'color_id' => Yii::t('app', 'Color ID'),
            'photo_id' => Yii::t('app', 'Photo ID'),
        ];
    }

    public function transactions()
    {
        return [
            'default' => self::OP_DELETE,
        ];
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
    public function getColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'color_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceProducts()
    {
        return $this->hasMany(PriceProduct::className(), ['product_id' => 'product_id', 'color_id' => 'color_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoGalleries()
    {
        return $this->hasMany(PhotoGallery::className(), ['object_id' => 'product_id', 'color_id' => 'color_id']);
    }
}
