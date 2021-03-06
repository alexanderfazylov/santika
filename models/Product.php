<?php

namespace app\models;

use app\models\scopes\ProductScope;
use dosamigos\transliterator\TransliteratorHelper;
use Yii;
use yii\base\Exception;
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
 * @property integer $color_id @TODO удалить это поле
 * @property integer $drawing_id
 * @property integer $photo_id
 * @property string $article
 * @property string $name
 * @property string $description
 * @property string $canonical
 * @property integer $length
 * @property integer $width
 * @property integer $height
 * @property integer $diameter
 * @property integer $is_promotion
 * @property integer $is_published
 * @property string $url
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 *
 * @property integer[] $line_ids
 * @property integer[] $old_line_ids
 * @property integer[] $color_ids
 * @property integer[] $old_color_ids
 * @property integer[] $installation_ids
 * @property integer[] $old_installation_ids
 * @property integer[] $installation_product_ids
 * @property integer[] $old_installation_product_ids
 *
 * @property InteractiveProduct[] $interactiveProducts
 * @property LineProduct[] $lineProducts
 * @property PriceProduct[] $priceProducts
 * @property Upload $drawing
 * @property Upload $manual
 * @property Upload $photo
 * @property Category $category
 * @property Collection $collection
 * @property Color $color
 * @property Shop $shop
 * @property PhotoGallery[] $photoGalleries
 * @property ProductColor[] $productColors
 * @property ProductInstallation[] $productInstallations
 * @property ProductInstallationProduct[] $productInstallationProducts
 */
class Product extends \yii\db\ActiveRecord
{
    public $use_related_ids = false;
    public $line_ids = [];
    public $old_line_ids = [];
    public $color_ids = [];
    public $old_color_ids = [];
    public $installation_ids = [];
    public $old_installation_ids = [];
    public $installation_product_ids = [];
    public $old_installation_product_ids = [];
    /**
     * @TODO Сделать обертку над $src = !empty($product->photo_id) ? $product->photo->getFileShowUrl($size) : Upload::defaultFileUrl($size)
     */
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
            [['shop_id', 'article', 'name', 'description', 'url', 'line_ids', 'color_ids', 'collection_id', 'category_id'], 'required', 'on' => 'admin'],

            [['shop_id', 'collection_id', 'category_id', 'manual_id', 'color_id', 'drawing_id', 'photo_id', 'length', 'width', 'height', 'diameter', 'is_promotion', 'is_published'], 'integer'],
            [['article', 'name', 'description', 'canonical', 'url', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255],
            [['line_ids', 'color_ids', 'installation_ids', 'installation_product_ids', 'photo_tmp', 'manual_tmp', 'drawing_tmp', 'photo_name', 'manual_name', 'drawing_name'], 'safe'],
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
            'color_ids' => 'Покрытия',
            'installation_ids' => 'Способ монтажа',
            'installation_product_ids' => 'Монтажные элементы и комплектующие',
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
            'diameter' => 'Диаметр', // Yii::t('app', 'Height'),
            'is_promotion' => 'Отображать на главной странице', //Yii::t('app', 'Is Promotion'),
            'is_published' => 'Опубликовано',
            'canonical' => 'Canonical',
            'url' => Yii::t('app', 'Url'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'meta_keywords' => Yii::t('app', 'Meta Keywords'),
        ];
    }

