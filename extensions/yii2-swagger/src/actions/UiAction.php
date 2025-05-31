<?php

namespace ext\swagger\actions;

use yii\base\Action;
use yii\web\Response;

class UiAction extends Action
{
    /**
     * @var array
     */
    public array $urls = [];

    /**
     * @var string
     */
    public string $view = '@ext/swagger/views/ui.php';

    /**
     * @return string
     */
    public function run(): string
    {
        $this->controller->response->format = Response::FORMAT_HTML;

        return $this->controller->renderFile($this->view, [
            'urls' => $this->urls,
        ]);
    }
}
