<?php

$params = require __DIR__ . '/params.php';
if (YII_ENV && constant('YII_ENV') == 'test') {
    $db = require __DIR__ . '/test_db.php';
} else {
    $db = require __DIR__ . '/db.php';
}

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'cms' => [
            'class' => 'app\modules\cms\CmsModule',
        ],
        'api' => [
            'class' => 'app\modules\api\ApiModule'
        ]
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'TVezNX1LggPhA1M6gl2kUBoaYOnzUO5N',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,
        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'rules' => [
                '/' => 'site/index',
                'login' => 'site/login',
                'about' => 'site/about',
                'news' => 'news/index',
                'contact' => 'site/contact',
                'cms/news' => 'cms/news/index',
                'cms/<controller>/<action>' => 'cms/<controller>/<action>',
                [
                    'class' => \app\components\NewsUrlManager::class
                ],
                'cms/settings' => 'cms/settings/index',
                [
                    'class' => '\yii\rest\UrlRule',
                    'pluralize' =>  false,
                    'controller' => 'api/news'
                ]
            ]
        ],
        'thumbnail' => [
            'class' => 'sadovojav\image\Thumbnail',
            'cachePath' => 'runtime/thumbnail'
        ],
        'notifyRocketManager' => [
            'class' => 'app\modules\cms\components\NotifyRocketManager'
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
