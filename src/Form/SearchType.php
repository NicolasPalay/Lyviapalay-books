<?php

namespace App\Form;

use App\Classe\Search;
use App\Entity\CategoryProduct;
use App\Model\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('string', TextType::class,[
                'label'=> false,
                'required'=>false,
                'attr'=>[
                    'placeholder' => 'Votre recherche...',
                    'class' => 'form-control-sm'
           ] ])
        ->add('categoryProduct', EntityType::class,[
                'class'=> CategoryProduct::class,
                'label'=> false,
                'required'=>false,
                'multiple'=>true,
                'expanded'=>true,
                'attr'=>[
                    'class' => 'form-control-sm'
                ]
            ])
        ->add('submit', SubmitType::class,[
            'label'=> 'Filtrer',
            'attr'=>[
                'class' => 'btn-block btn-info'
            ]
        ]);
        }




    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}