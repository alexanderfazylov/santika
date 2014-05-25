<?php

namespace app\models;

use app\models\scopes\PriceScope;
use Yii;

/**
 * This is the model class for table "price".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $start_date
 * @property integer $type
 *
 * @property PriceProduct[] $priceProducts
 */
class Price extends \yii\db\ActiveRecord
{
    const TYPE_PRODUCT = 1;
    const TYPE_SERVICE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'start_date', 'type'], 'required'],
            [['shop_id', 'type'], 'integer'],
            [['start_date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'shop_id' => 'Салон',
            'shop.name' => 'Салон',
            'start_date' => 'Начало действия прайса',
            'type' => 'Тип',
            'typeText' => 'Тип',
        ];
    }

    /**
     * @inheritdoc
     * @return PriceScope
     */
    public static function find()
    {
        return new PriceScope(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceProducts()
    {
        return $this->hasMany(PriceProduct::className(), ['price_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }

    public static function getTypesText()
    {
        return [
            static::TYPE_PRODUCT => 'Товары',
            static::TYPE_SERVICE => 'Услуги',
        ];
    }

    public function getTypeText()
    {
        if (empty($this->type)) {
            return null;
        }
        return self::getTypesText()[$this->type];
    }
}
