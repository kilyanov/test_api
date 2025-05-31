Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist ext/yii2-swagger "*"
```

or add

```
"ext/yii2-swagger": "*"
```

to the require section of your `composer.json` file.

Use:
====

web.php

```
    'container' => [
        'definitions' => [
            \ext\swagger\actions\UiAction::class => [
                'urls' => [
                    [
                        'name' => 'OAS',
                        'url' => Url::to(['api']),
                    ],
                ],
            ],
            \ext\swagger\actions\ApiAction::class => [
                'path' => Yii::getAlias('@app/modules/swagger/api.json'),
            ],
            \ext\swagger\actions\JsonAction::class => [
                'config' => [],
            ],
        ],
    ],
    'modules' => [
        'swagger' => [
            'class' => \yii\base\Module::class,
            'controllerNamespace' => 'ext\swagger\controllers',
        ],
    ],
```

api.json

```
{
    "openapi": "3.0.3",
    "info": {
        "title": "The Project",
        "version": "1.0.0"
    },
    "servers": [
        {
            "description": "localhost",
            "url": "http://localhost/api"
        }
    ],
    ...
}
```
