<?php


namespace frontend\widgets\assets;


use yii\web\AssetBundle;

class ProductTopSliderAsset extends AssetBundle
{
    public $sourcePath = '@frontend/widgets/assets/dist';

    public $publishOptions = [
        'forceCopy' => true,
    ];

    public $css = [
        'css/producttopslider.min.css',
    ];

    public $depends = [
        'common\assets\BxSliderAsset',
    ];
}