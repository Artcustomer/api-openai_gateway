<?php

namespace App\Form\Type;

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
class AudioCreateTranslationType extends AbstractExtendedType
{

    public const FIELD_FILE = 'file';
    public const FIELD_MODEL = 'model';
    public const FIELD_PROMPT = 'prompt';
    public const FIELD_TEMPERATURE = 'temperature';
    public const FIELD_LANGUAGE = 'language';

    public const LANGUAGE_ENGLISH = 'English';
    public const LANGUAGE_FRENCH = 'French';
    public const LANGUAGE_GERMAN = 'German';
    public const LANGUAGE_ITALIAN = 'Italian';
    public const LANGUAGE_SPANISH = 'Spanish';
    public const LANGUAGE_PORTUGUESE = 'Portuguese';
    public const LANGUAGE_RUSSIAN = 'Russian';
    public const LANGUAGE_CHINESE = 'Chinese';

    public const FIELD_NAMES = [
        self::FIELD_FILE,
        self::FIELD_MODEL,
        self::FIELD_PROMPT,
        self::FIELD_TEMPERATURE,
        self::FIELD_LANGUAGE
    ];

    public const LANGUAGES = [
        self::LANGUAGE_ENGLISH => 'en',
        self::LANGUAGE_FRENCH => 'fr',
        self::LANGUAGE_GERMAN => 'de',
        self::LANGUAGE_ITALIAN => 'it',
        self::LANGUAGE_SPANISH => 'es',
        self::LANGUAGE_PORTUGUESE => 'pt',
        self::LANGUAGE_RUSSIAN => 'ru',
        self::LANGUAGE_CHINESE => 'zh'
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