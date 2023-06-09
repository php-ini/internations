<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Internations\AdminBundle\Entity\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Validator\Constraint as Assert;
use Internations\AdminBundle\Repository\RoleRepository;
use Internations\AdminBundle\Validator\Constraints as CustomAssert;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[UniqueEntity('name')]
final class Role implements RoleHierarchyInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: false, type: 'string', unique: true)]
    #[CustomAssert\CheckRoleName]
    private ?string $name = null;

    #[ORM\JoinTable(name: 'role_user')]
    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: User::class)]
    private $users;

    #[ORM\JoinTable(name: 'role_group')]
    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Groups::class, fetch: 'EXTRA_LAZY')]
    private $groups;

    public function __construct() {
        $this->users = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function addUser(User $user): self
    {
        $this->users[] = $user;

        return $this;
    }

    public function removeUser(User $user): bool
    {
        return $this->users->removeElement($user);
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addGroup(Groups $group): self
    {
        $this->groups[] = $group;

        return $this;
    }

    public function removeGroup(Groups $group): bool
    {
        return $this->users->removeElement($group);
    }

    public function getGroups(): ?Collection
    {
        return $this->groups;
    }

    public function getUsersCount(): int
    {
        return count($this->users);
    }

    public function getGroupsCount(): int
    {
        return count($this->groups);
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getReachableRoleNames(array $roles): array
    {
        $out = [];

        foreach ($roles as $role) {
            $out[] = $role->getName();
        }

        return $out;
    }
}
