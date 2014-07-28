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
            [['line_id', 'category_id'], 'unique', 'targetAttribute' => ['line_id', 'category_id'], 'message' => 'Комбинация линия - категория уже существует.']
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
            'category_id' => Yii::t('app', 'Категория'),
            /**
             * @TODO разобраться с атрибутами по связи
             */
            'category.name' => Yii::t('app', 'Категория'),
            'category_name' => Yii::t('app', 'Категория'),
            'line.name' => Yii::t('app', 'Линия'),
            'line_name' => Yii::t('app', 'Линия'),
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

    /**
     * Возвращает массив из id категорий, которые есть в бд
     * @return array
     * NOT USED
     */
    public static function categoryIdsByLine($line_id)
    {
        $array = self::find()
            ->select('category_id')
            ->andWhere(['line_id' => $line_id])
            ->asArray()->all();
        $result = [];
        foreach ($array as $a) {
            $result[] = $a['category_id'];
        }
        return $result;
    }
}
