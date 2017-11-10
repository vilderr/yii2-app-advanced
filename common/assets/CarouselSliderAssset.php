<?php


namespace common\assets;


use yii\web\AssetBundle;

class CarouselSliderAssset extends AssetBundle
{
    public $sourcePath = '@frontend/bootstrap/js/dist';

    public $js = [
        'util.js',
        'carousel.js',
    ];

    public $depends = [
        'common\assets\JqueryAsset',
    ];
}