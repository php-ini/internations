<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Enum;

final class Roles
{
    public const ADMIN_ROLE = 'admin';
    public const USER_ROLE = 'user';
    public const ROLES_LIST = ['User' => self::USER_ROLE, 'Admin' => self::ADMIN_ROLE];
}