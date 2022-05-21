<?php

namespace App\Form;

use App\Entity\FigureMedias;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class FigureMediasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url',  TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Collez ici le code d'une vidéo embarquée (iframe)",
                ],
                'constraints' => new Regex([
                    'pattern' => "/^<iframe[^>]*>\s*<\/iframe>/",
                    'message' => "Le code saisi n'est pas valide. Copiez/collez le code d'une vidéo embarquée, ce code doit commencer par <iframe>",
                ])
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FigureMedias::class,
        ]);
    }
}
