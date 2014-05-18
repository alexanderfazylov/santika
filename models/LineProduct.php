<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "line_product".
 *
 * @property integer $id
 * @property integer $line_id
 * @property integer $product_id
 *
 * @property Product $product
 * @property Line $line
 */
class LineProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'line_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['line_id', 'product_id'], 'required'],
            [['line_id', 'product_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'line_id' => Yii::t('app', 'Line ID'),
            'product_id' => Yii::t('app', 'Product ID'),
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
    public function getLine()
    {
        return $this->hasOne(Line::className(), ['id' => 'line_id']);
    }
}
