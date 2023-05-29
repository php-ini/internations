<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Internations\AdminBundle\Entity\Groups;
use Internations\AdminBundle\Entity\Role;
use Internations\AdminBundle\Enum\Roles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Internations\AdminBundle\Form\GroupsFormType;
use Internations\AdminBundle\Repository\GroupsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class GroupController extends AbstractController
{
    const SUB_DOMAIN_NAME = 'groups';

    public function __construct(private EntityManagerInterface $entityManager, private GroupsRepository $groupsRepository)
    {
    }

    #[Route('/internations/' . self::SUB_DOMAIN_NAME, name: 'internations_' . self::SUB_DOMAIN_NAME)]
    #[IsGranted(Roles::USER_ROLE)]
    public function index(): Response
    {
        $groups = $this->groupsRepository->findAll();

        return $this->render('@InternationsAdmin/' . self::SUB_DOMAIN_NAME . '/index.html.twig', [
            self::SUB_DOMAIN_NAME => $groups
        ]);
    }

    #[Route('/internations/' . self::SUB_DOMAIN_NAME . '/create', name: 'internations_' . self::SUB_DOMAIN_NAME . '_create')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $groups = new Groups();
        $roles = $this->entityManager->getRepository(Role::class)->findAll();
        $form = $this->createForm(GroupsFormType::class, $groups, ['create' => true, 'roles' => $roles]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newGroup = $form->getData();

            try {

                $this->groupsRepository->create($newGroup);

            } catch (\Exception $e) {

                return $this->render('@InternationsAdmin/' . self::SUB_DOMAIN_NAME . '/create.html.twig', [
                    'form' => $form->createView(),
                    'error' => $e->getMessage()
                ]);
            }

            $this->addFlash('success', 'Success! Group was added.');

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
        $group = $this->groupsRepository->find($id);
        $form = $this->createForm(GroupsFormType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->{self::SUB_DOMAIN_NAME . 'Repository'}->save($form->getData(), true);
            $this->addFlash('success', 'Success! Group was saved.');

            return $this->redirectToRoute('internations_' . self::SUB_DOMAIN_NAME);
        }

        return $this->render('@InternationsAdmin/' . self::SUB_DOMAIN_NAME . '/create.html.twig', [
            'group' => $group,
            'form' => $form->createView()
        ]);
    }

    #[Route('/internations/' . self::SUB_DOMAIN_NAME . '/delete/{id}', methods: ['GET', 'DELETE'], name: 'internations_' . self::SUB_DOMAIN_NAME . '_delete')]
    #[IsGranted(Roles::ADMIN_ROLE)]
    public function delete($id): Response
    {
        $group = $this->groupsRepository->find($id);

        if (!$group instanceof Groups) {
            throw new EntityNotFoundException('No group found!');
        }

        $this->{self::SUB_DOMAIN_NAME . 'Repository'}->remove($group, true);

        $this->addFlash('success', 'Success! Group was deleted.');

        return $this->redirectToRoute('internations_' . self::SUB_DOMAIN_NAME);
    }
}