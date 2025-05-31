<?php

use Dotenv\Dotenv;

ini_set('error_reporting', 0);
ini_set("auto_detect_line_endings", '1');

array_map(static function ($env) {
    $path = dirname(__DIR__);

    if (file_exists($path . '/' . $env)) {
        Dotenv::create($path, $env)->overload();
    }
}, [
    '.env',
    '.env.local',
]);

ini_set('display_errors', getenv('DISPLAY_ERRORS'));
defined('YII_DEBUG') or define('YII_DEBUG', getenv('YII_DEBUG') === 'true');
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV') ?: 'prod');


