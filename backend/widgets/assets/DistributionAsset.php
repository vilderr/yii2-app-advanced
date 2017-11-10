<?php

namespace backend\widgets\assets;

use yii\web\AssetBundle;

/**
 * Class DistributionAsset
 * @package backend\widgets\assets
 */
class DistributionAsset extends AssetBundle
{
    public $sourcePath = '@backend/widgets/assets/dist';

    public $js = [
        'js/distribution.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];

    public $publishOptions = [
        'forceCopy' => true,
    ];
}