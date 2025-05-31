<?php

declare(strict_types=1);

namespace app\modules\track\trait;

use app\modules\track\interface\StatusAttributeInterface;
use yii\db\ActiveQuery;

trait StatusActiveQueryTrait
{
    /**
     * @param string|array|null $status
     * @return ActiveQuery
     */
    public function status(string|array|null $status = StatusAttributeInterface::STATUS_ONE): ActiveQuery
    {
        $this->andFilterWhere([$this->modelClass::tableName() . '.[[status]]' => $status]);

        return $this;
    }
}
