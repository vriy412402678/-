<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'homeUrl' => '/backend',
    'defaultRoute' => 'server/index/index',
    'modules' => [
        'home'=>[
            'class' => 'backend\modules\home\home',
        ],
        'server'=>[
            'class' => 'backend\modules\server\server',
        ],
    ],
    'components' => [
        'view' => [
            'renderers' => [
                'html' => [
                    'class' => 'yii\smarty\ViewRenderer',
                    'cachePath' => '@runtime/Smarty/cache',
                    'options' => [
                        'left_delimiter' => '<{',
                        'right_delimiter' => '}>',
                    ],
                ],
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/backend',
        ],
        'user' => [
            'identityClass' => 'common\models\admin\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl' => ['home/login/index']
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],

    ],
    'params' => $params,
];
