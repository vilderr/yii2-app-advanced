<?php

namespace common\collects;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class PictureCollection
 * @package common\collects
 */
class PictureCollection extends Model
{
    public $picture;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['picture', 'image', 'extensions' => 'png, jpg, jpeg, gif'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'picture' => 'Изображение',
        ];
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->picture = UploadedFile::getInstance($this, 'picture');

            return true;
        }

        return false;
    }
}