<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
    'language' => 'ru-RU', // ← here!
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
    'components' => [
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
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
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
                'admin/<controller:\w+>/<id:\d+>' => 'admin/<controller>/view',
                'admin/<controller:\w+>/<action:\w+>/' => 'admin/<controller>/<action>',
                'admin/<controller:\w+>/<action:\w+>' => 'admin/<controller>/<action>',

                /**
                 * url для каталога во frond-end'e
                 * @TODO разобраться, когда существующе action парсится не правильно
                 */
                'catalog/product/<url:([-]*\w+)+>' => '/catalog/product',
                'catalog/filter' => '/catalog/line-product',
                'catalog/product-info' => '/catalog/product-info',
                'catalog/<url:([-]*\w+)+>' => '/catalog/line',
                'catalog/collection/<url:([-]*\w+)+>' => '/catalog/collection',
                'catalog/collection/<parent_url:([-]*\w+)+>/<url:([-]*\w+)+>' => '/catalog/collection', //мб не нужно?
                'catalog/line/<line_url:([-]*\w+)+>' => '/catalog/line-product',
                'catalog/<line_url:([-]*\w+)+>/<url:([-]*\w+)+>/' => '/catalog/category',
                //финт, что бы не приходил "product" в параметре category, когда у товара нет категории
                'GET catalog/<line_url:([-]*\w+)+>/product/<url:([-]*\w+)+>/' => '/catalog/product',
                'GET catalog/<line_url:([-]*\w+)+>/<category_url:([-]*\w+)+>/<url:([-]*\w+)+>/' => '/catalog/product',
                'catalog/<line_url:([-]*\w+)+>/<category_url:([-]*\w+)+>/<url:([-]*\w+)+>/' => '/catalog/product',
                'catalog/<line_url:([-]*\w+)+>/product/<url:([-]*\w+)+>/' => '/catalog/product',

                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

            ]
        ],

        'i18n' => array(
            'translations' => array(
                'app*' => array(
                    'class' => 'yii\i18n\PhpMessageSource',
//                    'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en_US',
                    'fileMap' => array(
                        'app' => 'app.php',
                    ),
                ),
//                'yii*' => array(
//                    'class' => 'yii\i18n\PhpMessageSource',
//                    //'basePath' => '@app/messages',
//                    //'sourceLanguage' => 'en_US',
//                    'fileMap' => array(
//                        'yii' => 'yii.php',
//                        'yii/error' => 'yii.php',
//                    ),
//                ),
            ),
        ),
//        'assetManager' => [
//            'bundles' => [
//                'yii\bootstrap\BootstrapAsset' => [
//                    'css' => []
//                ],
//            ],
//        ],
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'cookieValidationKey' => 'xxxxxxx',
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
