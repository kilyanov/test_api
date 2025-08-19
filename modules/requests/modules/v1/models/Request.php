<?php

declare(strict_types=1);

namespace app\modules\requests\modules\v1\models;

use app\modules\requests\models\Request as RequestAlias;
use DateTime;

class Request extends RequestAlias
{
    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'id',
            'name',
            'email',
            'message',
            'comment',
            'status',
            'idModerator',
            'createdAt' => function () {
                return (new DateTime($this->createdAt))->format('d.m.Y H:i:s');
            },
            'updatedAt' => function () {
                return (new DateTime($this->updatedAt))->format('d.m.Y H:i:s');
            },
        ];
    }

    /**
     * @return string[]
     */
    public function extraFields(): array
    {
        return [
            'moderator' => 'moderatorRelation',
        ];
    }
}
