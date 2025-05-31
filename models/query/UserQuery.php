<?php

namespace app\models\query;

use app\common\db\traits\DefaultActiveQueryTrait;
use app\common\interface\StatusAttributeInterface;
use app\models\User;
use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{
    use DefaultActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return User[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return User|array|null
     */
    public function one($db = null): User|array|null
    {
        return parent::one($db);
    }

    /**
     * @return UserQuery
     */
    public function blocked(): UserQuery
    {
        return $this->andWhere([User::tableName() . '.[[status]]' => StatusAttributeInterface::STATUS_ACTIVE]);
    }
}
