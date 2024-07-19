<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'constraints' => [
                new Length([
                    'min' => 13,
                    'max' => 50
                ])
            ],
            'first_options'  => [
                'label' => 'Votre nouveau mot de passe', 
                'attr' => [
                'placeholder' => 'Choisissez votre nouveau mot de passe'
                ],
                'hash_property_path' => 'password'
            ],
            'second_options' => [
                'label' => 'Confirmez votre nouveau mot de passe',
                'attr' => [
                'placeholder' => 'Confirmez votre nouveau mot de passe'
                ]
            ],
            'mapped' => false,
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Modifier mon mot de passe',
            'attr' => [
                'class' => 'btn-success'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
