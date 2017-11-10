<?php

namespace common\models\catalog\product;

use common\models\Picture;
use common\models\behaviors\ImageUploadBehavior;

/**
 * Class ProductPicture
 * @package common\models\catalog\product
 */
class ProductPicture extends Picture
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
                    'list_picture' => [
                        'width'       => 200,
                        'height'      => 150,
                        'jpegQuality' => 75,
                    ],
                    'detail_picture' => [
                        'width'       => 600,
                        'height'      => 450,
                        'jpegQuality' => 75,
                    ],
                ],
            ],
        ];
    }
}