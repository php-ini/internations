<?php

declare(strict_types=1);

namespace Internations\AdminBundle\Form;

use Internations\AdminBundle\Entity\Groups;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GroupsFormType extends AbstractType
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
            ->add('is_active', CheckboxType::class, [
                'label'    => 'Active?',
                'required' => false,
                'attr' => [],
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
                'row_attr' => [
                    'class' => 'form-group',
                ],

            ])
            ->add('save', SubmitType::class, ['label' => 'Save Group'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Groups::class,
        ]);
    }
}
