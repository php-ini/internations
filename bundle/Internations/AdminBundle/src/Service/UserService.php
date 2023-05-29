<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
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
        $user = $this->hashUserPassword($user);

        if (!empty($data['roles'])) {
            $roles = $this->getRolesByIds($data['roles']);
            $user->setRoles($roles);
        } else {
            $user->setRoles([]);
        }

        if (!empty($data['groups'])) {
            $groups = $this->getGroupsByIds($data['groups']);
            $user->setGroups($groups);
        } else {
            $user->setGroups(new ArrayCollection());
        }

        return $user;
    }

    public function getRolesByIds(array $roleIds): ArrayCollection
    {
        $rolesArray = new ArrayCollection();
        if (!empty($roleIds)) {
            foreach ($roleIds as $roleId) {
                $rolesArray[] = $this->roleRepository->find($roleId);
            }
        }
        return $rolesArray;
    }

    public function getGroupsByIds(array $groupsIds): ArrayCollection
    {
        $groupsArray = new ArrayCollection();
        if (!empty($groupsIds)) {
            foreach ($groupsIds as $groupsId) {
                $groupsArray[] = $this->groupsRepository->find($groupsId);
            }
        }
        return $groupsArray;
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

        $roles = $this->roleRepository->findBy(['name' => $newUser->getRoles()]);
        $oldUser->setRoles($roles);
        $oldUser->setGroups($newUser->getGroups());

        return $oldUser;
    }
}