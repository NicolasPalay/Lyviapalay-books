<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('slug')
            ->add('description')
            ->add('price')
            ->add('category')
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
