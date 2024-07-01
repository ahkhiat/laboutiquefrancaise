<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Entrez votre prénom',
                'attr' => [
                    'placeholder' => 'Indiquez votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Entrez votre nom',
                'attr' => [
                    'placeholder' => 'Indiquez votre nom'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Entrez votre adresse',
                'attr' => [
                    'placeholder' => 'Indiquez votre adresse'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Entrez votre code postal',
                'attr' => [
                    'placeholder' => 'Indiquez votre code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Entrez votre ville',
                'attr' => [
                    'placeholder' => 'Indiquez votre ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Entrez votre pays',
                'attr' => [
                    'placeholder' => 'Indiquez votre pays'
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Entrez votre numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Indiquez votre numéro de téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Sauvegarder',
                'attr' => [
                    'class' => 'btn-success'
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
