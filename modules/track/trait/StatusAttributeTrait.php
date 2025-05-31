<?php

declare(strict_types=1);

namespace app\modules\track\trait;

use app\modules\track\interface\StatusAttributeInterface;
use Exception;
use yii\helpers\ArrayHelper;

trait StatusAttributeTrait
{
    /**
     * @return string
     */
    protected static function getStatusAttribute(): string
    {
        return 'status';
    }

    /**
     * @return string[]
     */
    public static function getStatusList(): array
    {
        return [
            StatusAttributeInterface::STATUS_NEW => 'Новый',
            StatusAttributeInterface::STATUS_PROGRESS => 'В процессе',
            StatusAttributeInterface::STATUS_COMPLETED => 'Готов',
            StatusAttributeInterface::STATUS_FAILED => 'Ошибка',
            StatusAttributeInterface::STATUS_CANCELED => 'Отменён',
        ];
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function getStatus(): ?string
    {
        return ArrayHelper::getValue(static::getStatusList(), $this->{static::getStatusAttribute()});
    }
}
