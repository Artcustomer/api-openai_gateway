<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class TextTranslateType extends AbstractExtendedType
{

    public const FIELD_FROM_LANGUAGE = 'from_language';
    public const FIELD_TO_LANGUAGE = 'to_language';
    public const FIELD_PROMPT = 'prompt';

    public const LANGUAGE_ENGLISH = 'English';
    public const LANGUAGE_FRENCH = 'French';
    public const LANGUAGE_GERMAN = 'German';
    public const LANGUAGE_ITALIAN = 'Italian';
    public const LANGUAGE_SPANISH = 'Spanish';

    public const FIELD_NAMES = [
        self::FIELD_FROM_LANGUAGE,
        self::FIELD_TO_LANGUAGE,
        self::FIELD_PROMPT
    ];

    public const LANGUAGES = [
        self::LANGUAGE_ENGLISH => 'english',
        self::LANGUAGE_FRENCH => 'french',
        self::LANGUAGE_GERMAN => 'german',
        self::LANGUAGE_ITALIAN => 'italian',
        self::LANGUAGE_SPANISH => 'spanish'
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return array
     */
    protected function buildFields(FormBuilderInterface $builder, array $options): array
    {
        $fields = [];
        $fields[self::FIELD_FROM_LANGUAGE] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'From language',
                'choices' => self::LANGUAGES,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ]
        ];
        $fields[self::FIELD_TO_LANGUAGE] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'To language',
                'choices' => self::LANGUAGES,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ]
        ];
        $fields[self::FIELD_PROMPT] = [
            'type' => TextareaType::class,
            'options' => [
                'label' => 'Prompt',
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ]
        ];
        $fields[self::FIELD_SAVE] = [
            'type' => SubmitType::class,
            'options' => [
                'label' => 'Submit',
                'attr' => [
                    'class' => 'btn btn-outline-primary'
                ]
            ]
        ];

        return $fields;
    }
}
