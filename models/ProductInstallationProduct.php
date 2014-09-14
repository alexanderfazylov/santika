<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_installation_product".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $installation_product_id
 *
 * @property Product $installationProduct
 * @property Product $product
 */
class ProductInstallationProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_installation_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'installation_product_id'], 'required'],
            [['product_id', 'installation_product_id'], 'integer']
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
            'installation_product_id' => Yii::t('app', 'Installation Product ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstallationProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'installation_product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
