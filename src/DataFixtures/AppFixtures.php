<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Internations\AdminBundle\Entity\Groups;
use Internations\AdminBundle\Entity\Role;
use Internations\AdminBundle\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');
        $manager->persist($roleAdmin);

        $roleUser = new Role();
        $roleUser->setName('ROLE_USER');
        $manager->persist($roleUser);

        $group = new Groups();
        $group->setName('Facebook');
        $group->setIsActive(true);
        $manager->persist($group);

        $user = new User();
        $user->setName('Admin');
        $user->setEmail('admin@example.com');
        $user->setPassword('123456');
        $hashedPassword = $this
            ->passwordHasher
            ->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setIsActive(true);
        $user->setRoles([$roleAdmin]);
        $user->setGroups([$group]);
        $manager->persist($user);

        $user = new User();
        $user->setName('User');
        $user->setEmail('user@example.com');
        $user->setPassword('123456');
        $hashedPassword = $this
            ->passwordHasher
            ->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $user->setIsActive(true);
        $user->setRoles([$roleUser]);
        $user->setGroups([$group]);
        $manager->persist($user);

        $manager->flush();
    }
}
