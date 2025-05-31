<?php

namespace ext\swagger\controllers;

use ext\swagger\actions\ApiAction;
use ext\swagger\actions\JsonAction;
use ext\swagger\actions\UiAction;
use yii\web\Controller;

class DefaultController extends Controller
{
    /**
     * @var string
     */
    public $defaultAction = 'ui';

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'ui' => [
                'class' => UiAction::class,
            ],
            'api' => [
                'class' => ApiAction::class,
            ],
            'json' => [
                'class' => JsonAction::class,
            ],
        ];
    }
}
