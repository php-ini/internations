<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/internations/users', name: 'internations_users')]
    public function index(): Response
    {
        return $this->render('@InternationsAdmin/index.html.twig');
    }
}