<?php

namespace App\Form\Type\OpenAI;

use App\Enum\Languages;
use App\Form\Type\AbstractExtendedType;
use App\Form\Type\Core\AudioType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class AudioSpeakToTextType extends AbstractExtendedType
{

    public const FIELD_BUTTON_START = 'button_start';
    public const FIELD_BUTTON_STOP = 'button_stop';
    public const FIELD_INPUT_AUDIO = 'input_audio';
    public const FIELD_FILE = 'file';
    public const FIELD_PROMPT = 'prompt';
    public const FIELD_LANGUAGE = 'language';

    public const FIELD_NAMES = [
        self::FIELD_BUTTON_START,
        self::FIELD_BUTTON_STOP,
        self::FIELD_INPUT_AUDIO,
        self::FIELD_FILE
    ];

    public const LANGUAGES = [
        Languages::LANGUAGE_CHINESE => Languages::LANGCODE_CHINESE,
        Languages::LANGUAGE_ENGLISH => Languages::LANGCODE_ENGLISH,
        Languages::LANGUAGE_FRENCH => Languages::LANGCODE_FRENCH,
        Languages::LANGUAGE_GERMAN => Languages::LANGCODE_GERMAN,
        Languages::LANGUAGE_ITALIAN => Languages::LANGCODE_ITALIAN,
        Languages::LANGUAGE_NORWEGIAN => Languages::LANGCODE_NORWEGIAN,
        Languages::LANGUAGE_PORTUGUESE => Languages::LANGCODE_PORTUGUESE,
        Languages::LANGUAGE_RUSSIAN => Languages::LANGCODE_RUSSIAN,
        Languages::LANGUAGE_SPANISH => Languages::LANGCODE_SPANISH,
        Languages::LANGUAGE_SWEDISH => Languages::LANGCODE_SWEDISH
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return array
     */
    protected function buildFields(FormBuilderInterface $builder, array $options): array
    {
        $fields = [];
        $fields[self::FIELD_BUTTON_START] = [
            'type' => ButtonType::class,
            'options' => [
                'label' => 'Start',
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ],
        ];
        $fields[self::FIELD_BUTTON_STOP] = [
            'type' => ButtonType::class,
            'options' => [
                'label' => 'Stop',
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
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
        $fields[self::FIELD_INPUT_AUDIO] = [
            'type' => AudioType::class,
            'options' => [
                'label' => false,
                'attr' => [
                    'controls' => true,
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ],
        ];

        $fields[self::FIELD_FILE] = [
            'type' => FileType::class,
            'options' => [
                'required' => false,
                'label' => 'File',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'accept' => '.mp3,.mp4,.mpeg,.mpga,.m4a,.wav,.webm',
                ],
                'row_attr' => [
                    'class' => 'mb-3 d-none'
                ],
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
