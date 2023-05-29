<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Service;

use Doctrine\ORM\EntityNotFoundException;
use Internations\AdminBundle\Entity\Role;
use Internations\AdminBundle\Repository\RoleRepository;

final class RoleService
{
    public function __construct(
        private RoleRepository $groupsRepository
    ) {
    }

    public function updateEntity(Role $role, array $data): Role
    {
        if (!$role instanceof Role) {
            throw new EntityNotFoundException('No Entity found');
        }

        $role->setName($data['name']);

        return $role;
    }
}