<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Figure;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class FigureType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'invalid_message' => "Le nom d'une figure ne peut pas dépasser 45 caractères.",
                'attr' => [
                    'maxlength' => 45,
                    'placeholder' => "Saisissez un nom",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un nom',
                    ]),
                ],
            ])

            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => "Décrivez la figure et donnez des indications sur la manière de pouvoir la réaliser",
                    'rows' => 10,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une description',
                    ]),
                ],
            ])

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => "Sélectionnez une catégorie :",
                'choice_label' => function ($categorie) {
                    return $categorie->getName();
                }
            ])

            ->add('FigureMedias', CollectionType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Collez ici le code d'une vidéo embarquée",
                ],
                'entry_type' => FigureMediasType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])

            ->add('images', FileType::class, [
                'data_class' => null,
                'label' => false,
                'multiple' => true,
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'accept' => 'image/jpeg, image/png, image/gif'
                ],
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/png',
                                    'image/gif'
                                ],
                                'mimeTypesMessage' => 'Veuillez sélectionner une image valide',
                            ]),
                        ]
                    ]),
                ],
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
}
