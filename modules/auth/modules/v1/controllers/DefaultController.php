<?php

namespace app\modules\auth\modules\v1\controllers;

use app\models\User as UserAlias;
use app\modules\auth\modules\v1\forms\AuthForm;
use app\modules\auth\modules\v1\forms\LogoutForm;
use app\modules\user\models\User;
use app\rest\ApiController;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\UnauthorizedHttpException;

class DefaultController extends ApiController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        $parentBehaviors = parent::behaviors();
        unset($parentBehaviors['accessControl']);
        return array_merge_recursive(
            $parentBehaviors,
            [
                'authenticator' => [
                    'only' => [
                        'logout',
                    ],
                ],
            ]
        );
    }

    /**
     * @return array
     */
    public function verbs(): array
    {
        return [
            'login' => ['POST', 'OPTIONS'],
            'logout' => ['POST', 'OPTIONS'],
        ];
    }

    /**
     * @return array
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionLogin(): array
    {
        $model = new AuthForm();
        if ($model->load(Yii::$app->getRequest()->post(), '')) {
            if ($model->login()) {
                /**return ['accessToken' => $model->getUser()->accessToken];*/
                $model->getUser()->setScenario(UserAlias::SCENARIO_REFRESH_TOKEN);
                if ($model->getUser()->save()) {
                    return ['accessToken' => $model->getUser()->accessToken];
                }
            }
        }
        throw new UnauthorizedHttpException(Json::encode($model->getErrors()));
    }

    /**
     * @return array
     */
    public function actionLogout(): array
    {
        Yii::$app->getUser()->logout();
        return [];
    }
}
