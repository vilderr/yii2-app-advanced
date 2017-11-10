<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Class BxSliderAsset
 * @package common\assets
 */
class BxSliderAsset extends AssetBundle
{
    public $sourcePath = '@common/assets/dist';

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->publishOptions = [
            'forceCopy' => YII_ENV_DEV ? true : false,
        ];
    }

    public $js = [
        'js/jquery.bxslider.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}