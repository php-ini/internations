<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Dto\Response\Transformer;

use Internations\AdminBundle\Dto\Response\UserResponseDto;
use Internations\AdminBundle\Entity\User;

class UserResponseDtoTransformer extends AbstractResponseDtoTransformer
{
    private GroupsResponseDtoTransformer $groupsResponseDtoTransformer;

    /**
     * UserResponseDtoTransformer constructor.
     * @param GroupsResponseDtoTransformer $groupsResponseDtoTransformer
     */
    public function __construct(GroupsResponseDtoTransformer $groupsResponseDtoTransformer)
    {
        $this->groupsResponseDtoTransformer = $groupsResponseDtoTransformer;
    }

    /**
     * @param User $user
     * @return UserResponseDto
     */
    public function transformFromObject($user, $isNested = false): UserResponseDto
    {
        $dto = new UserResponseDto($user);
        $dto->name = $user->getName();
        $dto->email = $user->getEmail();
        $dto->password = $user->getPassword();
        $dto->roles = $user->getRoles();
        if($isNested) {
            $dto->groups = $this->groupsResponseDtoTransformer->transformFromObjects($user->getGroups());
        }

        $dto->createdAt = $user->getCreatedAt()->format('Y-m-d H:i:s');

        return $dto;
    }

}