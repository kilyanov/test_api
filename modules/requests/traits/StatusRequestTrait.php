<?php

declare(strict_types=1);

namespace app\modules\requests\traits;

use app\modules\requests\interface\StatusRequestInterface;
use Exception;
use yii\helpers\ArrayHelper;

trait StatusRequestTrait
{
    /**
     * @return string
     */
    protected static function getStatusAttribute(): string
    {
        return 'status';
    }

    /**
     * @return array
     */
    public static function getStatusList(): array
    {
        return [
            StatusRequestInterface::STATUS_ACTIVE => 'Активная',
            StatusRequestInterface::STATUS_RESOLVE => 'Завершена',
        ];
    }

    /**
     * @return null|string
     * @throws Exception
     */
    public function getStatus(): ?string
    {
        return ArrayHelper::getValue(static::getStatusList(), $this->{static::getStatusAttribute()});
    }
}
