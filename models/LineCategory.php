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
    public $line_name;
    public $category_name;

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
            [['line_id', 'category_id'], 'required'],
            [['line_id', 'category_id'], 'integer'],
            [['line_id', 'category_id'], 'unique', 'targetAttribute' => ['line_id', 'category_id'], 'message' => 'The combination of Line ID and Category ID has already been taken.']
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
            'category.name' => Yii::t('app', 'category.name ID'),
            'line.name' => Yii::t('app', 'line.name ID'),
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

    /**
     * Возвращает массив из id категорий, которые есть в бд
     * @return array
     * NOT USED
     */
    public static function allCategoryIds()
    {
        $array = self::find()->select('category_id')->asArray()->all();
        $result = [];
        foreach ($array as $a) {
            $result[] = $a['category_id'];
        }
        return $result;
    }
}
