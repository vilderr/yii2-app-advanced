<?php

namespace common\assets\fonts;

use yii\web\AssetBundle;

/**
 * Class MaterialDesignAsset
 * @package common\assets\fonts
 */
class MaterialDesignAsset extends AssetBundle
{
    public $sourcePath = '@common/assets/fonts/mdi';

    public $css = [
        'css/materialdesignicons.min.css',
    ];
}