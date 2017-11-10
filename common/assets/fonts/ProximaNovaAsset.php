<?php

namespace common\assets\fonts;

use yii\web\AssetBundle;

/**
 * Class ProximaNovaAsset
 * @package app\assets\fonts
 */
class ProximaNovaAsset extends AssetBundle
{
    public $sourcePath = '@common/assets/fonts/proxima';

    public $css = [];

    public $publishOptions = [
        'forceCopy' => true,
    ];

    public function init()
    {
        $this->css = [
            'css/proximanova.min.css?' . time(),
        ];
    }
}