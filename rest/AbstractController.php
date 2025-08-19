<?php

namespace app\rest;

use app\common\rbac\CollectionRolls;
use app\rest\actions\ExportAction;
use app\rest\actions\IndexAction;
use Exception;
use Throwable;
use Yii;
use yii\data\ActiveDataFilter;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 *
 * @property-read string $modelSearch
 * @property-read array $exportAttribute
 * @property-read ActiveQueryInterface $query
 */
abstract class AbstractController extends ApiController
{
    /**
     * @return array
     */
    public function actions(): array
    {
        $actions = parent::actions();
        return ArrayHelper::merge([
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => $this->getModelSearch(),
                //'checkAccess' => [$this, 'checkAccess'],
                'dataFilter' => [
                    'class' => ActiveDataFilter::class,
                    'searchModel' => $this->getModel(),
                ],
            ],
            'export' => [
                'class' => ExportAction::class,
                'modelClass' => $this->getModelSearch(),
            ],
        ], $actions);
    }

    /**
     * @return ActiveRecordInterface
     *
     * @throws UnprocessableEntityHttpException
     */
    public function actionCreate(): ActiveRecordInterface
    {
        $model = $this->getModel();

        if ($model->load($this->request->post(), '') && $model->save()) {
            return $model;
        }

        throw new UnprocessableEntityHttpException(implode(', ', $model->getErrorSummary(true)));
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
        $model = $this->findModel($id);

        if ($model->load($this->request->post(), '') && $model->save()) {
            return $model;
        }

        throw new UnprocessableEntityHttpException(Json::encode($model->getErrors()));
    }

    /**
     * @param string $id
     * @return array|ActiveRecord
     * @throws NotFoundHttpException
     */
    public function actionView(string $id): array|ActiveRecord
    {
        return $this->findModel($id);
    }

    /**
     * @param string $id
     * @return array
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDelete(string $id): array
    {
        $this->findModel($id)->delete();
        return [];
    }

    /**
     * @return array
     */
    public function actionDeleteAll(): array
    {
        if ($uuids = $this->request->post('uuids')) {
            return [$this->getModel()::deleteAll(['id' => $uuids])];
        }

        return [];
    }

    /**
     * @return array|ActiveRecord
     */
    public function actionUpdateAll(): array|ActiveRecord
    {
        if ($this->request->post('uuids') && $updateAttributes = $this->request->post('attributes')) {
            $update = [];
            foreach ($updateAttributes as $attribute) {
                $update = $attribute;
            }
            $this->getModel()::updateAll($update, ['id' => $this->request->post('uuids')]);
        }

        return [];
    }

    /**
     * @param array $config
     *
     * @return ActiveRecordInterface
     */
    abstract protected function getModel(array $config = []): ActiveRecordInterface;

    /**
     * @return string
     */
    abstract protected function getModelSearch(): string;

    /**
     * @return array
     */
    abstract protected function getExportAttribute(): array;

    /**
     * @param string $id
     * @return array|ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel(string $id): array|ActiveRecord
    {
        $model = $this->getModel()::find()->where(['id' => $id])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
