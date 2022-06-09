<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'invalid_message' => "Le nom d'utilisateur ne peut pas dépasser 45 caractères.",
                'attr' => [
                    'maxlength' => 45,
                    'placeholder' => "Saisissez un nom d'utilisateur unique",
                ]
            ])

            ->add('email', EmailType::class, [
                'attr' => [
                    'maxlength' => 255,
                    'placeholder' => "Adresse@domaine.com",
                ]
            ])

            ->add('password', PasswordType::class, [
                'attr' => [
                    'placeholder' => "Saisissez un mot de passe",
                    'autocomplete' => 'new-password',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe.',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])

            ->add('roles', ChoiceType::class, [
                'multiple' => false,
                'expanded' => false,
                'choices'  => [
                    'Utilisateur'    => 'ROLE_USER',
                    'Modérateur'     => 'ROLE_MODO',
                    'Administrateur' => 'ROLE_ADMIN',
                ]
            ])

            ->add('avatar', FileType::class, [
                'label' => "Sélectionnez un avatar (facultatif) :",
                'multiple' => false,
                'required' => false,
                'mapped' => false,
            ])


            // the event that will handle the conditional field
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                array($this, 'onPreSetData')
            )
        ;

        // Data transformer
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        // Add an optional password field if is a user editing form
        if ( $data instanceof User ) {
            $form->add(
                'password',
                PasswordType::class,
                [
                    'required' => false,
                    'mapped' => false
                ]
            );
        }
    }
}
