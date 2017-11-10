<?php

namespace frontend\bootstrap;

use yii\web\AssetBundle;

/**
 * Class BootstrapAsset
 * @package frontend\bootstrap
 */
class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@frontend/bootstrap/dist';

    public function init()
    {
        $this->publishOptions = [
            'forceCopy' => YII_ENV_DEV ? true : false,
        ];

        $this->css = [
            'css/bootstrap.css?' . time(),
        ];
    }
}