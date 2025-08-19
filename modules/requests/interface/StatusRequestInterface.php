<?php

declare(strict_types=1);

namespace app\modules\requests\interface;

interface StatusRequestInterface
{
    public const int STATUS_ACTIVE = 1;
    public const int STATUS_RESOLVE = 2;

    /**
     * @return array
     */
    public static function getStatusList(): array;

    /**
     * @return null|string
     */
    public function getStatus(): ?string;
}
