<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller\Api;

use Internations\AdminBundle\Dto\Response\Transformer\GroupsResponseDtoTransformer;
use Internations\AdminBundle\Factory\GroupFactory;
use Internations\AdminBundle\Repository\GroupsRepository;
use Internations\AdminBundle\Service\GroupService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GroupsApiController extends AbstractApiController
{
    const VERSION = 1;
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const NOT_FOUND_MESSAGE = 'Group not found!';

    public function __construct(
        private GroupsRepository $groupsRepository,
        private GroupService $groupService,
        private GroupFactory $groupFactory,
        private GroupsResponseDtoTransformer $groupsResponseDtoTransformer,
    )
    {
    }

    #[Route('/api/v' . self::VERSION . '/groups', methods: ['GET'], name: 'internations_api_get_groups')]
    public function all(): Response
    {
        $groups = $this->groupsRepository->findAll();

        $dto = $this->groupsResponseDtoTransformer->transformFromObjects($groups, true);

        return $this->respond($dto);
    }

    #[Route('/api/v' . self::VERSION . '/groups/{id}', methods: ['GET'], name: 'internations_api_get_group')]
    public function get($id): Response
    {
        $groups = $this->groupsRepository->find($id);

        if (!$groups) {
            $data = [
                'status' => self::STATUS_FAILED,
                'errors' => self::NOT_FOUND_MESSAGE,
            ];

            return $this->respond($data, Response::HTTP_NOT_FOUND);
        }

        $groupDto = $this->groupsResponseDtoTransformer->transformFromObject($groups, true);

        return $this->respond($groupDto);
    }

    #[Route('/api/v' . self::VERSION . '/groups', methods: ['POST'], name: 'internations_api_create_group')]
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        try {
            $data = $request->toArray();

            if (!$request || !array_key_exists('name', $data)) {
                throw new \Exception();
            }

            $groupEntity = $this->groupFactory->create($data);

            $errors = $validator->validate($groupEntity);

            if (count($errors) === 0) {

                $this->groupsRepository->create($groupEntity, true);

                $data = [
                    'success' => "Group created successfully",
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

    #[Route('/api/v' . self::VERSION . '/groups/{id}', methods: ['PUT'], name: 'internations_api_update_group')]
    public function update(Request $request, ValidatorInterface $validator, int $id): Response
    {
        $group = $this->groupsRepository->find($id);

        if (!$group) {
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

            $groupEntity = $this->groupService->updateEntity($group, $data);

            $errors = $validator->validate($groupEntity);

            if (count($errors) === 0) {
                $this->groupsRepository->save($groupEntity, true);
                $data = [
                    'status' => self::STATUS_SUCCESS,
                    'success' => "Group updated successfully",
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

    #[Route('/api/v' . self::VERSION . '/groups/{id}', methods: ['DELETE'], name: 'internations_api_delete_group')]
    public function delete($id): Response
    {
        $group = $this->groupsRepository->find($id);

        if (!$group) {
            $data = [
                'status' => self::STATUS_FAILED,
                'errors' => self::NOT_FOUND_MESSAGE,
            ];

            return $this->respond($data, Response::HTTP_NOT_FOUND);
        }

        $this->groupsRepository->remove($group, true);

        $data = [
            'status' => self::STATUS_SUCCESS,
            'success' => "Group deleted successfully",
        ];

        return $this->respond($data);
    }
}