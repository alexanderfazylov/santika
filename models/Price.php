<?php

namespace app\models;

use app\models\scopes\PriceScope;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use Yii;
use yii\base\Exception;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "price".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $start_date
 * @property integer $type$id
 * @property integer $import_id
 *
 * @property PriceProduct[] $priceProducts
 * @property Upload $import
 */
class Price extends \yii\db\ActiveRecord
{
    const TYPE_PRODUCT = 1;
    const TYPE_SERVICE = 2;

    public $import_tmp;
    public $import_name;
    public $article_column;
    public $cost_column;

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
            [['shop_id', 'type', 'import_id', 'article_column', 'cost_column'], 'integer'],
            [['start_date', 'type'], 'unique', 'targetAttribute' => ['shop_id', 'start_date', 'type'], 'message' => 'Прайс лист на выбранную дату уже существует.'],
            [['start_date', 'import_tmp', 'import_name'], 'safe'],
            [['article_column', 'cost_column'], 'required'
                , 'when' => function ($model, $attribute) {
                    return !empty($model->import_tmp);
                }
                , 'whenClient' => 'function (attribute, value) {
                    return  $("#price-import_tmp").val() != "";
                }',
            ],
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
            'import_id' => 'Файл импорта',
            'article_column' => '№ столбца с артикулом',
            'cost_column' => '№ столбца с ценой',
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
        $this->saveFileFromAttribute('import', Upload::TYPE_PRICE);

        return parent::beforeValidate();
    }

    public function afterSave($insert)
    {
        /**
         * @TODO доп проверки на импорт
         */
        if (!empty($this->import_id) && !empty($this->article_column) && !empty($this->cost_column)) {
            $file = $this->import->getFilePath();
            if (file_exists($file)) {
                //  Read your Excel workbook
                try {
                    $inputFileType = PHPExcel_IOFactory::identify($file);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($file);
                    $worksheet = $objPHPExcel->getActiveSheet();
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                    if ($highestColumnIndex < $this->article_column || $highestColumnIndex < $this->cost_column) {
                        throw new Exception('Не корректный номер строки');
                    }
                    $start_row = 1;

                    $curs_eur = Currency::getEurValue();
                    $import_count = 0;
                    $error_count = 0;
                    for ($row = $start_row; $row <= $highestRow; ++$row) {
                        $article = $worksheet->getCellByColumnAndRow($this->article_column, $row)->getValue();
                        $cost_eur = $worksheet->getCellByColumnAndRow($this->cost_column, $row)->getValue();
                        $product = Product::find()->byShop($this->shop_id)->byArticle($article)->one();
                        if (!is_null($product)) {
                            $price_product = PriceProduct::findOne(['price_id' => $this->id, 'product_id' => $product->id]);

                            if (empty($price_product)) {
                                $price_product = new PriceProduct();
                                $price_product->price_id = $this->id;
                                $price_product->product_id = $product->id;
                            }
                            $price_product->cost_eur = $cost_eur;
                            $price_product->cost_rub = $cost_eur * $curs_eur;
                            $price_product->save();
                            $import_count++;
                        } else {
                            $error_count++;
                        }
                    }
                    Yii::$app->getSession()->setFlash('importPrice', "Импортировано цен: $import_count. Не найдено товаров: $error_count");
                } catch (Exception $e) {
                    throw new Exception($e);
                }
            }
        }
        parent::afterSave($insert); // TODO: Change the autogenerated stub
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


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImport()
    {
        return $this->hasOne(Upload::className(), ['id' => 'import_id']);
    }
}
