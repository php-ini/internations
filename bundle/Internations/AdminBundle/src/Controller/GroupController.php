<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use Internations\AdminBundle\Entity\Groups;
use Internations\AdminBundle\Form\GroupsFormType;
use Internations\AdminBundle\Repository\GroupsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    const SUB_DOMAIN_NAME = 'groups';

    private GroupsRepository $groupsRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, GroupsRepository $groupsRepository)
    {
        $this->groupsRepository = $groupsRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/internations/' . self::SUB_DOMAIN_NAME, name: 'internations_' . self::SUB_DOMAIN_NAME)]
    public function index(): Response
    {
        $groups = $this->{self::SUB_DOMAIN_NAME . 'Repository'}->findAll();

        return $this->render('@InternationsAdmin/' .  self::SUB_DOMAIN_NAME . '/index.html.twig', [
             self::SUB_DOMAIN_NAME => $groups
        ]);
    }

    #[Route('/internations/' . self::SUB_DOMAIN_NAME . '/create', name: 'internations_' . self::SUB_DOMAIN_NAME . '_create')]
    public function create(Request $request): Response
    {
        $groups = new Groups();
        $form = $this->createForm(GroupsFormType::class, $groups);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newGroup = $form->getData();

            try {

                $this->{self::SUB_DOMAIN_NAME . 'Repository'}->create($newGroup);

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
    public function edit($id, Request $request): Response
    {
//        $this->checkLoggedInUser($id);
        $group = $this->{self::SUB_DOMAIN_NAME . 'Repository'}->find($id);
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
    public function delete($id): Response
    {
//        $this->checkLoggedInUser($id);
        $group = $this->{self::SUB_DOMAIN_NAME . 'Repository'}->find($id);

        if (!$group instanceof Groups) {
            throw new EntityNotFoundException('No group found!');
        }

        $this->{self::SUB_DOMAIN_NAME . 'Repository'}->remove($group, true);

        $this->addFlash('success', 'Success! Group was deleted.');

        return $this->redirectToRoute('internations_' . self::SUB_DOMAIN_NAME);
    }
}