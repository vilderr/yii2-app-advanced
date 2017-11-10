<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class AuthAsset
 * @package backend\assets
 */
class AuthAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'common\assets\fonts\ProximaNovaAsset',
        'common\assets\fonts\MaterialDesignAsset',
    ];

    public function init()
    {
        $this->publishOptions = [
            'forceCopy' => YII_ENV_DEV ? true : false,
        ];

        $this->css = [
            'css/auth.css?' . time(),
        ];
    }
}