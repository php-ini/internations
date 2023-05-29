<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Dto\Response;

use JMS\Serializer\Annotation as Serialization;
//use JMS\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Annotation\MaxDepth;

class GroupsResponseDto
{
    #[Serialization\Type("int")]
    public int $id;

    #[Serialization\Type("string")]
    public string $name;

    #[MaxDepth(1)]
    public array $users;

    #[Serialization\Type("DateTime<'Y-m-d\TH:i:s'>")]
    public string $createdAt;
}