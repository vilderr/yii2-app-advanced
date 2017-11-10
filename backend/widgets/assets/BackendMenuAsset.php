<?php

namespace backend\widgets\assets;

use yii\web\AssetBundle;

/**
 * Class BackendMenuAsset
 * @package backend\widgets\assets
 */
class BackendMenuAsset extends AssetBundle
{
    public $sourcePath = '@backend/widgets/assets/dist';

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        $this->css = [
            'css/mainmenu.css?' . time(),
        ];

        $this->js = [
            'js/mainmenu.js?' . time(),
        ];
    }
}