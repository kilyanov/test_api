<?php

declare(strict_types=1);

namespace app\modules\requests\modules\v1\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\requests\modules\v1\models\Request;
use app\rest\AbstractController;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

class DefaultController extends AbstractController
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator'], $behaviors['accessControl']);
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBearerAuth::class,
                QueryParamAuth::class,
            ],
            'except' => ['create']
        ];
        $behaviors['accessControl'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => [CollectionRolls::ROLE_MODERATOR],
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['?']
                ]
            ],
        ];
        return $behaviors;
    }

    /**
     * @param array $config
     * @return Request
     * @throws Exception
     */
    protected function getModel(array $config = []): Request
    {
        return Yii::createObject(Request::class, $config);
    }

    /**
     * @return string
     */
    protected function getModelSearch(): string
    {
        return Request::class;
    }

    /**
     * @return array
     */
    protected function getExportAttribute(): array
    {
        return [];
    }
}
