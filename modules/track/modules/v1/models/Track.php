<?php

declare(strict_types=1);

namespace app\modules\track\modules\v1\models;

use app\modules\track\models\Track as TrackAlias;

class Track extends TrackAlias
{
    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'id',
            'track_number',
            'status',
            'createdAt',
            'updatedAt',
        ];
    }

    /**
     * @return string[]
     */
    public function extraFields(): array
    {
        return [
            'statusValue' => function () {
                return $this->getStatus();
            }
        ];
    }
}
