<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Dto\Response\Transformer;

use Internations\AdminBundle\Dto\Response\RoleResponseDto;
use Internations\AdminBundle\Entity\Role;

class RoleResponseDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * RoleResponseDtoTransformer constructor.
     * @param UserResponseDtoTransformer $userResponseDtoTransformer
     */
    public function __construct(private UserResponseDtoTransformer $userResponseDtoTransformer)
    {
    }

    /**
     * @param Role $role
     * @return RoleResponseDto
     */
    public function transformFromObject($role, $isNested = false): RoleResponseDto
    {
        $dto = new RoleResponseDto($role);
        $dto->name = $role->getName();

        if ($isNested) {
            $dto->users = $this->userResponseDtoTransformer->transformFromObjects($role->getUsers());
        }

        return $dto;
    }
}