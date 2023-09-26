<?php

namespace App\Form;

use App\Entity\Decication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address',TextType::class,[
                'label' => 'Adresse de la dédicace',
            ])
            ->add('dateDedication',DateTimeType::class,[
'label' => 'Date de dédicace',
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('dateDedicationEnd',DateTimeType::class,[
'label' => 'Date de fin de dédicace',
                'widget' => 'single_text',
                'html5' => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Decication::class,
        ]);
    }
}
