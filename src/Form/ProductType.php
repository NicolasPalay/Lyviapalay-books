<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description',TextareaType::class,
                [
                    'attr'=>[
                        'class'=>'editor'
                    ]
                ])
            ->add('excerpt',TextareaType::class)
            ->add('isbn')
            ->add('price',MoneyType::class)
            ->add('weight')
            ->add('categoryProduct',null,[
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