    public function transactions()
    {
        return [
            'default' => self::OP_DELETE,
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

        $this->url = Inflector::slug(TransliteratorHelper::process($this->name));
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

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->use_related_ids) {
            $this->saveLines();
            $this->saveColors();
            $this->saveInstallations();
            $this->saveInstallationProducts();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeDelete()
    {
        /**
         * Проверка на существование связанных записей, что бы не было ошибок по FK
         */
        $errors = [];
        if ($this->getPhotoGalleries()->count() != 0) {
            $errors[] = 'Фотогарелея';
        }
        if ($this->getShowWith()->count() != 0) {
            $errors[] = 'Отображать вместе с другим товаром';
        }
        if ($this->getProductInstallationProductsAlter()->count() != 0) {
            $errors[] = 'В качестве монтажных элементов';
        }

        if (!empty($errors)) {
            $this->addError('id', 'Нельзя удалить, т.к. есть закрепленные ' . implode(', ', $errors));
            return false;
        }

        foreach ($this->getLineProducts()->all() as $lk) {
            $lk->delete();
        }
        foreach ($this->getPriceProducts()->all() as $pp) {
            $pp->delete();
        }
        foreach ($this->getProductColors()->all() as $pc) {
            $pc->delete();
        }
        foreach ($this->getProductInstallations()->all() as $pi) {
            $pi->delete();
        }
        foreach ($this->getProductInstallationProducts()->all() as $pip) {
            $pip->delete();
        }
        foreach ($this->getInteractiveProducts()->all() as $ip) {
            $ip->delete();
        }
        foreach ($this->getShowWithAlter()->all() as $swa) {
            $swa->delete();
        }
        return parent::beforeDelete();
    }

    public function afterDelete()
    {
        if ($photo = $this->getPhoto()->one()) {
            $photo->delete();
        }
        if ($manual = $this->getManual()->one()) {
            $manual->delete();
        }
        if ($drawing = $this->getDrawing()->one()) {
            $drawing->delete();
        }
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    /**
     * Подготавливает массив линий и покрытий
     */
    public function prepare()
    {
        $this->use_related_ids = 1;
        $this->prepareLines();
        $this->prepareColors();
        $this->prepareInstallations();
        $this->prepareInstallationProducts();
    }


    /**
     * Вытаскивает все линии товара
     */
    public function prepareLines()
    {
        $line_products = LineProduct::findAll(['product_id' => $this->id]);
        $line_ids = [];
        foreach ($line_products as $line_product) {
            $line_ids[] = $line_product->line_id;
        }
        $this->line_ids = $line_ids;
        $this->old_line_ids = $line_ids;
    }

    /**
     * Сохраняет разницу в выбранных линиях
     */
    public function saveLines()
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

    /**
     * Вытаскивает все покрытия товара
     */
    public function prepareColors()
    {
        $product_colors = ProductColor::findAll(['product_id' => $this->id]);
        $color_ids = [];
        foreach ($product_colors as $product_color) {
            $color_ids[] = $product_color->color_id;
        }
        $this->color_ids = $color_ids;
        $this->old_color_ids = $color_ids;
    }

    /**
     * Сохраняет разницу в выбранных покрытиях
     */
    public function saveColors()
    {
        $color_ids = $this->color_ids == "" ? [] : $this->color_ids;
        $diff_delete = array_diff($this->old_color_ids, $color_ids);
        $diff_insert = array_diff($color_ids, $this->old_color_ids);
        ProductColor::deleteAll(['product_id' => $this->id, 'color_id' => $diff_delete]);
        foreach ($diff_insert as $color_id) {
            $pc = new ProductColor();
            $pc->product_id = $this->id;
            $pc->color_id = $color_id;
            $pc->save();
        }
    }


    /**
     * Вытаскивает все способы монтажа товара
     */
    public function prepareInstallations()
    {
        $product_installations = ProductInstallation::findAll(['product_id' => $this->id]);
        $installation_ids = [];
        foreach ($product_installations as $product_installation) {
            $installation_ids[] = $product_installation->installation_id;
        }
        $this->installation_ids = $installation_ids;
        $this->old_installation_ids = $installation_ids;
    }

    /**
     * Сохраняет разницу в выбранных способах монтажа
     */
    public function saveInstallations()
    {
        $installation_ids = $this->installation_ids == "" ? [] : $this->installation_ids;
        $diff_delete = array_diff($this->old_installation_ids, $installation_ids);
        $diff_insert = array_diff($installation_ids, $this->old_installation_ids);
        Productinstallation::deleteAll(['product_id' => $this->id, 'installation_id' => $diff_delete]);
        foreach ($diff_insert as $installation_id) {
            $pi = new Productinstallation();
            $pi->product_id = $this->id;
            $pi->installation_id = $installation_id;
            $pi->save();
        }
    }

    /**
     * Вытаскивает все Монтажные элементы и комплектующие товара
     */
    public function prepareInstallationProducts()
    {
        $product_installation_products = ProductInstallationProduct::findAll(['product_id' => $this->id]);
        $installation_product_ids = [];
        foreach ($product_installation_products as $product_installation_product) {
            $installation_product_ids[] = $product_installation_product->installation_product_id;
        }
        $this->installation_product_ids = $installation_product_ids;
        $this->old_installation_product_ids = $installation_product_ids;
    }

    /**
     * Сохраняет разницу в выбранных Монтажных элементах и комплектующих
     */
    public function saveInstallationProducts()
    {
        $installation_product_ids = $this->installation_product_ids == "" ? [] : $this->installation_product_ids;
        $diff_delete = array_diff($this->old_installation_product_ids, $installation_product_ids);
        $diff_insert = array_diff($installation_product_ids, $this->old_installation_product_ids);
        ProductInstallationProduct::deleteAll(['product_id' => $this->id, 'installation_product_id' => $diff_delete]);
        foreach ($diff_insert as $installation_product_id) {
            $pip = new ProductInstallationProduct();
            $pip->product_id = $this->id;
            $pip->installation_product_id = $installation_product_id;
            $pip->save();
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInteractiveProducts()
    {
        return $this->hasMany(InteractiveProduct::className(), ['product_id' => 'id']);
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
    public function getPriceProducts()
    {
        return $this->hasMany(PriceProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductColors()
    {
        return $this->hasMany(ProductColor::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductInstallations()
    {
        return $this->hasMany(ProductInstallation::className(), ['product_id' => 'id']);
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
     * @throws \yii\base\Exception
     * @return \yii\db\ActiveQuery
     * @deprecated
     */
    public function getPriceProduct()
    {
        throw new Exception("Use getPriceProducts"); //return $this->hasMany(PriceProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoGalleries()
    {
        /**
         * @TODO нет связи в БД
         * @TODO добавить type? ->onCondition(['type' => PhotoGallery::TYPE_PRODUCT])
         */
        return $this->hasMany(PhotoGallery::className(), ['object_id' => 'id']);
    }

    /**
     * Возвращает связь с товаром в котором отображается текущий товар
     * @return \yii\db\ActiveQuery
     */
    public function getShowWith()
    {
        /**
         * @TODO нет связи в БД
         * @TODO добавить type? ->onCondition(['type' => PhotoGallery::TYPE_PRODUCT])
         */
        return $this->hasMany(ShowWith::className(), ['product_id' => 'id'])
            ->andWhere([ShowWith::tableName() . '.type' => ShowWith::TYPE_PRODUCT]);
    }


    /**
     * Возвращает связь с товарами которые отображаются вместе с текущим
     * @return \yii\db\ActiveQuery
     */
    public function getShowWithAlter()
    {
        /**
         * @TODO нет связи в БД
         * @TODO добавить type? ->onCondition(['type' => PhotoGallery::TYPE_PRODUCT])
         */
        return $this->hasMany(ShowWith::className(), ['object_id' => 'id'])
            ->andWhere([ShowWith::tableName() . '.type' => ShowWith::TYPE_PRODUCT]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductInstallationProducts()
    {
        return $this->hasMany(ProductInstallationProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductInstallationProductsAlter()
    {
        return $this->hasMany(ProductInstallationProduct::className(), ['installation_product_id' => 'id']);
    }

    /**
     * Возвращает ДхШхВ товара
     * @return string
     */
    public function getLwh()
    {
        $array = [];
        if (!is_null($this->length)) {
            $array[] = $this->length;
        }
        if (!is_null($this->width)) {
            $array[] = $this->width;
        }
        if (!is_null($this->height)) {
            $array[] = $this->height;
        }
        return implode(' x ', $array);
    }

    /**
     * Возвращает строку с ДхШхВ товара
     * @return string
     */
    public function getLwhString()
    {
        $array = [];
        if (!is_null($this->length)) {
            $array[] = 'Д';
        }
        if (!is_null($this->width)) {
            $array[] = 'Ш';
        }
        if (!is_null($this->height)) {
            $array[] = 'В';
        }
        $result = implode(' x ', $array);
        if ($result) {
            $result .= ':';
        }
        return $result;
    }


    /**
     * Генерирует ссылку на товар
     * @param $line_url
     * @return string
     */
    public function createUrlByLine($line_url)
    {
        /**
         * @TODO $this->category делает дополнительный sql запрос
         */
        $category = $this->category;
        if (is_null($category)) {
            $category_url = null;
        } else {
            $category_url = $category->url;
        }
        return Url::toRoute(['/catalog/product', 'line_url' => $line_url, 'category_url' => $category_url, 'url' => $this->url]);
    }

}
