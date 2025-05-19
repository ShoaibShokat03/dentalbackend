<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'dentaldocsecretekey',
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
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
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
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'POST api/user/register' => 'api/user/register',
                'POST api/user/login' => 'api/user/login',
                'GET api/user/profile' => 'api/user/profile',
            
                // Dashboard
                'GET api/dashboard' => 'api/dashboard/stats',
                'OPTIONS api/dashboard' => 'api/dashboard/stats',
                
                // Users
                'GET api/users/list' => 'api/user/users-list',
                'OPTIONS api/users/list' => 'api/user/users-list',

                'POST api/users/create' => 'api/user/register',
                'OPTIONS api/users/create' => 'api/user/register',

                'POST api/users/edit' => 'api/user/edit',
                'OPTIONS api/users/edit' => 'api/user/edit',
                
                'POST api/users/delete' => 'api/user/delete',
                'OPTIONS api/users/delete' => 'api/user/delete',

                
                // Patient
                'GET api/patients/list' => 'api/patient/patients-list',
                'OPTIONS api/patients/list' => 'api/patient/patients-list',

                'POST api/patients/create' => 'api/patient/create',
                'OPTIONS api/patients/create' => 'api/patient/create',

                'POST api/patients/edit' => 'api/patient/edit',
                'OPTIONS api/patients/edit' => 'api/patient/edit',
                
                'POST api/patients/delete' => 'api/patient/delete',
                'OPTIONS api/patients/delete' => 'api/patient/delete',

                
                // Categories
                'GET api/categories/list' => 'api/inventorycategories/list',
                'OPTIONS api/categories/list' => 'api/inventorycategories/list',

                'POST api/categories/create' => 'api/inventorycategories/create',
                'OPTIONS api/categories/create' => 'api/inventorycategories/create',

                'POST api/categories/edit' => 'api/inventorycategories/edit',
                'OPTIONS api/categories/edit' => 'api/inventorycategories/edit',
                
                'POST api/categories/delete' => 'api/inventorycategories/delete',
                'OPTIONS api/categories/delete' => 'api/inventorycategories/delete',
                
                
                // Inventory
                'GET api/inventory/list' => 'api/inventory/list',
                'OPTIONS api/inventory/list' => 'api/inventory/list',

                'POST api/inventory/create' => 'api/inventory/create',
                'OPTIONS api/inventory/create' => 'api/inventory/create',

                'POST api/inventory/edit' => 'api/inventory/edit',
                'OPTIONS api/inventory/edit' => 'api/inventory/edit',
                
                'POST api/inventory/delete' => 'api/inventory/delete',
                'OPTIONS api/inventory/delete' => 'api/inventory/delete',
                
                // Generic Entities
                'GET api/genericentities/list' => 'api/genericentities/list',
                'OPTIONS api/genericentities/list' => 'api/genericentities/list',

                'POST api/genericentities/create' => 'api/genericentities/create',
                'OPTIONS api/genericentities/create' => 'api/genericentities/create',

                'POST api/genericentities/edit' => 'api/genericentities/edit',
                'OPTIONS api/genericentities/edit' => 'api/genericentities/edit',
                
                'POST api/genericentities/delete' => 'api/genericentities/delete',
                'OPTIONS api/genericentities/delete' => 'api/genericentities/delete',
                
                // Generic Entities
                'GET api/genericrecords/list' => 'api/genericrecords/list',
                'OPTIONS api/genericrecords/list' => 'api/genericrecords/list',

                'POST api/genericrecords/create' => 'api/genericrecords/create',
                'OPTIONS api/genericrecords/create' => 'api/genericrecords/create',

                'POST api/genericrecords/edit' => 'api/genericrecords/edit',
                'OPTIONS api/genericrecords/edit' => 'api/genericrecords/edit',
                
                'POST api/genericrecords/delete' => 'api/genericrecords/delete',
                'OPTIONS api/genericrecords/delete' => 'api/genericrecords/delete',
            ],
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
