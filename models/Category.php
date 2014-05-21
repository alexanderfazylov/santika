<?php

namespace app\models;

use app\models\scopes\CategoryScope;
use Yii;
use yii\base\Exception;
use yii\db\Query;
use yii\helpers\Inflector;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property integer $parent_id
 * @property string $name
 * @property integer $sort
 * @property string $url
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 *
 * @property Category $parent
 * @property Category[] $categories
 * @property Shop $shop
 * @property LineCategory[] $lineCategories
 * @property Product[] $products
 */
class Category extends \yii\db\ActiveRecord
{
    public $line_ids = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'name', 'url'], 'required'],
            [['shop_id', 'parent_id', 'sort'], 'integer'],
            [['name', 'url', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255],
            [['line_ids'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'shop_id' => 'Салон', //Yii::t('app', 'Shop ID'),
            'parent_id' => 'Родительская категория',//Yii::t('app', 'Parent ID'),
            'name' => 'Название',//Yii::t('app', 'Name'),
            'sort' => 'Сортировка',//Yii::t('app', 'Sort'),
            'url' => Yii::t('app', 'Url'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'meta_keywords' => Yii::t('app', 'Meta Keywords'),
        ];
    }


    public function beforeValidate()
    {
        $this->url = Inflector::slug($this->name);
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert)
    {
        /**
         * @TODO проверить работу, мб в других местах будут баги
         */
        LineCategory::deleteAll(['category_id' => $this->id]);
        foreach ($this->line_ids as $line_id) {
            $lc = new LineCategory();
            $lc->category_id = $this->id;
            $lc->line_id = $line_id;
            $lc->save();
        }
    }

    /**
     * @inheritdoc
     * @return CategoryScope
     */
    public static function find()
    {
        return new CategoryScope(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLineCategories()
    {
        return $this->hasMany(LineCategory::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }

    /**
     * Возвращает категории которые не прикреплены к линии или которые находятся в текущей связи линия-категория
     * @param $shop_id
     * @param $line_category_id
     * @return Category[]
     */
    public static function withOutLine($shop_id, $line_category_id)
    {
        $query_for_id = (new Query)->select('category_id')->from('line_category');
        if (!empty($line_category_id)) {
            $query_for_id->andWhere(['not in', 'id', $line_category_id]);
        }
        $query = Category::find();
        $query->select = ['id', 'name'];
        $query->andWhere(['not in', 'id', $query_for_id]);
        return $query->byShop($shop_id)->all();
    }

    /**
     * Возвращает массив категорий находящихся в указанных линиях
     * @param int $shop_id
     * @param array|Query $line_ids
     * @throws \yii\base\Exception
     * @return Category[]
     */
    public static function byLineIds($shop_id, $line_ids)
    {
        $query_for_category_id = (new Query)->select('category_id')->from('line_category');
        if (is_array($line_ids)) {
            $query_for_category_id->where(['line_id' => $line_ids]);
        } elseif ($line_ids instanceof Query) {
            $query_for_category_id->where(['in', 'line_id', $line_ids]);
        } else {
            return [];
        }
        $query = Category::find();
        $query->select = ['id', 'name'];
        $query->andWhere(['in', 'id', $query_for_category_id]);
        return $query->byShop($shop_id)->all();
    }

    /**
     * Возвращает массив категорий находящихся в линиях выбранного продукта
     * @param $shop_id
     * @param $product_id
     * @return Category[]
     */
    public static function byProductId($shop_id, $product_id)
    {
        $query_for_line_id = (new Query)->select('line_id')->from('line_product')->where(['product_id' => $product_id]);
        return self::byLineIds($shop_id, $query_for_line_id);

    }
}
