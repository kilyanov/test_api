<?php

namespace app\modules\user\models\query;

use app\models\query\UserQuery as UserQueryAlias;
use app\models\User;
use yii\db\ActiveRecord;

class UserQuery extends UserQueryAlias
{
    /**
     * @param $db
     * @return array|ActiveRecord[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * @param $db
     * @return array|User|null
     */
    public function one($db = null): array|null|User
    {
        return parent::one($db);
    }
}
