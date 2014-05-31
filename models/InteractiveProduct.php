<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "interactive_product".
 *
 * @property integer $id
 * @property integer $interactive_id
 * @property integer $product_id
 * @property double $left
 * @property double $top
 *
 * @property Interactive $interactive
 * @property Product $product
 */
class InteractiveProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'interactive_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['interactive_id', 'product_id', 'left', 'top'], 'required'],
            [['interactive_id', 'product_id'], 'integer'],
            [['left', 'top'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'interactive_id' => Yii::t('app', 'Interactive ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'left' => Yii::t('app', 'Left'),
            'top' => Yii::t('app', 'Top'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInteractive()
    {
        return $this->hasOne(Interactive::className(), ['id' => 'interactive_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
