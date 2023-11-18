<?php

namespace App\Form\Type;

use App\Enum\Languages;
use Artcustomer\OpenAIClient\Enum\Model;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class AudioCreateTranscriptionType extends AbstractExtendedType
{

    public const FIELD_FILE = 'file';
    public const FIELD_MODEL = 'model';
    public const FIELD_PROMPT = 'prompt';
    public const FIELD_TEMPERATURE = 'temperature';
    public const FIELD_LANGUAGE = 'language';

    public const FIELD_NAMES = [
        self::FIELD_FILE,
        self::FIELD_MODEL,
        self::FIELD_PROMPT,
        self::FIELD_TEMPERATURE,
        self::FIELD_LANGUAGE
    ];

    public const LANGUAGES = [
        Languages::LANGUAGE_ENGLISH => Languages::LANGCODE_ENGLISH,
        Languages::LANGUAGE_FRENCH => Languages::LANGCODE_FRENCH,
        Languages::LANGUAGE_GERMAN => Languages::LANGCODE_GERMAN,
        Languages::LANGUAGE_ITALIAN => Languages::LANGCODE_ITALIAN,
        Languages::LANGUAGE_SPANISH => Languages::LANGCODE_SPANISH,
        Languages::LANGUAGE_PORTUGUESE => Languages::LANGCODE_PORTUGUESE,
        Languages::LANGUAGE_RUSSIAN => Languages::LANGCODE_RUSSIAN,
        Languages::LANGUAGE_CHINESE => Languages::LANGCODE_CHINESE
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return array
     */
    protected function buildFields(FormBuilderInterface $builder, array $options): array
    {
        $fields = [];
        $fields[self::FIELD_FILE] = [
            'type' => FileType::class,
            'options' => [
                'required' => true,
                'label' => 'File',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'accept' => '.mp3,.mp4,.mpeg,.mpga,.m4a,.wav,.webm',
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
            ]
        ];
        $fields[self::FIELD_MODEL] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Model',
                'required' => true,
                'choices' => [
                    'Whisper 1' => Model::WHISPER_1
                ],
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
            ],
        ];
        $fields[self::FIELD_PROMPT] = [
            'type' => TextType::class,
            'options' => [
                'label' => 'Prompt',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ],
        ];
        $fields[self::FIELD_TEMPERATURE] = [
            'type' => NumberType::class,
            'options' => [
                'label' => 'Sampling temperature',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 1
            ]
        ];
        $fields[self::FIELD_LANGUAGE] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Language',
                'required' => false,
                'choices' => self::LANGUAGES,
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
