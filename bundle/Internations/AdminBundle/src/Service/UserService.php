<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Service;

use Doctrine\ORM\EntityNotFoundException;
use Internations\AdminBundle\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserService
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function hashUserPassword(User $user): User
    {
        if (!$user instanceof User) {
            throw new EntityNotFoundException('No Entity found');
        }

        $hashedPassword = $this
            ->passwordHasher
            ->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        return $user;
    }
}