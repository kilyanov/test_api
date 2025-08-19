<?php

namespace app\modules\swagger\modules\v1\controllers;

use ext\swagger\actions\ApiAction;
use ext\swagger\actions\UiAction;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class DefaultController extends Controller
{
    /**
     * @var string
     */
    public $defaultAction = 'doc';

    /**
     * @return void
     */
    public function init(): void
    {
        Yii::$app->log->targets['file']->enabled = false;
        parent::init();
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'doc' => [
                'class' => UiAction::class,
                'urls' => [
                    [
                        'name' => 'OAS',
                        'url' => Url::to(['oas']),
                    ],
                ],
            ],
            'oas' => [
                'class' => ApiAction::class,
                'path' => Yii::getAlias('@app/modules/swagger/modules/v1/oas.json'),
            ],
            'ping' => [
                'class' => ApiAction::class,
                'path' => Yii::getAlias('@app/modules/swagger/modules/v1/ping.json'),
            ],
            'auth' => [
                'class' => ApiAction::class,
                'path' => Yii::getAlias('@app/modules/swagger/modules/v1/auth.json'),
            ],
            'requests' => [
                'class' => ApiAction::class,
                'path' => Yii::getAlias('@app/modules/swagger/modules/v1/requests.json'),
            ],
        ];
    }
}
