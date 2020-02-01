<?php

namespace App\Form\Back\User;

use App\Entity\Account\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', ChoiceType::class, [
                'label' => 'Civilité',
                'choices' => [
                    ' ' => ' ',
                    'Monsieur' => 'Monsieur',
                    'Madame' => 'Madame'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'John'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Doe'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'john.doe@gmail.com'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Tapez votre  mot de passe'
                ]
            ])
            ->add('passwordConfirm', PasswordType::class, [
                'label' => 'Confirmation de mot de passe',
                'attr' => [
                    'placeholder' => 'Confirmez votre mot de passe'
                ]
            ])
            ->add('role', ChoiceType::class, [
                'label'   => 'Choisissez un role',
                'choices' => [
                    ''               => '',
                    'Etudiant(e)'    => 'Etudiant(e)',
                    'Professeur' => 'Professeur',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
