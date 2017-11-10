<?php

namespace common\listeners\catalog\product;

use yii\helpers\FileHelper;
use common\models\catalog\product\events\PictureAssigned;
use common\models\catalog\product\Product;
use common\models\catalog\import\UploadedFile;

/**
 * Class PictureDownloadListener
 * @package common\listeners\catalog\product
 */
class PictureDownloadListener
{
    public function handle(PictureAssigned $event)
    {
        if ($event->entity instanceof Product) {
            $product = $event->entity;

            $pathinfo = pathinfo($product->remote_picture_url);
            UploadedFile::reset();

            $_FILES[$product->id . '_files'] = [
                'name'     => $pathinfo['basename'],
                'type'     => FileHelper::getMimeTypeByExtension($product->remote_picture_url),
                'tmp_name' => $product->remote_picture_url,
                'size'     => 0,
                'error'    => 0,
            ];

            $product->addPicture(UploadedFile::getInstanceByName($product->id . '_files'));
            $product->save();
        }
    }
}