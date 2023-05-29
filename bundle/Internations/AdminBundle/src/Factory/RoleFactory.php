<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Factory;

use Internations\AdminBundle\Entity\Role;

final class RoleFactory
{
    public function create(array $role): Role
    {
        $newRole = new Role();
        $newRole->setName($role['name']);

        return $newRole;
    }
}