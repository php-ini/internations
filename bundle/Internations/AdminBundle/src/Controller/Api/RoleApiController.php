<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller\Api;

use Internations\AdminBundle\Dto\Response\Transformer\RoleResponseDtoTransformer;
use Internations\AdminBundle\Factory\RoleFactory;
use Internations\AdminBundle\Repository\RoleRepository;
use Internations\AdminBundle\Service\RoleService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RoleApiController extends AbstractApiController
{
    const VERSION = 1;
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const NOT_FOUND_MESSAGE = 'Role not found!';

    public function __construct(
        private RoleRepository $roleRepository,
        private RoleService $roleService,
        private RoleFactory $roleFactory,
        private RoleResponseDtoTransformer $roleResponseDtoTransformer,
    )
    {
    }

    #[Route('/api/v' . self::VERSION . '/roles', methods: ['GET'], name: 'internations_api_get_roles')]
    public function all(): Response
    {
        $roles = $this->roleRepository->findAll();

        $dto = $this->roleResponseDtoTransformer->transformFromObjects($roles, true);

        return $this->respond($dto);
    }

    #[Route('/api/v' . self::VERSION . '/roles/{id}', methods: ['GET'], name: 'internations_api_get_role')]
    public function get($id): Response
    {
        $role = $this->roleRepository->find($id);

        if (!$role) {
            $data = [
                'status' => self::STATUS_FAILED,
                'errors' => self::NOT_FOUND_MESSAGE,
            ];

            return $this->respond($data, Response::HTTP_NOT_FOUND);
        }

        $userDto = $this->roleResponseDtoTransformer->transformFromObject($role, true);

        return $this->respond($userDto);
    }

    #[Route('/api/v' . self::VERSION . '/roles', methods: ['POST'], name: 'internations_api_create_role')]
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        try {
            $data = $request->toArray();

            if (!$request || !array_key_exists('name', $data)) {
                throw new \Exception();
            }

            $roleEntity = $this->roleFactory->create($data);

            $errors = $validator->validate($roleEntity);

            if (count($errors) === 0) {

                $this->roleRepository->create($roleEntity, true);

                $data = [
                    'success' => "Role created successfully",
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

    #[Route('/api/v' . self::VERSION . '/roles/{id}', methods: ['PUT'], name: 'internations_api_update_role')]
    public function update(Request $request, ValidatorInterface $validator, int $id): Response
    {
        $role = $this->roleRepository->find($id);

        if (!$role) {
            $data = [
                'status' => self::STATUS_FAILED,
                'errors' => self::NOT_FOUND_MESSAGE,
            ];

            return $this->respond($data, Response::HTTP_NOT_FOUND);
        }

        try {
            $data = $request->toArray();

            if (!$request || !array_key_exists('name', $data)) {
                throw new \Exception();
            }

            $roleEntity = $this->roleService->updateEntity($role, $data);

            $errors = $validator->validate($roleEntity);

            if (count($errors) === 0) {
                $this->roleRepository->save($roleEntity, true);
                $data = [
                    'status' => self::STATUS_SUCCESS,
                    'success' => "Role updated successfully",
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

    #[Route('/api/v' . self::VERSION . '/roles/{id}', methods: ['DELETE'], name: 'internations_api_delete_role')]
    public function delete($id): Response
    {
        $role = $this->roleRepository->find($id);

        if (!$role) {
            $data = [
                'status' => self::STATUS_FAILED,
                'errors' => self::NOT_FOUND_MESSAGE,
            ];

            return $this->respond($data, Response::HTTP_NOT_FOUND);
        }

        $this->roleRepository->remove($role, true);

        $data = [
            'status' => self::STATUS_SUCCESS,
            'success' => "Role deleted successfully",
        ];

        return $this->respond($data);
    }
}