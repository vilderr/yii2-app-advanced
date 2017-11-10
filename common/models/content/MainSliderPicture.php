<?php

namespace common\models\content;

use common\models\Picture;
use common\models\behaviors\ImageUploadBehavior;

/**
 * Class MainSliderPicture
 * @package common\models\content
 */
class MainSliderPicture extends Picture
{
    public function behaviors()
    {
        return [
            'upload' => [
                'class'                 => ImageUploadBehavior::class,
                'attribute'             => 'file',
                'createThumbsOnRequest' => true,
                'filePath'              => '@frontend/web/upload/tmp/[[id]].[[extension]]',
                'fileUrl'               => '@static/upload/tmp/[[id]].[[extension]]',
                'thumbPath'             => '@frontend/web/upload/thumbs/[[attribute_model_name]]/[[profile]]_[[id]].[[extension]]',
                'thumbUrl'              => '@static/upload/thumbs/[[attribute_model_name]]/[[profile]]_[[id]].[[extension]]',
                'thumbs'                => [
                    'admin'   => ['width' => 250, 'height' => 250],
                    'picture' => [
                        'width'       => 1450,
                        'height'      => 370,
                        'jpegQuality' => 75,
                    ],
                ],
            ],
        ];
    }
}