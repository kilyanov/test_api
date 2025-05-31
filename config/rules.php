<?php

return [
    '' => 'track',
    '<module:[\w\-]+>' => '<module>',
    '<module:[\w\-]+>/<controller:[\w\-]+>' => '<module>/<controller>',
    '<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>/<page:\d+>/<per-page:\d+>' => '<module>/<controller>/<action>',
    '<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>' => '<module>/<controller>/<action>',
    '<module:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>' => '<module>/<controller>/<action>',
    [
        'class' => yii\web\UrlRule::class,
        'pattern' => 'swagger/<version:[\w\-]+>/<controller:[\w\-]+>/<action:[\w\-]+>',
        'route' => 'swagger/<version>/<controller>/<action>',
        'suffix' => '.json',
    ],
];