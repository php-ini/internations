<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/internations/admin', name: 'internations_admin')]
    public function index(): Response
    {
        return $this->render('@InternationsAdmin/index.html.twig');
    }
}