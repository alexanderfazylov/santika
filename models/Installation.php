<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "installation".
 *
 * @property integer $id
 * @property string $name
 *
 * @property ProductInstallation[] $productInstallations
 */
class Installation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'installation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => 'Наименование',
        ];
    }

    public function transactions()
    {
        return [
            'default' => self::OP_DELETE,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductInstallations()
    {
        return $this->hasMany(ProductInstallation::className(), ['installation_id' => 'id']);
    }

    public function beforeDelete()
    {
        /**
         * Проверка на существование связанных записей, что бы не было ошибок по FK
         */
        $errors = [];
        if ($this->getProductInstallations()->count() != 0) {
            $errors[] = 'Товары';
        }

        if (!empty($errors)) {
            $this->addError('id', 'Нельзя удалить, т.к. есть закрепленные ' . implode(', ', $errors));
            return false;
        }

        return parent::beforeDelete();
    }
}
