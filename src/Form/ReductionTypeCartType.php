<?php

namespace App\Form;

use App\Entity\Reduction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReductionTypeCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reductCode', TextType::class, [
                'label' => 'Code promo',
                'attr' => [
                    'placeholder' => 'Entrez un code promo'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Code promo',
                'attr' => [
                    'class' => 'lp-btn mt-5'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reduction::class,
        ]);
    }
}
