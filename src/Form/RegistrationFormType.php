<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('firstname',TextType::class,[
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Votre prénom',
                    "class" => "form-control",
                    "minlength" => "2",
                    "maxlength" => "50"
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir votre prénom',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Votre prénom doit contenir au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'Votre prénom doit contenir au maximum {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('lastname',TextType::class,[
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre Nom',
                    "class" => "form-control",
                    "minlength" => "2",
                    "maxlength" => "50"
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir votre nom',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Votre nom doit contenir au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'Votre nom doit contenir au maximum {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('email', EmailType::class,[
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Votre email',
                    "class" => "form-control",
                    "minlength" => "2",
                    "maxlength" => "50"
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre email',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre prénom doit contenir au moins {{ limit }} caractères',
                        'max' => 180,
                        'maxMessage' => 'Votre prénom doit contenir au maximum {{ limit }} caractères',
                    ]),
                    new Assert\Email([
                        'message' => 'Veuillez saisir un email valide',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Accepter les conditions d'utilisation",
                    ]),
                ],
            ])
            ->add('plainPassword',RepeatedType::class, [
                'type' => PasswordType::class,

                'invalid_message' => 'les mots de passe ne sont pas identiques.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe',
                    'attr' => ["class" => "form-control"]
                 ],
                'second_options' => ['label' => 'Confirmez le mot de passe',
                    'attr' => ["class" => "form-control"]
                ],
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
