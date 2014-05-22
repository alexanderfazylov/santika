<?php

namespace app\models;

use Yii;

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
            [['name', 'path', 'ext'], 'required'],
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
     * папка для загрузки времнных файдов
     * @return string
     */
    public static function getUploadsPathByType($type = 1, $name)
    {
        $upload_dir = self::getUploadsPath();
        $path = 'product' . DIRECTORY_SEPARATOR;
        $full_path = $upload_dir . $path;
        if (!file_exists($full_path) && !is_dir($full_path)) {
            mkdir($full_path, 0777);
        }
        return $path . $name;
    }


}
