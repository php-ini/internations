<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Dto\Response\Transformer;

use Internations\AdminBundle\Dto\Response\UserResponseDto;
use Internations\AdminBundle\Entity\User;

class UserResponseDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * UserResponseDtoTransformer constructor.
     * @param GroupsResponseDtoTransformer $groupsResponseDtoTransformer
     */
    public function __construct(private GroupsResponseDtoTransformer $groupsResponseDtoTransformer, private RoleResponseDtoTransformer $roleResponseDtoTransformer)
    {
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
        $dto->isActive = $user->isActive();

        if ($isNested) {
            $dto->roles = $this->roleResponseDtoTransformer->transformFromObjects($user->getRoles());
            $dto->groups = $this->groupsResponseDtoTransformer->transformFromObjects($user->getGroups());
        }

        $dto->createdAt = $user->getCreatedAt()->format('Y-m-d H:i:s');

        return $dto;
    }

}