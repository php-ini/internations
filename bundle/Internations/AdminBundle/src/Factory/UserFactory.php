<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Internations\AdminBundle\Entity\User;

final class UserFactory
{
    public function create(array $user, ArrayCollection $roles, ArrayCollection $groups): User
    {
        $newUser = new User;
        $newUser->setName($user['name']);
        $newUser->setEmail($user['email']);
        $newUser->setPassword($user['password']);
        $newUser->setIsActive((bool)$user['is_active']);

        if ($groups) {
            $newUser->setGroups($groups);
        }

        if ($roles) {
            $newUser->setRoles($roles);
        }

        return $newUser;
    }
}