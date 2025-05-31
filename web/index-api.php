<?php

require __DIR__ . '/../vendor/autoload.php';
require(__DIR__ . '/../config/env.php');
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/api.php';

(new yii\web\Application($config))->run();
