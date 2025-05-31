<?php

declare(strict_types=1);

namespace app\rest\data;

use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\QueryInterface;

/**
 *
 * @property-read array $items
 */
class DataProvider extends ActiveDataProvider
{
    public const DEFAULT_PAGE_SIZE = 20;

    public const DIRECTION_LEFT = 'left';

    public const DIRECTION_RIGHT = 'right';

    /**
     * @var string
     */
    public string $direction = self::DIRECTION_RIGHT;

    /**
     * @var int
     */
    protected int $page = 1;

    /**
     * @var int
     */
    protected int $pageSize = self::DEFAULT_PAGE_SIZE;

    /**
     * @var int
     */
    protected int $total = 0;

    /**
     * @var int
     */
    protected int $increment = 0;

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return void
     * @throws InvalidConfigException
     */
    public function setTotal(): void
    {
        $this->total = $this->prepareTotalCount();
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return $this
     */
    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * @param int|null $pageSize
     * @return $this
     */
    public function setPageSize(?int $pageSize = self::DEFAULT_PAGE_SIZE): self
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * @return int`
     */
    public function getIncrement(): int
    {
        return $this->increment;
    }

    /**
     * @return void
     */
    public function setIncrement(): void
    {
        $models = $this->getModels();
        $endModel = end($models);
        $this->increment = ($endModel === false || !$endModel->hasProperty('increment'))
            ? 0 : $endModel->increment;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return array_map(function($model) {
            return $model->fields();
        }, $this->getModels());
    }

    /**
     * @return array
     * @throws InvalidConfigException
     */
    protected function prepareModels(): array
    {
        if (!$this->query instanceof QueryInterface) {
            throw new InvalidConfigException('The "query" property must be an instance of a class that implements the QueryInterface e.g. yii\db\Query or its subclasses.');
        }
        $query = clone $this->query;
        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->getTotalCount();
            if ($pagination->totalCount === 0) {
                return [];
            }
            $pagination->totalCount = $this->getTotalCount();
            $query->limit($pagination->getPageSize());

            if ($query->hasProperty('increment')) {
                if ($this->direction === self::DIRECTION_RIGHT) {
                    $query->andWhere(['>', 'increment', $this->increment]);
                }
                if ($this->direction === self::DIRECTION_LEFT) {
                    $query->andWhere(['<', 'increment', $this->increment]);
                }
            }
            else {
                $query->offset($pagination->getOffset());
            }
        }

        if (($sort = $this->getSort()) !== false) {
            $query->addOrderBy($sort->getOrders());
        }

        return $query->all($this->db);
    }
}
