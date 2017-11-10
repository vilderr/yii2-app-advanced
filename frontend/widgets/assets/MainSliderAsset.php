<?php

namespace frontend\widgets\assets;

use yii\web\AssetBundle;

/**
 * Class MainSliderAsset
 * @package frontend\widgets\assets
 */
class MainSliderAsset extends AssetBundle
{
    public $sourcePath = '@frontend/widgets/assets/dist';

    public $publishOptions = [
        'forceCopy' => true,
    ];

    public $depends = [
        'common\assets\BxSliderAsset',
    ];

    public function init()
    {
        $this->css = [
            'css/mainslider.min.css?' . time(),
        ];
    }
}