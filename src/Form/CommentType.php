<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content',TextareaType::class,[
                'label' => 'Votre commentaire',
                'attr' => [
                    'placeholder' => 'Votre commentaire'
                ]
            ])
            ->add('rgpt',CheckboxType::class,[
                'label' => 'J\'accepte le stockage de mes données personnelles',
                'mapped' => false,
                'required' => true,
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
