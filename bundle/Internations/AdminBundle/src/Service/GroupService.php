<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Service;

use Doctrine\ORM\EntityNotFoundException;
use Internations\AdminBundle\Entity\Groups;
use Internations\AdminBundle\Repository\GroupsRepository;

final class GroupService
{
    public function __construct(
        private GroupsRepository $groupsRepository
    ) {
    }

    public function updateEntity(Groups $group, array $data): Groups
    {
        if (!$group instanceof Groups) {
            throw new EntityNotFoundException('No Entity found');
        }

        $group->setName($data['name']);
        $group->setIsActive((bool)$data['is_active']);

        return $group;
    }
}