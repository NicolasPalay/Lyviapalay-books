<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Comment1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content',TextareaType::class,[
                'label' => 'Commentaire',
                'attr' => [
                    'placeholder' => 'Votre commentaire',
                    'rows' => 5,
                ],
            ])
            ->add('active')
            ->add('rgpt', CheckboxType::class, [
            'label' => 'RGPD',
                'required' => false,

            ])
            ->add('blog', null, [
                'choice_label' => 'title',
            ])
            ->add('product', null, [
                'choice_label' => 'name',
            ])
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
