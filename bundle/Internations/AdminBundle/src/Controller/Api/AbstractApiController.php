<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class AbstractApiController extends AbstractFOSRestController
{
    public function respond($data, int $statusCode = Response::HTTP_OK): Response
    {
        return $this->json($data, $statusCode);
    }

    protected function transformJsonBody(Request $request)
    {
        $data = $request->toArray();

        if ($data === null) {
            return $request;
        }

        return $data;
    }
}