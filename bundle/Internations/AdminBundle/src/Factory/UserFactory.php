<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Factory;

use Internations\AdminBundle\Entity\User;

final class UserFactory
{
    public function create(array $user): User
    {
        $newUser = new User;
        $newUser->setName($user['name']);
        $newUser->setEmail($user['email']);
        $newUser->setPassword($user['password']);
        $newUser->setIsActive((bool)$user['is_active']);

        // TODO: Add roles, groups relations

        return $newUser;
    }
}