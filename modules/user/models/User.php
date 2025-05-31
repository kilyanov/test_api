<?php

namespace app\modules\user\models;

use app\models\User as UserAlias;
use app\modules\user\models\query\UserQuery;

class User extends UserAlias
{
    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find(): UserQuery
    {
        return new UserQuery(get_called_class());
    }
}
