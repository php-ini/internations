<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller;

use Internations\AdminBundle\Entity\User;
use Internations\AdminBundle\Form\UserFormType;
use Internations\AdminBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/internations/users', name: 'internations_users')]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('@InternationsAdmin/users/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/internations/users/create', name: 'internations_users_create')]
    public function create(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUser = $form->getData();

            try {

                $this->userRepository->create($newUser);

            } catch (\Exception $e) {

                return $this->render('@InternationsAdmin/users/create.html.twig', [
                    'form' => $form->createView(),
                    'error' => $e->getMessage()
                ]);
            }

            $this->addFlash('success', 'Success! User was added.');

            return $this->redirectToRoute('internations_users');
        }

        return $this->render('@InternationsAdmin/users/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}