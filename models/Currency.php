<?php

namespace app\models;

use app\components\CBRCurrency;
use Yii;

/**
 * This is the model class for table "Currency".
 *
 * @property integer $id
 * @property string $name
 * @property double $value
 * @property string $cdate
 */
class Currency extends \yii\db\ActiveRecord
{
    const EUR = 'EUR';
    const EUR_CODE = '978';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'value', 'cdate'], 'required'],
            [['value'], 'number'],
            [['cdate'], 'safe'],
            [['name'], 'string', 'max' => 255]
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
            'value' => Yii::t('app', 'Value'),
            'cdate' => Yii::t('app', 'Cdate'),
        ];
    }

    public static function getEurValue($cdate = null)
    {
        if (is_null($cdate)) {
            $cdate = date('Y-m-d');
        }
        $currency = static::findOne(['name' => static::EUR, 'cdate' => $cdate]);
        if (is_null($currency)) {
            $cbr_date = date('d/m/Y', strtotime($cdate));
            $value = CBRCurrency::getValue(static::EUR_CODE, $cbr_date);

            $currency = new Currency();
            $currency->name = static::EUR;
            $currency->value = $value;
            $currency->cdate = $cdate;
            $currency->save();
        }
        return $currency->value;
    }
}
