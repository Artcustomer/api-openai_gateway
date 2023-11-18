<?php

namespace App\Form\Type;

use App\Enum\Languages;
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

    public const FIELD_NAMES = [
        self::FIELD_FROM_LANGUAGE,
        self::FIELD_TO_LANGUAGE,
        self::FIELD_PROMPT
    ];

    public const LANGUAGES = [
        Languages::LANGUAGE_ENGLISH => 'english',
        Languages::LANGUAGE_FRENCH => 'french',
        Languages::LANGUAGE_GERMAN => 'german',
        Languages::LANGUAGE_ITALIAN => 'italian',
        Languages::LANGUAGE_SPANISH => 'spanish',
        Languages::LANGUAGE_PORTUGUESE => 'portuguese',
        Languages::LANGUAGE_RUSSIAN => 'russian',
        Languages::LANGUAGE_CHINESE => 'chinese'
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
