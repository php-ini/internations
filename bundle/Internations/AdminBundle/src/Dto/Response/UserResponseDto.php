<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Dto\Response;

use JMS\Serializer\Annotation as Serialization;
use Symfony\Component\Serializer\Annotation\MaxDepth;

class UserResponseDto
{
    #[Serialization\Type("int")]
    public int $id;

    #[Serialization\Type("string")]
    public string $name;

    #[Serialization\Type("string")]
    public string $email;

    #[Serialization\Type("string")]
    public string $password;

    #[Serialization\Type("boolean")]
    public bool $isActive;

    #[MaxDepth(1)]
    public array $groups;

    #[MaxDepth(1)]
    public array $roles;

    #[Serialization\Type("DateTime<'Y-m-d\TH:i:s'>")]
    public string $createdAt;
}