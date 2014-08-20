<?php

namespace app\models;

use Yii;
use yii\db\BaseActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "upload".
 *
 * @property integer $id
 * @property string $name
 * @property string $path
 * @property string $ext
 * @property string $thumbnail
 * @property string $file_name
 * @property integer $type
 */
class Upload extends \yii\db\ActiveRecord
{
    const TYPE_TMP = 0;
    const TYPE_PRODUCT = 1;
    const TYPE_COLOR = 2;
    const TYPE_INTERACTIVE = 3;
    const TYPE_PRICE = 4;
    const TYPE_LINE = 5;

    const SIZE_ORIGIN = '';
    const SIZE_SQUARE_245 = 'square_245';
    const SIZE_SQUARE_300 = 'square_300';
    const SIZE_SQUARE_510 = 'square_510';
    const SIZE_RECTANGLE_600_450 = 'rectangle_600_450';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'upload';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'path', 'type'], 'required'],
            [['type'], 'integer'],
            [['name', 'path', 'ext', 'thumbnail'], 'string', 'max' => 255]
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
            'path' => Yii::t('app', 'Path'),
            'ext' => Yii::t('app', 'Ext'),
            'thumbnail' => Yii::t('app', 'Thumbnail'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    public function afterDelete()
    {
        /**
         * @TODO сделать удаление файла при удалении объекта
         */
        parent::afterDelete();
    }

    /**
     * папка для загрузки файдов
     * @return string
     */
    public static function getUploadsPath()
    {
        /**
         * @TODO добавить разделение на салоны
         */
        $upload_dir = Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
        if (!file_exists($upload_dir) && !is_dir($upload_dir)) {
            mkdir($upload_dir, 0777);
        }
        return $upload_dir;
    }

    /**
     * папка для загрузки времнных файдов
     * @param string $size
     * @return string
     */
    public static function getTmpUploadsPath($size = Upload::SIZE_ORIGIN)
    {
        $upload_dir = self::getUploadsPath();
        $upload_folder = Upload::getUploadsPathByType(Upload::TYPE_TMP, $size);
        $upload_tmp = $upload_dir . $upload_folder;

        return $upload_tmp;
    }

    /**
     * Папка по типу файла
     * @param int $type
     * @param string $size
     * @return string
     */
    public static function getUploadsPathByType($type = self::TYPE_PRODUCT, $size = Upload::SIZE_ORIGIN)
    {
        $upload_dir = self::getUploadsPath();
        $sub_dir = static::getDirByType($type);
        $path = $sub_dir . DIRECTORY_SEPARATOR;
        $full_path = $upload_dir . $path;
        if (!file_exists($full_path) && !is_dir($full_path)) {
            mkdir($full_path, 0777);
        }

        if ($size) {
            $path .= $size . DIRECTORY_SEPARATOR;
            $full_path = $upload_dir . $path;
            if (!file_exists($full_path) && !is_dir($full_path)) {
                mkdir($full_path, 0777);
            }
        }

        return $path;
    }

    /**
     * Возвращает уникальное имя файла в папке
     * @param $path
     * @param $name
     * @return string
     */
    public static function getUniquerName($path, $name)
    {
        $path_info = static::fullPathInfo($name);
        $file_name = $path_info['filename'];
        $ext = !empty($path_info['extension']) ? '.' . $path_info['extension'] : '';
        while (is_file($path . $file_name . $ext)) {
            $file_name .= rand(0, 9);
        }
        return $file_name . $ext;
    }

    /**
     * Возвращает название папки по типу
     * @param int $type
     * @return string
     */
    public static function getDirByType($type = 1)
    {
        $dir = '';
        switch ($type) {
            case static::TYPE_TMP:
                $dir = 'tmp';
                break;
            case static::TYPE_PRODUCT:
                $dir = 'product';
                break;
            case static::TYPE_COLOR:
                $dir = 'color';
                break;
            case static::TYPE_INTERACTIVE:
                $dir = 'interactive';
                break;
            case static::TYPE_PRICE:
                $dir = 'price';
                break;
            case static::TYPE_LINE:
                $dir = 'line';
                break;
        }
        return $dir;
    }

    /**
     * Возвращает ссылку на файл или миниатюру
     * @param string $size
     * @return string
     */
    public function getFileShowLink($size = Upload::SIZE_ORIGIN)
    {
        return Html::a($this->name, $this->getFileShowUrl($size));
    }

    /**
     * Возвращает url на файл или миниатюру
     * @param string $size
     * @return string
     */
    public function getFileShowUrl($size = Upload::SIZE_ORIGIN)
    {
        return Url::to(['/default/file-show', 'id' => $this->id, 'size' => $size]);
    }

    /**
     * Возвращает физический путь к файлу
     * @param string $size
     * @return string
     */
    public function getFilePath($size = Upload::SIZE_ORIGIN)
    {
        $uploads_dir = Upload::getUploadsPath();
        $path = static::getUploadsPathByType($type = $this->type, $size);
        $abs_path = $uploads_dir . $path . $this->file_name;
        return addslashes($abs_path);
    }

    /**
     * аналог pathinfo для utf8
     * @param $path_file
     * @return array
     */
    public static function fullPathInfo($path_file)
    {
        $path_file = strtr($path_file, array('\\' => '/'));

        preg_match("~[^/]+$~", $path_file, $file);
        preg_match("~([^/]+)[.$]+(.*)~", $path_file, $file_ext);
        preg_match("~(.*)[/$]+~", $path_file, $dirname);

        return array(
            'dirname' => (isset($dirname[1])) ? $dirname[1] : false,
            'basename' => $file[0],
            'extension' => (isset($file_ext[2])) ? $file_ext[2] : false,
            'filename' => (isset($file_ext[1])) ? $file_ext[1] : $file[0]);
    }

    /**
     * Возвращает ссылку на картинку по умолчанию
     * @param string $size
     * @return string
     */
    public static function defaultFileUrl($size = Upload::SIZE_ORIGIN)
    {
        return Url::toRoute(['/default/file-show', 'id' => 0, 'size' => $size]);
    }
    public function isFileExist(){
        $file_path = $this->getFilePath();
        return file_exists($file_path);
    }

    /**
     * @TODO доделать описение, возврщать все типы? проверить миграцию
     */
    public static function getAllTypes()
    {
        return [
            static::TYPE_LINE,
            static::TYPE_PRODUCT,
            static::TYPE_PRICE,
            static::TYPE_COLOR,
            static::TYPE_INTERACTIVE,
        ];
    }

    public static function getAllSizes()
    {
        return [
            static::SIZE_SQUARE_245,
            static::SIZE_SQUARE_300,
            static::SIZE_SQUARE_510,
            static::SIZE_RECTANGLE_600_450,
        ];
    }
}
