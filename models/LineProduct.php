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
            [['line_id', 'product_id'], 'integer'],
            [['line_id', 'product_id'], 'unique', 'targetAttribute' => ['line_id', 'product_id'], 'message' => 'Комбинация линия - товар уже существует.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'line_id' => Yii::t('app', 'Линия'),
            'product_id' => Yii::t('app', 'Товар'),
            /**
             * @TODO разобраться с атрибутами по связи
             */
            'product.name' => Yii::t('app', 'Товар'),
            'product_name' => Yii::t('app', 'Товар'),
            'line.name' => Yii::t('app', 'Линия'),
            'line_name' => Yii::t('app', 'Линия'),
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
