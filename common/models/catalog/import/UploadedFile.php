<?php

namespace common\models\catalog\import;

/**
 * Class UploadedFile
 * @package common\models\catalog\import
 */
class UploadedFile extends \yii\web\UploadedFile
{
    public function saveAs($file, $deleteTempFile = true)
    {
        if ($this->error == UPLOAD_ERR_OK) {
            return copy($this->tempName, $file);
        }

        return false;
    }
}