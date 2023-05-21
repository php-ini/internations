<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Enum;

final class Roles
{
    public const ADMIN_ROLE = 'ROLE_ADMIN';
    public const USER_ROLE = 'ROLE_USER';
    public const ROLES_LIST = ['User' => self::USER_ROLE, 'Admin' => self::ADMIN_ROLE];
}