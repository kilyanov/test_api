<?php

namespace app\common\db;

use ext\behaviors\IdAttributeBehavior;
use ext\behaviors\TimestampBehavior;
use yii\db\ActiveRecord as ActiveRecordAlias;

class ActiveRecord extends ActiveRecordAlias
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'TimestampBehavior' => [
                'class' => TimestampBehavior::class,
            ],
            'IdAttributeBehavior' => [
                'class' => IdAttributeBehavior::class,
            ],
        ];
    }
}