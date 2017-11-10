<?php

namespace frontend\widgets\assets;

use yii\web\AssetBundle;

/**
 * Class CategoryListAsset
 * @package frontend\widgets\assets
 */
class CategoryListAsset extends AssetBundle
{
    public $sourcePath = '@frontend/widgets/assets/dist';

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->publishOptions = [
            'forceCopy' => YII_ENV_DEV ? true : false,
        ];
    }

    public $css = [
        'css/categorylist.min.css',
    ];
}