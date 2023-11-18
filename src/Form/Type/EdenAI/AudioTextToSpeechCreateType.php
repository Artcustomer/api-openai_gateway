<?php

namespace App\Form\Type\EdenAI;

use App\Enum\Languages;
use App\Form\Type\AbstractExtendedType;
use Artcustomer\EdenAIClient\Enum\Option;
use Artcustomer\EdenAIClient\Enum\Provider;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class AudioTextToSpeechCreateType extends AbstractExtendedType
{

    public const FIELD_PROVIDERS = 'providers';
    public const FIELD_FALLBACK_PROVIDERS = 'fallback_providers';
    public const FIELD_RESPONSE_AS_DICT = 'response_as_dict';
    public const FIELD_ATTRIBUTES_AS_LIST = 'attributes_as_list';
    public const FIELD_SHOW_ORIGINAL_RESPONSE = 'show_original_response';
    public const FIELD_SETTINGS = 'settings';
    public const FIELD_TEXT = 'text';
    public const FIELD_OPTION = 'option';
    public const FIELD_LANGUAGE = 'language';
    public const FIELD_RATE = 'rate';
    public const FIELD_PITCH = 'pitch';
    public const FIELD_VOLUME = 'volume';
    public const FIELD_AUDIO_FORMAT = 'audio_format';
    public const FIELD_SAMPLING_RATE = 'sampling_rate';

    public const FIELD_NAMES = [
        self::FIELD_PROVIDERS,
        self::FIELD_FALLBACK_PROVIDERS,
        self::FIELD_RESPONSE_AS_DICT,
        self::FIELD_ATTRIBUTES_AS_LIST,
        self::FIELD_SHOW_ORIGINAL_RESPONSE,
        self::FIELD_SETTINGS,
        self::FIELD_TEXT,
        self::FIELD_OPTION,
        self::FIELD_LANGUAGE,
        self::FIELD_RATE,
        self::FIELD_PITCH,
        self::FIELD_VOLUME,
        self::FIELD_AUDIO_FORMAT,
        self::FIELD_SAMPLING_RATE
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

    public const OPTIONS = [
        'Male' => Option::MALE,
        'Female' => Option::FEMALE
    ];

    protected function buildFields(FormBuilderInterface $builder, array $options): array
    {
        $fields = [];
        $fields[self::FIELD_TEXT] = [
            'type' => TextareaType::class,
            'options' => [
                'label' => 'Text',
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'required' => true
            ]
        ];
        $fields[self::FIELD_PROVIDERS] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Providers',
                'choices' => [
                    'Amazon' => Provider::AMAZON,
                    'ElevenLabs' => Provider::ELEVENLABS,
                    'Google' => Provider::GOOGLE,
                    'IBM' => Provider::IBM,
                    'LovoAI' => Provider::LOVOAI,
                    'Microsoft' => Provider::MICROSOFT
                ],
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'multiple' => true,
                'required' => true
            ]
        ];
        $fields[self::FIELD_LANGUAGE] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Language',
                'choices' => self::LANGUAGES,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'required' => true
            ]
        ];
        $fields[self::FIELD_OPTION] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Language',
                'choices' => self::OPTIONS,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'required' => true
            ]
        ];
        $fields[self::FIELD_RATE] = [
            'type' => IntegerType::class,
            'options' => [
                'label' => 'Rate',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 0
            ]
        ];
        $fields[self::FIELD_PITCH] = [
            'type' => IntegerType::class,
            'options' => [
                'label' => 'Pitch',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 0
            ]
        ];
        $fields[self::FIELD_VOLUME] = [
            'type' => IntegerType::class,
            'options' => [
                'label' => 'Volume',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 0
            ]
        ];
        $fields[self::FIELD_SAMPLING_RATE] = [
            'type' => IntegerType::class,
            'options' => [
                'label' => 'Sampling rate',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => 0,
                    'max' => 10000,
                    'step' => 1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 0
            ]
        ];
        $fields[self::FIELD_AUDIO_FORMAT] = [
            'type' => TextType::class,
            'options' => [
                'label' => 'Audio format',
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => '',
                'empty_data' => '',
                'required' => false
            ]
        ];
        $fields[self::FIELD_RESPONSE_AS_DICT] = [
            'type' => CheckboxType::class,
            'options' => [
                'label' => 'Response as dict',
                'attr' => [
                    'class' => 'form-check-input mt-1'
                ],
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'row_attr' => [
                    'class' => 'form-check mb-3'
                ],
                'data' => true,
                'required' => false
            ]
        ];
        $fields[self::FIELD_ATTRIBUTES_AS_LIST] = [
            'type' => CheckboxType::class,
            'options' => [
                'label' => 'Attributes as list',
                'attr' => [
                    'class' => 'form-check-input mt-1'
                ],
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'row_attr' => [
                    'class' => 'form-check mb-3'
                ],
                'data' => false,
                'required' => false
            ]
        ];
        $fields[self::FIELD_SHOW_ORIGINAL_RESPONSE] = [
            'type' => CheckboxType::class,
            'options' => [
                'label' => 'Show original response',
                'attr' => [
                    'class' => 'form-check-input mt-1'
                ],
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'row_attr' => [
                    'class' => 'form-check mb-3'
                ],
                'data' => false,
                'required' => false
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
