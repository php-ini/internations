<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Internations\AdminBundle\Enum\Roles;
use Internations\AdminBundle\Entity\User;
use Internations\AdminBundle\Entity\Groups;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserFormType extends AbstractType
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
            ->add('email', EmailType::class, [
                'attr' => array(
                    'class' => 'form-check',
                    'placeholder' => 'Enter Email...'
                ),
                'label' => 'Email',
                'row_attr' => [
                    'class' => 'form-group',
                ],
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter Password...',
                ),
                'row_attr' => [
                    'class' => 'form-group',
                ],
                'label' => 'Enter Password',
                'required' => true
            ])
            ->add('roles', ChoiceType::class, [
                'attr' => array(
                    'class' => 'form-control',
                ),
                'choices' => Roles::ROLES_LIST,
                'label' => 'Select the user roles',
                'multiple' => true,
                'row_attr' => [
                    'class' => 'form-group',
                ],
                'required' => true,
                'placeholder' => 'Select'
            ])
            ->add('groups', EntityType::class, array(
                'class'     => Groups::class,
                'expanded'  => true,
                'multiple'  => true,
            ))
            ->add('is_active', CheckboxType::class, [
                'label'    => 'Active user?',
                'required' => false,
                'attr' => [],
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
                'row_attr' => [
                    'class' => 'form-group',
                ],

            ])
            ->add('save', SubmitType::class, ['label' => 'Save User'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
