<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property integer $collection_id
 * @property integer $category_id
 * @property integer $manual_id
 * @property integer $coat_id
 * @property integer $drawing_id
 * @property string $article
 * @property string $series
 * @property string $name
 * @property string $description
 * @property integer $length
 * @property integer $width
 * @property integer $height
 * @property integer $is_promotion
 * @property string $url
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'article', 'series', 'name', 'description', 'url'], 'required'],
            [['shop_id', 'collection_id', 'category_id', 'manual_id', 'coat_id', 'drawing_id', 'length', 'width', 'height', 'is_promotion'], 'integer'],
            [['article', 'series', 'name', 'description', 'url', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'shop_id' => Yii::t('app', 'Shop ID'),
            'collection_id' => Yii::t('app', 'Collection ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'manual_id' => Yii::t('app', 'Manual ID'),
            'coat_id' => Yii::t('app', 'Coat ID'),
            'drawing_id' => Yii::t('app', 'Drawing ID'),
            'article' => Yii::t('app', 'Article'),
            'series' => Yii::t('app', 'Series'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'length' => Yii::t('app', 'Length'),
            'width' => Yii::t('app', 'Width'),
            'height' => Yii::t('app', 'Height'),
            'is_promotion' => Yii::t('app', 'Is Promotion'),
            'url' => Yii::t('app', 'Url'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'meta_keywords' => Yii::t('app', 'Meta Keywords'),
        ];
    }
}
