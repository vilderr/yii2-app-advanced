<?php

namespace common\models\catalog\category;

use common\models\Picture;
use common\models\behaviors\ImageUploadBehavior;

/**
 * Class CategoryPicture
 * @mixin ImageUploadBehavior
 * @package common\models\catalog\category
 */
class CategoryPicture extends Picture
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
                    'admin'   => ['width' => 60, 'height' => 60],
                    'picture' => [
                        'width'       => 60,
                        'height'      => 60,
                        'jpegQuality' => 75,
                    ],
                ],
            ],
        ];
    }
}