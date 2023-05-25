<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller;

use Internations\AdminBundle\Dto\Response\Transformer\UserResponseDtoTransformer;
use Internations\AdminBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserApiController extends AbstractApiController
{
    const VERSION = 1;

    private UserRepository $userRepository;
    private UserResponseDtoTransformer $userResponseDtoTransformer;

    public function __construct(
        UserRepository $userRepository,
        UserResponseDtoTransformer $userResponseDtoTransformer,
    )
    {
        $this->userRepository = $userRepository;
        $this->userResponseDtoTransformer = $userResponseDtoTransformer;
    }

    #[Route('/api/v1/users', methods: ['GET'], name: 'internations_api_get_users')]
    public function all(): Response
    {
        $users = $this->userRepository->findAll();

        $dto = $this->userResponseDtoTransformer->transformFromObjects($users, true);

        return $this->respond($dto);
    }
}