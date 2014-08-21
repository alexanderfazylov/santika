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
     * @param $file_type
     */
    public function saveFileFromAttribute($attribute, $file_type)
    {
        $attr_tmp = $attribute . '_tmp';
        $attr_name = $attribute . '_name';
        $attr_id = $attribute . '_id';
        $model = $this->owner;
        if (!empty($model->$attr_tmp)) {
            $upload = null;
            if (!empty($model->$attr_id)) {
                $upload = Upload::findOne($model->$attr_id);
            }
            if (is_null($upload)) {
                $upload = new Upload();
            }
            $name = $model->$attr_name;

            $uploads_dir = Upload::getUploadsPath();
            //путь до временного файла
            $source_folder = Upload::getUploadsPathByType(Upload::TYPE_TMP);
            $source = $uploads_dir . $source_folder . $model->$attr_tmp;

            $dest_folder = Upload::getUploadsPathByType($file_type);
            $unique_name = Upload::getUniquerName($uploads_dir . $dest_folder, $name);
            $path = $dest_folder . $unique_name;
            $dest = $uploads_dir . $path;
            if (!copy($source, $dest)) {
                /**
                 * @TODO обработать ошибку
                 */
            }

            $image_sizes = array(
                Upload::SIZE_SQUARE_245,
                Upload::SIZE_SQUARE_300,
                Upload::SIZE_SQUARE_510,
                Upload::SIZE_RECTANGLE_600_450,
            );
            foreach ($image_sizes as $image_size) {
                //переносим миниатуюру то же
                //путь до временного файла
                $source_thumb_folder = Upload::getUploadsPathByType(Upload::TYPE_TMP, $image_size);
                $source_thumb = $uploads_dir . $source_thumb_folder . $model->$attr_tmp;
                if (file_exists($source_thumb)) {
                    //если загружена картинка, то у нее есть миниатюра
                    $dest_thumb_folder = Upload::getUploadsPathByType($file_type, $image_size);
                    $size_path = $dest_thumb_folder . $unique_name;
                    $dest_thumb = $uploads_dir . $size_path;

                    if (!copy($source_thumb, $dest_thumb)) {
                        /**
                         * @TODO обработать ошибку
                         */
                    }
                }
            }
            $upload->type = $file_type;
            $upload->name = $name;
            $upload->path = $path;
            $upload->file_name = $unique_name;
            $upload->ext = '';
            $upload->save();

            $model->$attr_id = $upload->id;
        }
    }
} 