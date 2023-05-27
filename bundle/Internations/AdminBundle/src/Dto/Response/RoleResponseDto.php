<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Dto\Response;

use JMS\Serializer\Annotation as Serialization;
use Symfony\Component\Serializer\Annotation\MaxDepth;

class RoleResponseDto
{
    #[Serialization\Type("string")]
    public string $name;

    #[MaxDepth(1)]
    public array $users;
}