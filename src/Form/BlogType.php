<?php

namespace App\Form;

use App\Entity\Blog;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content',TextareaType::class,
                [
                    'attr'=>[
                        'class'=>'editor'
                    ]
                ])
            ->add('excerpt',TextareaType::class)
            ->add('category',null,[
                'choice_label'=>'name'
            ])
            ->add('pictures',FileType::class,[
                'multiple'=>true,
                'label'=>'Ajouter des photos',
                'mapped'=>false,
                'required' => false,
                'attr' => [
                    'accept' => 'image/jpeg, image/png, image/gif',

                    'onchange' => "previewPictures(this)"
                ]])
        ->add('publish',CheckboxType::class,
            [
                'label'=>'Publier',
                'required'=>false
            ])
        ->add('auteur',TextType::class,
            [
                'label'=>'Auteur',
                'required'=>false
            ])
            ->add('promote',CheckboxType::class,
                [
                    'label'=>'Mettre en avant',
                    'required'=>false
                ])
            ->add('dedication',null,[
                'choice_label'=>'address',
                'required'=>false
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
