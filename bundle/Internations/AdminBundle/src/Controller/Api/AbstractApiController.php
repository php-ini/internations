<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;

class AbstractApiController extends AbstractFOSRestController
{
    public function respond($data, int $statusCode = Response::HTTP_OK): Response
    {
        return $this->json($data, $statusCode);
    }
}