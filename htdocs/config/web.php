<?php
require_once('vault.php');
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$baseUrl = str_replace('/web', '', (new \yii\web\Request)->getBaseUrl());

$config = [
    'id' => 'blank-yii2-shib',

    'name' => 'Test App',

    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],


    'defaultRoute' => '//site/index',


    'components' => [
        
   
        
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'blankyii2testcookie',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
       'session' => [
                'timeout' => 3600,
        ],
        'user' => [
            'identityClass' => 'app\models\User',

            //'enableAutoLogin' => true,
            'enableAutoLogin' => false,

            'idParam' => 'blank_yii2_shib_id',

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
           'enablePrettyUrl' => true,
            'showScriptName' => false,
          
            'rules' => [
                'users'=>'user/index',
                'user/delete'=>'/user/delete',
                'user/import'=>'/user/import',
                'user/<id:\w+>'=>'/user/update',
                'lookups'=>'/lookup-type/index',
                'departments'=>'/department/index',
                'department/<id:\d+>'=>'department/update',
                'login'=>'/site/login',
                'logout'=>'/site/logout'
            ],
        ],
       



        'icsldap' => [
            'class' => 'app\components\icsldap',
        ],

        'WebAuth' => [
            'class' => 'app\components\WebAuth',
        ],

        'shibboleth' => [
            'class' => 'app\components\shibboleth',
        ],



		'assetManager' => [
	        'bundles' => [

	            'yii\jui\JuiAsset' => [
	                'css' => [
	                    'themes/base/jquery-ui.css',			// this is a good one: "base"
	                ]
	            ],

	        ]
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
        'allowedIPs' => ['128.195.7.*','128.195.1.*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        //'allowedIPs' => ['128.195.7.24'],
        'allowedIPs' => ['128.195.7.*'],
    ];
}

return $config;
