<?php

use League\Tactician\CommandBus;
use yii\di\ServiceLocator;
use yii\rbac\DbManager;

return [
    'name' => getenv('APP_NAME'),
    'timeZone' => 'UTC',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
    ],
    'aliases' => [
        '@root' => dirname(__DIR__),
    ],
    'container' => [
        'singletons' => [
            CommandBus::class => static function () {
                $locator = new ServiceLocator([
                    'components' => [

                    ],
                ]);

                $lockingMiddleware = new League\Tactician\Plugins\LockingMiddleware();
                $commandMiddleware = new League\Tactician\Handler\CommandHandlerMiddleware(
                    new League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor(),
                    new League\Tactician\Handler\Locator\CallableLocator([$locator, 'get']),
                    new League\Tactician\Handler\MethodNameInflector\InvokeInflector()
                );

                return new League\Tactician\CommandBus([$lockingMiddleware, $commandMiddleware]);
            },
        ],
        'definitions' => [

        ],
    ],
    'modules' => [

    ],
    'components' => [
        'authManager' => [
            'class' => DbManager::class,
            'cache' => 'cache',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'timeZone' => 'Europe/Moscow',
            'sizeFormatBase' => 1000,
            'thousandSeparator' => ' ',
            'numberFormatterSymbols' => [
                NumberFormatter::CURRENCY_SYMBOL => '₽',
            ],
            'numberFormatterOptions' => [
                NumberFormatter::MAX_FRACTION_DIGITS => 2,
            ],
        ],
        'security' => [
            'class' => 'yii\base\Security',
            'passwordHashCost' => 15,
        ],
        'session' => [
            'class' => 'yii\web\CacheSession',
            'cache' => [
                'class' => 'yii\redis\Cache',
                'defaultDuration' => 0,
                'keyPrefix' => hash('crc32', __FILE__),
                'redis' => [
                    'hostname' => getenv('REDIS_HOST'),
                    'port' => getenv('REDIS_PORT'),
                    'database' => 1,
                ],
            ],
        ],
        'cache' => [
            'class' => yii\redis\Cache::class,
            'defaultDuration' => 24 * 60 * 60,
            'keyPrefix' => hash('crc32', __FILE__),
            'redis' => [
                'hostname' => getenv('REDIS_HOST'),
                'port' => getenv('REDIS_PORT'),
                'database' => 0,
            ],
        ],
        'log' => [
            'class' => 'yii\log\Dispatcher',
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'file' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => [
                        'error',
                        'warning',
                    ],
                    'except' => [
                        'yii\web\HttpException:404',
                        //'yii\web\HttpException:403',
                    ],
                    'enabled' => YII_ENV_PROD,
                ],
            ],
        ],
        'db' => require(__DIR__ . DIRECTORY_SEPARATOR . 'db.php'),
    ],
    'params' => require(__DIR__ . DIRECTORY_SEPARATOR . 'params.php'),
];
