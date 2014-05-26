<?php
/**
 * Created by PhpStorm.
 * User: KURT
 * Date: 24.05.14
 * Time: 14:55
 */

namespace app\behaviors;


use app\models\Upload;
use yii\base\Behavior;

class FileSaveBehavior extends Behavior
{


    /**
     * Сохраняет файл из временной папки в Uploads
     * @param $attribute
     */
    public function saveFileFromAttribute($attribute, $file_type)
    {
        $attr_tmp = $attribute . '_tmp';
        $attr_name = $attribute . '_name';
        $attr_id = $attribute . '_id';
        $model = $this->owner;
        if (!empty($model->$attr_tmp)) {
            if (empty($model->$attr_id)) {
                $upload = new Upload();
            } else {
                $upload = Upload::findOne($model->$attr_id);
            }

            $upload->name = $model->$attr_name;
            $upload->path = Upload::getUploadsPathByType($file_type, $upload->name);

            $source = Upload::getTmpUploadsPath() . $model->$attr_tmp;
            $dest = Upload::getUploadsPath() . $upload->path;
            if (!copy($source, $dest)) {

            }
            $upload->ext = '';
            $upload->save();
            $model->$attr_id = $upload->id;
        }
    }
} 