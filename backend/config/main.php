<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'                  => 'app-backend',
    'name'                => 'Bigmall',
    'language'            => 'ru-RU',
    'basePath'            => dirname(__DIR__),
    'aliases'             => [
        '@static' => $params['staticHostInfo'],
    ],
    'homeUrl'             => '/admin/',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap'           => [
        'log',
        'common\SetUp',
    ],
    'modules'             => [
        'dynagrid' => [
            'class'           => 'kartik\dynagrid\Module',
            'dynaGridOptions' => [
                'storage'     => 'cookie',
                'gridOptions' => [
                    'toolbar' => [
                        '{dynagrid}',
                        '{toggleData}',
                    ],
                    'export'  => false,
                ],
            ],

        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
    ],
    'components'          => [
        'request'      => [
            'csrfParam'           => '_csrf-backend',
            'cookieValidationKey' => $params['cookieValidationKey'],
            'baseUrl'             => '/admin',
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
        'session' => [
            'name' => '_session',
            'cookieParams' => [
                'domain' => $params['cookieDomain'],
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
        'urlManager'   => [
            'enablePrettyUrl'     => true,
            'showScriptName'      => false,
            'enableStrictParsing' => true,
            'suffix'              => '/',
            'rules'               => [
                [
                    'class'       => 'yii\web\GroupUrlRule',
                    'prefix'      => 'catalog',
                    'routePrefix' => 'catalog',
                    'rules'       => [
                        '/'                        => 'default/index',
                        '<_c:[\w\-]+>'             => '<_c>/index',
                        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
                    ],
                ],
                [
                    'class'       => 'yii\web\GroupUrlRule',
                    'prefix'      => 'content',
                    'routePrefix' => 'content',
                    'rules'       => [
                        '/'                        => 'default/index',
                        '<_c:[\w\-]+>'             => '<_c>/index',
                        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
                    ],
                ],
                '/'                 => 'app/index',
                '/settings'         => 'app/settings',
                '<_a:login|logout>' => 'auth/<_a>',

                '<_c:[\w\-]+>'                       => '<_c>/index',
                '<_c:[\w\-]+>/<id:\d+>'              => '<_c>/view',
                '<_c:[\w\-]+>/<_a:[\w-]+>'           => '<_c>/<_a>',
                '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
            ],
        ],
    ],
    'as access'           => [
        'class'        => 'yii\filters\AccessControl',
        'except'       => ['auth/login', 'app/error', 'response/make-slug'],
        'rules'        => [
            [
                'allow' => true,
                'roles' => ['admin'],
            ],
        ],
        'denyCallback' => function ($rule, $action) {
            return Yii::$app->response->redirect(['auth/login']);
        },
    ],
    'params'              => $params,
];
