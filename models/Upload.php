<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "upload".
 *
 * @property integer $id
 * @property string $name
 * @property string $path
 * @property string $ext
 */
class Upload extends \yii\db\ActiveRecord
{
    const TYPE_PRODUCT = 1;
    const TYPE_COLOR = 2;

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
            [['name', 'path'], 'required'],
            [['name', 'path', 'ext'], 'string', 'max' => 255]
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
        ];
    }

    /**
     * папка для загрузки файдов
     * @return string
     */
    public static function getUploadsPath()
    {
        $upload_dir = Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
        if (!file_exists($upload_dir) && !is_dir($upload_dir)) {
            mkdir($upload_dir, 0777);
        }
        return $upload_dir;
    }

    /**
     * папка для загрузки времнных файдов
     * @return string
     */
    public static function getTmpUploadsPath()
    {
        $upload_dir = self::getUploadsPath();
        $upload_tmp = $upload_dir . 'tmp' . DIRECTORY_SEPARATOR;
        if (!file_exists($upload_tmp) && !is_dir($upload_tmp)) {
            mkdir($upload_tmp, 0777);
        }
        return $upload_tmp;
    }

    /**
     * Папка + уникальное имя файла по его типу
     * @param int $type
     * @param $name
     * @return string
     */
    public static function getUploadsPathByType($type = self::TYPE_PRODUCT, $name)
    {
        $upload_dir = self::getUploadsPath();
        $sub_dir = static::getDirByType($type);
        $path = $sub_dir . DIRECTORY_SEPARATOR;
        $full_path = $upload_dir . $path;
        if (!file_exists($full_path) && !is_dir($full_path)) {
            mkdir($full_path, 0777);
        }
        $path_info = static::fullPathInfo($name);
        $file_name = $path_info['filename'];
        $ext = $path_info['extension'];
        while (is_file($full_path . $file_name . '.' . $ext)) {
            $file_name .= rand(0, 9);
        }
        return $path . $file_name . '.' . $ext;
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
            case static::TYPE_PRODUCT:
                $dir = 'product';
                break;
            case static::TYPE_COLOR:
                $dir = 'color';
                break;
        }
        return $dir;
    }

    /**
     * Возвращает ссылку на файл
     * @return string
     */
    public function getFileShowLink()
    {
        return Html::a($this->name, $this->getFileShowUrl());
    }

    /**
     * Возвращает url на файл
     * @return string
     */
    public function getFileShowUrl()
    {
        return Url::to(['/admin/default/file-show', 'file' => $this->path]);
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
}
