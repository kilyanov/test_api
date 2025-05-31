<?php

declare(strict_types=1);

namespace app\common\rbac;

class CollectionRolls
{
    public const ROLE_ROOT = 'admin';

    /**
     * @return string[]
     */
    public static function getListRole(): array
    {
        return [
            self::ROLE_ROOT => 'Администратор',
        ];
    }

    /**
     * @param string $role
     * @return string
     */
    public static function getRoleName(string $role): string
    {
        return self::getListRole()[$role];
    }
}
