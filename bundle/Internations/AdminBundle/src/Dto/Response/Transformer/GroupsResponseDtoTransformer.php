<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Dto\Response\Transformer;

use Internations\AdminBundle\Dto\Response\GroupsResponseDto;
use Internations\AdminBundle\Entity\Groups;

class GroupsResponseDtoTransformer extends AbstractResponseDtoTransformer
{
    private UserResponseDtoTransformer $userResponseDtoTransformer;

    /**
     * GroupsResponseDtoTransformer constructor.
     * @param UserResponseDtoTransformer $userResponseDtoTransformer
     */
    public function __construct(UserResponseDtoTransformer $userResponseDtoTransformer)
    {
        $this->userResponseDtoTransformer = $userResponseDtoTransformer;
    }

    /**
     * @param Groups $group
     * @return GroupsResponseDto
     */
    public function transformFromObject($group, $isNested = false): GroupsResponseDto
    {
        $dto = new GroupsResponseDto($group);
        $dto->id = $group->getId();
        $dto->name = $group->getName();
        if($isNested) {
            $dto->users = $this->userResponseDtoTransformer->transformFromObjects($group->getUsers());
        }

        $dto->createdAt = $group->getCreatedAt()->format('Y-m-d H:i:s');

        return $dto;
    }

}