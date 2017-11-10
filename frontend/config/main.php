<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'                  => 'app-frontend',
    'name'                => 'Bigmall',
    'language'            => 'ru-RU',
    'basePath'            => dirname(__DIR__),
    'aliases'             => [
        '@static' => $params['staticHostInfo'],
    ],
    'homeUrl' => '/',
    'bootstrap'           => [
        'log',
        'common\SetUp',
        'frontend\SetUp'
    ],
    'controllerNamespace' => 'frontend\controllers',
    'components'          => [
        'request'      => [
            'csrfParam'           => '_csrf-frontend',
            'cookieValidationKey' => $params['cookieValidationKey'],
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\user\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity',
                'httpOnly' => true,
                'domain' => $params['cookieDomain'],
            ],
            'loginUrl' => ['auth/login'],
        ],
        'session'      => [
            'name'         => '_session',
            'cookieParams' => [
                'domain'   => $params['cookieDomain'],
                'httpOnly' => true,
            ],
        ],
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
                    'logFile'        => '@runtime/logs/info.log',
                    'logVars'        => [],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'app/error',
        ],
        'frontendUrlManager' => require __DIR__ . '/urlManager.php',
        'urlManager'         => function () {
            return Yii::$app->get('frontendUrlManager');
        },
    ],
    'params'              => $params,
];
