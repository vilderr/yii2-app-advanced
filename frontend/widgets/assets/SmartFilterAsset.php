<?php

namespace frontend\widgets\assets;

use yii\web\AssetBundle;

/**
 * Class SmartFilterAsset
 * @package frontend\widgets\assets
 */
class SmartFilterAsset extends AssetBundle
{
    public $sourcePath = '@frontend/widgets/assets/dist';

    public $publishOptions = [
        'forceCopy' => true,
    ];

    public $js = [
        'js/smartfilter.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}