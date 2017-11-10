<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'                  => 'app-console',
    'language'            => 'ru-RU',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => [
        'log',
        'common\SetUp',
    ],
    'controllerNamespace' => 'console\controllers',
    'controllerMap'       => [
        'fixture' => [
            'class'     => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
        'migrate' => [
            'class'          => 'fishvision\migrate\controllers\MigrateController',
            'autoDiscover'   => true,
            'migrationPaths' => [
                '@vendor/yiisoft/yii2/rbac/migrations',
            ],
        ],
    ],
    'components'          => [
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class'          => 'yii\log\FileTarget',
                    'exportInterval' => 1,
                    'categories'     => ['info'],
                    'levels'         => ['info'],
                    'logFile'        => '@runtime/info.log',
                    'logVars'        => [],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class'   => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
            ],
        ],
    ],
    'params'              => $params,
];
