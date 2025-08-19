<?php

declare(strict_types=1);

namespace app\modules\requests\modules\v1\controllers;

use app\common\rbac\CollectionRolls;
use app\modules\requests\interface\StatusRequestInterface;
use app\modules\requests\modules\v1\models\Request;
use app\rest\AbstractController;
use Exception;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

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
     * @return array[]
     */
    public function verbs(): array
    {
        $verbs = parent::verbs();
        return ArrayHelper::merge(
            $verbs,
            [
                'in-job' => ['POST', 'OPTIONS']
            ]
        );
    }

    /**
     * @param string $id
     * @return array|ActiveRecord
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     * @throws Exception
     */
    public function actionUpdate(string $id): array|ActiveRecord
    {
        /** @var Request $model */
        $model = $this->findModelModerator($id);

        if ($model->load($this->request->post(), '') && $model->validate()) {
            $model->status = StatusRequestInterface::STATUS_RESOLVE;
            $model->save();
            return $model;
        }

        throw new UnprocessableEntityHttpException(Json::encode($model->getErrors()));
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionInJob(string $id): Request|array|ActiveRecord
    {
        $model = $this->findModel($id);
        /** @var Request $model */
        if ($model->idModerator === null) {
            $model->idModerator = Yii::$app->user->getId();
            $model->save();
            return $model;
        }
        throw new NotFoundHttpException('Запрос уже взят в работу.');
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

    /**
     * @param string $id
     * @return array|ActiveRecord
     * @throws NotFoundHttpException
     * @throws Exception
     */
    protected function findModelModerator(string $id): array|ActiveRecord
    {
        $model = $this->getModel()::find()->where([
            'id' => $id,
            'idModerator' => Yii::$app->user->getId()
        ])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
