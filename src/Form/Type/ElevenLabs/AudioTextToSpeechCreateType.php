<?php

namespace App\Form\Type\ElevenLabs;

use App\Form\Type\AbstractExtendedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class AudioTextToSpeechCreateType extends AbstractExtendedType
{

    public const FIELD_VOICE_ID = 'voice_id';
    public const FIELD_MODEL_ID = 'model_id';
    public const FIELD_TEXT = 'text';
    public const FIELD_VOICE_SETTINGS = 'voice_settings';
    public const FIELD_VOICE_SETTINGS_SIMILIRATY_BOOST = 'similarity_boost';
    public const FIELD_VOICE_SETTINGS_STABILITY = 'stability';
    public const FIELD_VOICE_SETTINGS_STYLE = 'style';
    public const FIELD_VOICE_SETTINGS_USE_SPEAKER_BOOST = 'use_speaker_boost';

    public const FIELD_OPTIMIZE_STREAMING_LATENCY = 'optimize_streaming_latency';
    public const FIELD_OUTPUT_FORMAT = 'output_format';

    public const FIELD_NAMES = [
        self::FIELD_VOICE_ID,
        self::FIELD_MODEL_ID,
        self::FIELD_TEXT,
        self::FIELD_VOICE_SETTINGS_SIMILIRATY_BOOST,
        self::FIELD_VOICE_SETTINGS_STABILITY,
        self::FIELD_VOICE_SETTINGS_STYLE,
        self::FIELD_VOICE_SETTINGS_USE_SPEAKER_BOOST
    ];

    public const VOICES = [
        'Snoop Dogg' => 'sxoRkbNuv2hOICiuyAvh',
        'Elon Musk' => 'KNkfKg9dv06jOeHALUu8'
    ];

    public const MODELS = [
        'Eleven English v1' => 'eleven_monolingual_v1',
        'Eleven Multilingual v1' => 'eleven_multilingual_v1',
        'Eleven Multilingual v2' => 'eleven_multilingual_v2',
        'Eleven Turbo v2' => 'eleven_turbo_v2',
        'Eleven English v2' => 'eleven_english_sts_v2',
    ];

    public const OUTPUT_FORMATS = [
        'mp3_44100_64',
        'mp3_44100_96',
        'mp3_44100_128 ',
        'mp3_44100_192 ',
        'pcm_16000',
        'pcm_22050',
        'pcm_24000',
        'pcm_44100',
        'ulaw_8000'
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return array
     */
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
        $fields[self::FIELD_VOICE_ID] = [
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
        $fields[self::FIELD_MODEL_ID] = [
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
        $fields[self::FIELD_VOICE_SETTINGS_SIMILIRATY_BOOST] = [
            'type' => NumberType::class,
            'options' => [
                'label' => 'Similarity boost',
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
                'data' => 1,
                'required' => true
            ]
        ];
        $fields[self::FIELD_VOICE_SETTINGS_STABILITY] = [
            'type' => NumberType::class,
            'options' => [
                'label' => 'Stability',
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
                'data' => 1,
                'required' => true
            ]
        ];
        $fields[self::FIELD_VOICE_SETTINGS_STYLE] = [
            'type' => NumberType::class,
            'options' => [
                'label' => 'Style',
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
                'data' => 0,
                'required' => false
            ]
        ];
        $fields[self::FIELD_VOICE_SETTINGS_USE_SPEAKER_BOOST] = [
            'type' => CheckboxType::class,
            'options' => [
                'label' => 'Use speaker boost',
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
