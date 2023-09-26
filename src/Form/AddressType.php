<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name',TextType::class, [
                'label' => 'Donnez un nom à votre adresse',
                'attr' => [
                    'placeholder' => 'Nommez votre adresse'
                ]
            ])
            ->add('firstname',TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ]
            ])
            ->add('lastname',TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Votre nom'
                ]
            ])
            ->add('adress',TextType::class, [
        'label' => 'Adresses',
        'attr' => [
            'placeholder' => 'votre adresse'
        ]
    ])
            ->add('postal',TextType::class, [
        'label' => 'Code postal',
        'attr' => [
            'placeholder' => 'Code postal'
        ]
    ])
            ->add('city',TextType::class, [
        'label' => 'Ville',
        'attr' => [
            'placeholder' => 'Ville'
        ]
    ])
            ->add('pays',CountryType::class, [
        'label' => 'Votre pays',
        'preferred_choices' => ['FR','CA','ES','IT','DE','GB','US'],
        'attr' => [
            'placeholder' => 'France',

                    ]
    ])
            ->add('phone',TelType::class, [
                'label' => 'Votre téléphone',
                'attr' => [
                    'placeholder' => 'Votre téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'lp-btn myz-5'
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
