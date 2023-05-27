<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller\Api;

use Internations\AdminBundle\Dto\Response\Transformer\GroupsResponseDtoTransformer;
use Internations\AdminBundle\Repository\GroupsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupsApiController extends AbstractApiController
{
    const VERSION = 1;

    public function __construct(
        private GroupsRepository $groupsRepository,
        private GroupsResponseDtoTransformer $groupsResponseDtoTransformer,
    )
    {
    }

    #[Route('/api/v1/groups', methods: ['GET'], name: 'internations_api_get_groups')]
    public function all(): Response
    {
        $groups = $this->groupsRepository->findAll();

        $dto = $this->groupsResponseDtoTransformer->transformFromObjects($groups, true);

        return $this->respond($dto);
    }
}