<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller\Api;

use Internations\AdminBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Internations\AdminBundle\Factory\UserFactory;
use Internations\AdminBundle\Service\UserService;
use Internations\AdminBundle\Repository\UserRepository;
use Internations\AdminBundle\Dto\Response\Transformer\UserResponseDtoTransformer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserApiController extends AbstractApiController
{
    const VERSION = 1;
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    public function __construct(
        private UserRepository $userRepository,
        private UserResponseDtoTransformer $userResponseDtoTransformer,
        private UserFactory $userFactory,
        private UserService $userService,
    )
    {
    }

    #[Route('/api/v' . self::VERSION . '/users', methods: ['GET'], name: 'internations_api_get_users')]
    public function all(): Response
    {
        $users = $this->userRepository->findAll();

        $dto = $this->userResponseDtoTransformer->transformFromObjects($users, true);

        return $this->respond($dto);
    }

    #[Route('/api/v' . self::VERSION . '/users/{id}', methods: ['GET'], name: 'internations_api_get_user')]
    public function get($id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            $data = [
                'status' => self::STATUS_FAILED,
                'errors' => "User not found",
            ];

            return $this->respond($data, Response::HTTP_NOT_FOUND);
        }

        $userDto = $this->userResponseDtoTransformer->transformFromObject($user, true);

        return $this->respond($userDto);
    }

    #[Route('/api/v' . self::VERSION . '/users', methods: ['POST'], name: 'internations_api_create_users')]
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        try {
            $data = $request->toArray();

            if (!$request || !array_key_exists('email', $data)) {
                throw new \Exception();
            }

            $userEntity = $this->userService
                ->hashUserPassword($this->userFactory->create($data));

            $errors = $validator->validate($userEntity, null, ['new']);

            if (count($errors) === 0) {

                $this->userRepository->create($userEntity, true);

                $data = [
                    'success' => "User created successfully",
                ];

                return $this->respond($data);
            }

            return $this->respond(['error' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (\Exception $e) {
            $data = [
                'status' => self::STATUS_FAILED,
                'errors' => $e->getMessage(),
            ];

            return $this->respond($data, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    #[Route('/api/v' . self::VERSION . '/users/{id}', methods: ['PUT'], name: 'internations_api_update_users')]
    public function update(Request $request, ValidatorInterface $validator, int $id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            $data = [
                'status' => self::STATUS_FAILED,
                'errors' => "User not found",
            ];

            return $this->respond($data, Response::HTTP_NOT_FOUND);
        }

        try {
            $data = $request->toArray();

            if (!$request || !array_key_exists('email', $data)) {
                throw new \Exception();
            }

            $userEntity = $this->userService
                ->hashUserPassword($this->userFactory->create($data));
            $errors = $validator->validate($userEntity);

            if (count($errors) === 0) {

                $newUser = $this->userService->updateEntity($user, $userEntity);

                $this->userRepository->save($newUser, true);

                $data = [
                    'status' => self::STATUS_SUCCESS,
                    'success' => "User updated successfully",
                ];

                return $this->respond($data);
            }

            return $this->respond(['error' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (\Exception $e) {
            $data = [
                'status' => self::STATUS_FAILED,
                'errors' => $e->getMessage(),
            ];

            return $this->respond($data, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    #[Route('/api/v' . self::VERSION . '/users/{id}', methods: ['DELETE'], name: 'internations_api_delete_user')]
    public function delete($id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            $data = [
                'status' => self::STATUS_FAILED,
                'errors' => "User not found",
            ];

            return $this->respond($data, Response::HTTP_NOT_FOUND);
        }

        $this->userRepository->remove($user, true);

        $data = [
            'status' => self::STATUS_SUCCESS,
            'success' => "User deleted successfully",
        ];

        return $this->respond($data);
    }
}