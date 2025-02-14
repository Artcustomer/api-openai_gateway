<?php

namespace App\Form\Type\OpenAI;

use App\Form\Type\AbstractExtendedType;
use Artcustomer\OpenAIClient\Enum\AudioFormat;
use Artcustomer\OpenAIClient\Enum\Model;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class AudioTextToSpeechType extends AbstractExtendedType
{

    public const FIELD_MODEL = 'model';
    public const FIELD_VOICE = 'voice';
    public const FIELD_INPUT = 'input';
    public const FIELD_RESPONSE_FORMAT = 'response_format';
    public const FIELD_SPEED = 'speed';

    public const FIELD_NAMES = [
        self::FIELD_MODEL,
        self::FIELD_VOICE,
        self::FIELD_INPUT,
        self::FIELD_RESPONSE_FORMAT,
        self::FIELD_SPEED
    ];

    public const MODELS = [
        'TTS 1' => Model::TTS_1,
        'TTS 1 HD' => Model::TTS_1_HD
    ];

    public const VOICES = [
        'Alloy' => 'alloy',
        'Ash' => 'ash',
        'Coral' => 'coral',
        'Echo' => 'echo',
        'Fable' => 'fable',
        'Onyx' => 'onyx',
        'Nova' => 'nova',
        'Sage' => 'sage',
        'Shimmer' => 'shimmer',
    ];

    public const RESPONSE_FORMATS = [
        'AAC' => AudioFormat::AAC,
        'FLAC' => AudioFormat::FLAC,
        'MP3' => AudioFormat::MP3,
        'OPUS' => AudioFormat::OPUS,
        'PCM' => AudioFormat::PCM,
        'WAV' => AudioFormat::WAV
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return array
     */
    protected function buildFields(FormBuilderInterface $builder, array $options): array
    {
        $fields = [];
        $fields[self::FIELD_MODEL] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Model',
                'choices' => self::MODELS,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'multiple' => false,
                'required' => true
            ]
        ];
        $fields[self::FIELD_VOICE] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Voice',
                'choices' => self::VOICES,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'multiple' => false,
                'required' => true
            ]
        ];
        $fields[self::FIELD_RESPONSE_FORMAT] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Response format',
                'choices' => self::RESPONSE_FORMATS,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'multiple' => false,
                'required' => true,
                'data' => AudioFormat::MP3,
            ]
        ];
        $fields[self::FIELD_SPEED] = [
            'type' => NumberType::class,
            'options' => [
                'label' => 'Speed',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => 0.25,
                    'max' => 4,
                    'step' => 0.01,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 1,
                'required' => false
            ]
        ];
        $fields[self::FIELD_INPUT] = [
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
