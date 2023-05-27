<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Form;

use Internations\AdminBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Internations\AdminBundle\Entity\Role;

class RolesType extends AbstractType
{
    public function __construct()
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Role::class,
            'multiple' => true,
            'expanded' => true,
            'getter' => function (User $user, FormInterface $form): array {
                return $user->getRolesObjects();
            }
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}