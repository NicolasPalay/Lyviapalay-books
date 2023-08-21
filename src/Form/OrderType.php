<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('adresses', EntityType::class,
                [
                    'class' => Address::class,
                    'choices' => $user->getAdresses(),
                    'expanded' => true,
                    'multiple' => false,
                    'label' => false,

                ])
            ->add('carriers', EntityType::class,
                [
                    'class' => Carrier::class,

                    'expanded' => true,
                    'multiple' => false,
                    'label' => 'Choisissez votre transporteur'

                ])
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Valider ma commande',
                    'attr' => [
                        'class' => 'btn btn-success btn-block'
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}
