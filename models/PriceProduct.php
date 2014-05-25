<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "price_product".
 *
 * @property integer $id
 * @property integer $price_id
 * @property integer $product_id
 * @property string $cost_eur
 * @property string $cost_rub
 *
 * @property Product $product
 * @property Price $price
 */
class PriceProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price_id', 'product_id', 'cost_eur'], 'required'],
            [['price_id', 'product_id'], 'integer'],
            [['cost_eur', 'cost_rub'], 'number'],
            [['price_id', 'product_id'], 'unique', 'targetAttribute' => ['price_id', 'product_id'], 'message' => 'The combination of Price ID and Product ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'price_id' => Yii::t('app', 'Price ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'cost_eur' => Yii::t('app', 'Cost Eur'),
            'cost_rub' => Yii::t('app', 'Cost Rub'),
        ];
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
    public function getPrice()
    {
        return $this->hasOne(Price::className(), ['id' => 'price_id']);
    }
}
