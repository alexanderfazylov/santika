<?php

namespace app\models;

use app\models\scopes\ProductScope;
use Yii;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property integer $collection_id
 * @property integer $category_id
 * @property integer $manual_id
 * @property integer $color_id
 * @property integer $drawing_id
 * @property integer $photo_id
 * @property string $article
 * @property string $name
 * @property string $description
 * @property string $canonical
 * @property integer $length
 * @property integer $width
 * @property integer $height
 * @property integer $is_promotion
 * @property string $url
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 *
 * @property integer[] $line_ids
 * @property integer[] $old_line_ids
 *
 * @property LineProduct[] $lineProducts
 * @property Upload $photo
 * @property Category $category
 * @property Collection $collection
 * @property Shop $shop
 * @property PhotoGallery[] $photoGalleries
 */
class Product extends \yii\db\ActiveRecord
{
    public $line_ids = [];
    public $old_line_ids = [];
    public $photo_tmp;
    public $photo_name;
    public $manual_tmp;
    public $manual_name;
    public $drawing_tmp;
    public $drawing_name;

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
            [['shop_id', 'article', 'name', 'description', 'url'], 'required'],
            [['shop_id', 'collection_id', 'category_id', 'manual_id', 'color_id', 'drawing_id', 'photo_id', 'length', 'width', 'height', 'is_promotion'], 'integer'],
            [['article', 'name', 'description', 'canonical', 'url', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255],
            [['line_ids', 'photo_tmp', 'manual_tmp', 'drawing_tmp', 'photo_name', 'manual_name', 'drawing_name'], 'safe'],
            [['article'], 'unique', 'targetAttribute' => ['shop_id', 'article'], 'message' => 'Товар с указанным артикулом уже существует.']

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
            'shop.name' => 'Салон',
            'collection_id' => 'Коллекция', // Yii::t('app', 'Collection ID'),
            'collection.name' => 'Коллекция', // Yii::t('app', 'Collection ID'),
            'collection_name' => 'Коллекция', // Yii::t('app', 'Collection ID'),
            'category_id' => 'Категория', //Yii::t('app', 'Category ID'),
            'category.name' => 'Категория', //Yii::t('app', 'Category ID'),
            'category_name' => 'Категория', //Yii::t('app', 'Category ID'),
            'line_ids' => 'Линии',
            'photo.fileShowLink' => 'Фото',
            'photo_id' => 'Фото',
            'manual.fileShowLink' => 'Инструкция',
            'manual_id' => 'Инструкция',
            'drawing.fileShowLink' => 'Чертеж',
            'drawing_id' => 'Чертеж',
            'color_id' => 'Покрытие',
            'color.name' => 'Покрытие',
            'article' => 'Артикул', //Yii::t('app', 'Article'),
            'name' => 'Наименование', //Yii::t('app', 'Name'),
            'description' => 'Описание', //Yii::t('app', 'Description'),
            'length' => 'Длина', // Yii::t('app', 'Length'),
            'width' => 'Ширина', // Yii::t('app', 'Width'),
            'height' => 'Высота', // Yii::t('app', 'Height'),
            'is_promotion' => 'Отображать на главной странице', //Yii::t('app', 'Is Promotion'),
            'canonical' => 'Canonical',
            'url' => Yii::t('app', 'Url'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'meta_keywords' => Yii::t('app', 'Meta Keywords'),
        ];
    }


    public function behaviors()
    {
        return [
            'fileSaveBehavior' => [
                'class' => 'app\behaviors\FileSaveBehavior',
            ]
        ];
    }

    public function beforeValidate()
    {
        /**
         * @TODO при поиске в админке выполняются эти функции., мб придумать что то другое?
         */
        $this->saveFileFromAttribute('photo', Upload::TYPE_PRODUCT);
        $this->saveFileFromAttribute('drawing', Upload::TYPE_PRODUCT);
        $this->saveFileFromAttribute('manual', Upload::TYPE_PRODUCT);

        $this->url = Inflector::slug($this->name);
        return parent::beforeValidate();
    }


    /**
     * @inheritdoc
     * @return ProductScope
     */
    public static function find()
    {
        return new ProductScope(get_called_class());
    }

    public function afterSave($insert)
    {
        $line_ids = $this->line_ids == "" ? [] : $this->line_ids;
        $diff_delete = array_diff($this->old_line_ids, $line_ids);
        $diff_insert = array_diff($line_ids, $this->old_line_ids);
        LineProduct::deleteAll(['product_id' => $this->id, 'line_id' => $diff_delete]);
        foreach ($diff_insert as $line_id) {
            $lp = new LineProduct();
            $lp->product_id = $this->id;
            $lp->line_id = $line_id;
            $lp->save();
        }
    }

    public function afterFind()
    {
        /**
         * @TODO оптимизировать
         */
        $line_products = LineProduct::findAll(['product_id' => $this->id]);
        $line_ids = [];
        foreach ($line_products as $line_product) {
            $line_ids[] = $line_product->line_id;
        }
        $this->line_ids = $line_ids;
        $this->old_line_ids = $line_ids;
        parent::afterFind();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLineProducts()
    {
        return $this->hasMany(LineProduct::className(), ['product_id' => 'id']);
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
    public function getCollection()
    {
        return $this->hasOne(Collection::className(), ['id' => 'collection_id']);
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
    public function getPhoto()
    {
        return $this->hasOne(Upload::className(), ['id' => 'photo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManual()
    {
        return $this->hasOne(Upload::className(), ['id' => 'manual_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDrawing()
    {
        return $this->hasOne(Upload::className(), ['id' => 'drawing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'color_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceProduct()
    {
        return $this->hasMany(PriceProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoGalleries()
    {
        return $this->hasMany(PhotoGallery::className(), ['object_id' => 'id']);
    }

    /**
     * Возвращает ДхШхВ товара
     * @return string
     */
    public function getLwh()
    {
        return $this->length . 'x' . $this->width . 'x' . $this->height;
    }


    /**
     * Генерирует ссылку на товар
     * @return string
     */
    public function createUrlByLine($line_url)
    {
        $category = $this->category;
        if (is_null($category)) {
            $category_url = null;
        } else {
            $category_url = $category->url;
        }
        return Url::toRoute(['/catalog/product', 'line_url' => $line_url, 'category_url' => $category_url, 'url' => $this->url]);
    }

}
