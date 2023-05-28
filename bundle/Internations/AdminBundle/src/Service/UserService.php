<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Service;

use Doctrine\ORM\EntityNotFoundException;
use Internations\AdminBundle\Entity\User;
use Internations\AdminBundle\Repository\GroupsRepository;
use Internations\AdminBundle\Repository\RoleRepository;
use Internations\AdminBundle\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserService
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
        private RoleRepository $roleRepository,
        private GroupsRepository $groupsRepository
    ) {
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

    public function transformFromArray(int $userId, array $data): User
    {
        $user = $this->userRepository->find($userId);
        $user = $user->transformFromArray($data);
        $this->hashUserPassword($user);

        $roles = [];
        if (!empty($data['roles'])) {
            foreach ($data['roles'] as $roleId) {
                $roles[] = $this->roleRepository->find($roleId);
            }
        }

        $groups = [];
        if (!empty($data['groups'])) {
            foreach ($data['groups'] as $groupId) {
                $groups[] = $this->groupsRepository->find($groupId);
            }
        }

        $user->setRoles($roles);
        $user->setGroups($groups);
        return $user;
    }

    public function updateEntity(User $oldUser, User $newUser): User
    {
        if (!$oldUser instanceof User || !$newUser instanceof User) {
            throw new EntityNotFoundException('No Entity found');
        }

        $oldUser->setName($newUser->getName());
        $oldUser->setEmail($newUser->getEmail());
        $oldUser->setPassword($newUser->getPassword());
        $oldUser->setIsActive($newUser->isActive());

        // TODO: Implement the groups, roles relationships

        return $oldUser;
    }
}