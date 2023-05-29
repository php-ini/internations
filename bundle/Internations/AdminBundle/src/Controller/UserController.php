<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Internations\AdminBundle\Entity\User;
use Internations\AdminBundle\Enum\Roles;
use Internations\AdminBundle\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Internations\AdminBundle\Form\UserFormType;
use Internations\AdminBundle\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserService $userService,
    )
    {
    }

    #[Route('/internations/users', name: 'internations_users')]
    #[IsGranted(Roles::USER_ROLE)]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('@InternationsAdmin/users/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/internations/users/create', name: 'internations_users_create')]
    #[IsGranted(Roles::ADMIN_ROLE)]
    public function create(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUser = $form->getData();

            try {

                $this->userRepository->create($this->userService->hashUserPassword($newUser));

            } catch (\Exception $e) {

                return $this->render('@InternationsAdmin/users/create.html.twig', [
                    'form' => $form->createView(),
                    'error' => $e->getMessage()
                ]);
            }

            $this->addFlash('success', 'User was added!');

            return $this->redirectToRoute('internations_users');
        }

        return $this->render('@InternationsAdmin/users/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/internations/users/edit/{id}', name: 'internations_users_edit')]
    #[IsGranted(Roles::ADMIN_ROLE)]
    public function edit($id, Request $request): Response
    {
        $user = $this->userRepository->find($id);
        $form = $this->createForm(UserFormType::class, $user);

        if ($request->isMethod('POST')) {
            $newUser = $this->userService->transformFromArray((int)$id, $request->get('user_form'));
            $this->userRepository->save($newUser, true);
            $this->addFlash('success', 'User was saved!');

            return $this->redirectToRoute('internations_users');
        }

        return $this->render('@InternationsAdmin/users/create.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    #[Route('/internations/users/delete/{id}', methods: ['GET', 'DELETE'], name: 'internations_users_delete')]
    #[IsGranted(Roles::ADMIN_ROLE)]
    public function delete($id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user instanceof User) {
            throw new EntityNotFoundException('No user found!');
        }

        $this->userRepository->remove($user, true);

        $this->addFlash('success', 'User was deleted!');

        return $this->redirectToRoute('internations_users');
    }
}