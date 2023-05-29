<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Internations\AdminBundle\Entity\Role;
use Internations\AdminBundle\Enum\Roles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Internations\AdminBundle\Form\RoleFormType;
use Internations\AdminBundle\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class RoleController extends AbstractController
{
    const SUB_DOMAIN_NAME = 'role';

    public function __construct(private EntityManagerInterface $entityManager, private RoleRepository $roleRepository)
    {
    }

    #[Route('/internations/' . self::SUB_DOMAIN_NAME, name: 'internations_' . self::SUB_DOMAIN_NAME)]
    #[IsGranted(Roles::USER_ROLE)]
    public function index(): Response
    {
        $roles = $this->roleRepository->findAll();

        return $this->render('@InternationsAdmin/' .  self::SUB_DOMAIN_NAME . '/index.html.twig', [
             self::SUB_DOMAIN_NAME . 's' => $roles
        ]);
    }

    #[Route('/internations/' . self::SUB_DOMAIN_NAME . '/create', name: 'internations_' . self::SUB_DOMAIN_NAME . '_create')]
    #[IsGranted(Roles::ADMIN_ROLE)]
    public function create(Request $request): Response
    {
        $role = new Role();
        $form = $this->createForm(RoleFormType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newRole = $form->getData();

            try {

                $this->roleRepository->create($newRole);

            } catch (\Exception $e) {

                return $this->render('@InternationsAdmin/' . self::SUB_DOMAIN_NAME . '/create.html.twig', [
                    'form' => $form->createView(),
                    'error' => $e->getMessage()
                ]);
            }

            $this->addFlash('success', self::SUB_DOMAIN_NAME . ' was added!');

            return $this->redirectToRoute('internations_' . self::SUB_DOMAIN_NAME);
        }

        return $this->render('@InternationsAdmin/' . self::SUB_DOMAIN_NAME . '/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/internations/' . self::SUB_DOMAIN_NAME . '/edit/{id}', name: 'internations_' . self::SUB_DOMAIN_NAME . '_edit')]
    #[IsGranted(Roles::ADMIN_ROLE)]
    public function edit($id, Request $request): Response
    {
        $role = $this->roleRepository->find($id);
        $form = $this->createForm(RoleFormType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->roleRepository->save($form->getData(), true);
            $this->addFlash('success', self::SUB_DOMAIN_NAME . ' was saved!');

            return $this->redirectToRoute('internations_' . self::SUB_DOMAIN_NAME);
        }

        return $this->render('@InternationsAdmin/' . self::SUB_DOMAIN_NAME . '/create.html.twig', [
            'role' => $role,
            'form' => $form->createView()
        ]);
    }

    #[Route('/internations/' . self::SUB_DOMAIN_NAME . '/delete/{id}', methods: ['GET', 'DELETE'], name: 'internations_' . self::SUB_DOMAIN_NAME . '_delete')]
    #[IsGranted(Roles::ADMIN_ROLE)]
    public function delete($id): Response
    {
        $role = $this->roleRepository->find($id);

        if (!$role instanceof Role) {
            throw new EntityNotFoundException('No ' . self::SUB_DOMAIN_NAME . ' found!');
        }

        $this->roleRepository->remove($role, true);

        $this->addFlash('success', self::SUB_DOMAIN_NAME . ' was deleted!');

        return $this->redirectToRoute('internations_' . self::SUB_DOMAIN_NAME);
    }
}