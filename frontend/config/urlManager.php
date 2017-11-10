<?php
return [
    'class'               => 'yii\web\UrlManager',
    'enablePrettyUrl'     => true,
    'showScriptName'      => false,
    'enableStrictParsing' => true,
    'suffix'              => '/',
    'rules'               => [
        '/'                         => 'app/index',
        'catalog/product/<id:\d+>/' => 'catalog/product',
        'catalog/goto/<id:\d+>/' => 'catalog/goto',
        ['class' => 'frontend\urls\CatalogUrlRules'],
    ],
];