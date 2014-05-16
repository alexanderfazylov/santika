<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "line_category".
 *
 * @property integer $id
 * @property integer $line_id
 * @property integer $category_id
 *
 * @property Category $category
 * @property Line $line
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLine()
    {
        return $this->hasOne(Line::className(), ['id' => 'line_id']);
    }
}
