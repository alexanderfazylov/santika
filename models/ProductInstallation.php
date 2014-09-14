<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_installation".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $installation_id
 *
 * @property Installation $installation
 * @property Product $product
 */
class ProductInstallation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_installation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'installation_id'], 'required'],
            [['product_id', 'installation_id'], 'integer']
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
            'installation_id' => Yii::t('app', 'Installation ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstallation()
    {
        return $this->hasOne(Installation::className(), ['id' => 'installation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
