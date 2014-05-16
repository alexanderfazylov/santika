<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "line_category".
 *
 * @property integer $id
 * @property integer $line_id
 * @property integer $category_id
 */
class LineCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'line_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['line_id'], 'required'],
            [['line_id', 'category_id'], 'integer']
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
            'category_id' => Yii::t('app', 'Category ID'),
        ];
    }
}
