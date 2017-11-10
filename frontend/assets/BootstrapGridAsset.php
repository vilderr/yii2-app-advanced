<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class BootstrapGridAsset
 * @package frontend\assets
 */
class BootstrapGridAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/dist';

    public $css = [
        'css/bootstrap-reboot.min.css',
        'css/bootstrap-grid.min.css',
    ];
}