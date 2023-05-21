<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
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

    #[Route('/internations/users/edit/{id}', name: 'internations_users_edit')]
    public function edit($id, Request $request): Response
    {
//        $this->checkLoggedInUser($id);
        $user = $this->userRepository->find($id);
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->save($form->getData(), true);
            $this->addFlash('success', 'Success! User was saved.');

            return $this->redirectToRoute('internations_users');
        }

        return $this->render('@InternationsAdmin/users/create.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    #[Route('/internations/users/delete/{id}', methods: ['GET', 'DELETE'], name: 'internations_users_delete')]
    public function delete($id): Response
    {
//        $this->checkLoggedInUser($id);
        $user = $this->userRepository->find($id);

        if (!$user instanceof User) {
            throw new EntityNotFoundException('No user found!');
        }

        $this->userRepository->remove($user, true);

        $this->addFlash('success', 'Success! User was deleted.');

        return $this->redirectToRoute('internations_users');
    }
}