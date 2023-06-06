<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class TextTranslateType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $languageChoices = [
            'English' => 'english',
            'French' => 'french',
            'German' => 'german',
            'Italian' => 'italian',
            'Spanish' => 'spanish'
        ];

        $builder
        ->add(
            'from_language',
            ChoiceType::class,
            [
                'label' => false,
                'choices' => $languageChoices,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 'english'
            ]
            )
            ->add(
                'to_language',
                ChoiceType::class,
                [
                    'label' => false,
                    'choices' => $languageChoices,
                    'attr' => [
                        'class' => 'form-control mt-1'
                    ],
                    'row_attr' => [
                        'class' => 'mb-3'
                    ],
                    'data' => 'french'
                ]
                )
        ->add(
            'prompt',
            TextareaType::class,
            [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ]
            )
        ->add(
            'save',
            SubmitType::class,
            [
                'label' => 'Submit',
                'attr' => [
                    'class' => 'btn btn-outline-primary'
                ]
            ]
            )
    ;
    }
}
