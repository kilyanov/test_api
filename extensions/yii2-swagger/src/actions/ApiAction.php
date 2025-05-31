<?php

namespace ext\swagger\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;

class ApiAction extends Action
{
    /**
     * @var string
     */
    public string $path;

    /**
     * @return Response
     */
    public function run(): Response
    {
        $this->controller->response->format = Response::FORMAT_JSON;
        Yii::error(file_get_contents($this->path));
        $this->controller->response->content = file_get_contents($this->path);

        return $this->controller->response;
    }
}
