<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Factory;

use Internations\AdminBundle\Entity\Groups;

final class GroupFactory
{
    public function create(array $group): Groups
    {
        $newGroup = new Groups();
        $newGroup->setName($group['name']);
        $newGroup->setIsActive((bool)$group['is_active']);

        return $newGroup;
    }
}