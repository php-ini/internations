<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Internations\AdminBundle\Entity\Groups;
use Symfony\Component\Validator\Constraint as Assert;
use Internations\AdminBundle\Repository\RoleRepository;
use Internations\AdminBundle\Validator\Constraints as CustomAssert;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
final class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: false)]
    #[CustomAssert\CheckName]
    private ?string $name = null;

    #[ORM\JoinTable(name: 'role_user')]
    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    private $users;

    #[ORM\JoinTable(name: 'role_group')]
    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Groups::class, fetch: 'EXTRA_LAZY')]
    private $groups;

    public function __construct() {
        $this->users = new ArrayCollection();
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

    public function getLkpUsersCount(): ?int
    {
        return count($this->users);
    }

    public function getLkpGroupsCount(): ?int
    {
        return count($this->groups);
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

    public function removeGroup(Group $group): bool
    {
        return $this->users->removeElement($group);
    }

    public function getGroups(): ?Collection
    {
        return $this->groups;
    }

    public function __toString()
    {
        return $this->name;
    }
}
