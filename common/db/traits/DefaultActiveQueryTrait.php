<?php

declare(strict_types=1);

namespace app\common\db\traits;

use yii\db\ActiveQuery;

trait DefaultActiveQueryTrait
{
    /**
     * @param string|array $ids
     *
     * @return ActiveQuery
     */
    public function ids(string|array $ids): ActiveQuery
    {
        /** @var ActiveQuery $this */
        return $this->andWhere([$this->modelClass::tableName() . '.[[id]]' => $ids]);
    }
}
