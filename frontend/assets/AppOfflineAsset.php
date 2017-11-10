<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class AppOfflineAsset
 * @package frontend\assets
 */
class AppOfflineAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [];
    public $depends = [
        'frontend\assets\BootstrapGridAsset',
        'common\assets\fonts\ProximaNovaAsset',
        'common\assets\fonts\MaterialDesignAsset',
    ];

    public function init()
    {
        $this->publishOptions = [
            'forceCopy' => YII_ENV_PROD ? true : false,
        ];

        $this->css = [
            'css/offline.min.css?' . time(),
        ];
    }
}