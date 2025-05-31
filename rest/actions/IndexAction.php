<?php

declare(strict_types=1);

namespace app\rest\actions;

use app\rest\data\DataProvider;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\Pagination;
use yii\data\Sort;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\rest\IndexAction as IndexActionAlias;

class IndexAction extends IndexActionAlias
{
    /**
     * @throws InvalidConfigException
     * @throws Exception
     */
    protected function prepareDataProvider()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $filter = null;
        if ($this->dataFilter !== null) {
            $this->dataFilter = Yii::createObject($this->dataFilter);
            if ($this->dataFilter->load($requestParams)) {
                $filter = $this->dataFilter->build();
                if ($filter === false) {
                    return $this->dataFilter;
                }
            }
        }

        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this, $filter);
        }

        /* @var $modelClass BaseActiveRecord */
        $modelClass = $this->modelClass;

        $query = $modelClass::find();
        if (!empty($filter)) {
            $query->andWhere($filter);
        }
        if (is_callable($this->prepareSearchQuery)) {
            $query = call_user_func($this->prepareSearchQuery, $query, $requestParams);
        }

        if (is_array($this->pagination)) {
            $pagination = ArrayHelper::merge(
                [
                    'params' => $requestParams,
                    'pageSizeLimit' => [
                        1,
                        (int)ArrayHelper::getValue($requestParams, 'per-page', DataProvider::DEFAULT_PAGE_SIZE)
                    ]
                ],
                $this->pagination
            );
        } else {
            $pagination = $this->pagination;
            if ($this->pagination instanceof Pagination) {
                $pagination->params = $requestParams;
                $pagination->pageSizeLimit = [
                    1,
                    (int)ArrayHelper::getValue($requestParams, 'per-page', DataProvider::DEFAULT_PAGE_SIZE)
                ];
            }
        }
        if (is_array($this->sort)) {
            $sort = ArrayHelper::merge(
                [
                    'params' => $requestParams,
                ],
                $this->sort
            );
        } else {
            $sort = $this->sort;
            if ($this->sort instanceof Sort) {
                $sort->params = $requestParams;
            }
        }
        /** @var DataProvider $dataProvider */
        $dataProvider = Yii::createObject([
            'class' => DataProvider::class,
            'query' => $query,
            'pagination' => $pagination,
            'sort' => $sort,
        ]);
        $dataProvider->setTotal();
        $dataProvider->setIncrement();
        $dataProvider->setPageSize((int)ArrayHelper::getValue($requestParams, 'per-page', DataProvider::DEFAULT_PAGE_SIZE));
        $dataProvider->setPage((int)ArrayHelper::getValue($requestParams, 'page', 1));
        $profiling = Yii::getLogger()->getDbProfiling();
        return [
            'countQuery' => $profiling[0],
            'queryStr' => $query->createCommand()->getRawSql(),
            'countData' => $dataProvider->getTotal(),
            'increment' => $dataProvider->getIncrement(),
            'pageSize' => $dataProvider->getPageSize(),
            'pageCurrent' => $dataProvider->getPage(),
            'items' => $dataProvider
        ];
    }
}