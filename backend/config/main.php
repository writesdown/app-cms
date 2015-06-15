<?php
use \yii\web\Request;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

// Replace url
$request = new Request();

$baseUrlFront    = str_replace('/backend/web', '/frontend/web', $request->getBaseUrl());
$scriptUrlFront  = str_replace('/backend/web', '/frontend/web', $request->getScriptUrl());

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
        'i18n'            => [
            'translations' => [
                'writesdown' => [
                    'class'          => 'yii\i18n\PhpMessageSource',
                    'basePath'       => '@app/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'en',
                    'fileMap'        => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
        'urlManagerFront' => [
            'class'     => 'yii\web\urlManager',
            'scriptUrl' => $scriptUrlFront,
            'baseUrl'   => $baseUrlFront,
        ],
        'urlManagerBack'  => [
            'class'           => 'yii\web\urlManager',
        ],
        'authManager'     => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    'params' => $params,
];
