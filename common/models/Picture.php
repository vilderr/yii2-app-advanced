<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%picture}}".
 *
 * @property integer $id
 * @property string  $model_name
 * @property string  $file
 */
class Picture extends \yii\db\ActiveRecord
{
    /**
     * @param UploadedFile $picture
     * @param              $model_name
     *
     * @return Picture
     */
    public static function create(UploadedFile $picture, $model_name)
    {
        return new static([
            'model_name' => $model_name,
            'file'       => $picture,
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pictures}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'model_name' => 'Model Name',
            'file'       => 'File',
        ];
    }
}
