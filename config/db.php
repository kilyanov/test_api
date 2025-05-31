<?php

return [
    'class' => yii\db\Connection::class,
    'dsn' => getenv('DB_DRIVER') . ':host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE') . ';port=' . getenv('DB_PORT'),
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'charset' => getenv('DB_CHARSET'),
    'tablePrefix' => getenv('DB_TABLE_PREFIX'),
    'enableQueryCache' => true,
    'queryCacheDuration' => 1 * 60 * 60, // seconds
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 1 * 60 * 60, // seconds
    'schemaCache' => 'cache',
];
