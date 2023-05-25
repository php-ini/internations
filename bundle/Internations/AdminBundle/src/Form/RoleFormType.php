<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Form;

use Internations\AdminBundle\Entity\Groups;
use Internations\AdminBundle\Entity\User;
use Internations\AdminBundle\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class RoleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => array(
                    'class' => 'form-check',
                    'placeholder' => 'Enter Name...',
                ),
                'label' => 'Name',
                'row_attr' => [
                    'class' => 'form-group',
                ],
                'required' => false
            ])
            ->add('users', EntityType::class, array(
                'class'     => User::class,
                'expanded'  => true,
                'multiple'  => true,
            ))
            ->add('groups', EntityType::class, array(
                'class'     => Groups::class,
                'expanded'  => true,
                'multiple'  => true,
            ))
            ->add('save', SubmitType::class, ['label' => 'Save Group'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
