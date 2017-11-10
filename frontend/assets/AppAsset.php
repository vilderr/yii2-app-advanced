<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $depends = [
        'yii\web\JqueryAsset',
        'common\assets\fonts\ProximaNovaAsset',
        'frontend\bootstrap\BootstrapAsset',
        'common\assets\fonts\MaterialDesignAsset',
    ];

    public function init()
    {
        $this->publishOptions = [
            'forceCopy' => YII_ENV_DEV ? true : false,
        ];

        $this->css = [
            'css/app.min.css?' . time(),
        ];

        $this->js = [
            'js/theme.js?' . time(),
            'js/plugins/navbar.js?' . time(),
            'js/plugins/jquery.slimscroll.js?' . time(),
        ];
    }
}
