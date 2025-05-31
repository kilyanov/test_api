<?php

namespace app\modules\ping\modules\v1\controllers;

use app\rest\ApiController;

class DefaultController extends ApiController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return array_diff_key(
            parent::behaviors(),
            array_flip([
                'accessControl',
                'authenticator'
            ])
        );
    }

    /**
     * @return array
     */
    public function verbs(): array
    {
        return [
            'index' => ['GET', 'OPTIONS'],
        ];
    }

    /**
     * @return array
     */
    public function actionIndex(): array
    {
        return [
            'answer' => 'ping'
        ];
    }
}
