<?php

namespace ext\swagger\actions;

use yii\base\Action;
use yii\web\Response;

class JsonAction extends Action
{
    /**
     * @var array
     */
    public array $config = [];

    /**
     * @return Response
     */
    public function run(): Response
    {
        return $this->controller->asJson($this->config);
    }
}
