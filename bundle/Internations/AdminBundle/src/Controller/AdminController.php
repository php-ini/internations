<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/', name: 'internations_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('internations_admin');
    }

    #[Route('/admin', name: 'internations_admin')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('@InternationsAdmin/index.html.twig');
    }
}