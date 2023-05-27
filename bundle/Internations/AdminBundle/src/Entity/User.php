<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Internations\AdminBundle\Enum\Roles;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraint as Assert;
use Internations\AdminBundle\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Internations\AdminBundle\Validator\Constraints as CustomAssert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email')]
#[ORM\HasLifecycleCallbacks]
final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: false)]
    #[CustomAssert\CheckName]
    private ?string $name = null;

    #[ORM\Column(name: 'email', type: 'string', length: 60, unique: true)]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Assert\NotBlank]
    private ?string $email = null;

    #[ORM\Column(length: 30)]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deleted_at = null;

    #[ORM\JoinTable(name: 'role_user')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'role_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Role::class)]
    private $roles;

    #[ORM\JoinTable(name: 'user_group')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Groups::class)]
    private $groups;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }

    public function getRoles(): array
    {
        $roles = [];

        foreach($this->roles->toArray() as $role) {
            $roles[] = $role->getName();
        }

        return $roles;
    }

    public function getRolesObjects(): array
    {
        return $this->roles->toArray();
    }

    public function getFormRoles(): array
    {
        return $this->roles->toArray();
    }

    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function setGroups(array $groups): self
    {
        $this->groups = $groups;

        return $this;
    }

    public function addGroup(Groups $group): self
    {
        $this->groups[] = $group;

        return $this;
    }

    public function removeGroup(Groups $group): bool
    {
        return $this->groups->removeElement($group);
    }

    public function getGroups(): ?Collection
    {
        return $this->groups;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeImmutable $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    public function isAdmin()
    {
        return in_array(Roles::ADMIN_ROLE, $this->roles);
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function transformFromArray(array $data): self
    {
        $this->setName($data['name']);
        $this->setPassword($data['password']);
        $this->setEmail($data['email']);
        $this->setIsActive(isset($data['is_active']) ? (bool)$data['is_active'] : false);

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
