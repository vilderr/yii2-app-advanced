<?php

namespace backend\widgets\assets;

use yii\web\AssetBundle;

/**
 * Class ImportAsset
 * @package backend\widgets\assets
 */
class ImportAsset extends AssetBundle
{
    public $sourcePath = '@backend/widgets/assets/dist';

    public $js = [
        'js/import.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];

    public $publishOptions = [
        'forceCopy' => true,
    ];
}