<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * @return Response
     */
    public function actionError(): Response
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            $exception = new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
        return $this->asJson([
                'name' => $exception->getName(),
                'message' => $exception->getMessage(),
                'exception' => $exception,
        ]);
    }

}
