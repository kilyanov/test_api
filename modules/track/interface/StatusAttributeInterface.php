<?php

declare(strict_types=1);

namespace app\modules\track\interface;

interface StatusAttributeInterface
{
    public const STATUS_NEW = 'new';
    public const STATUS_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELED = 'canceled';

    /**
     * @return array
     */
    public static function getStatusList(): array;

    /**
     * @return string|null
     */
    public function getStatus(): ?string;
}
