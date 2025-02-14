<?php

namespace App\Form\Type\OpenAI;

use App\Form\Type\AbstractExtendedType;
use Artcustomer\OpenAIClient\Enum\AudioFormat;
use Artcustomer\OpenAIClient\Enum\AudioVoice;
use Artcustomer\OpenAIClient\Enum\Model;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class AudioGenerateAudioType extends AbstractExtendedType
{

    public const FIELD_MODEL = 'model';
    public const FIELD_VOICE = 'voice';
    public const FIELD_TEXT = 'text';
    public const FIELD_RESPONSE_FORMAT = 'response_format';

    public const FIELD_NAMES = [
        self::FIELD_MODEL,
        self::FIELD_VOICE,
        self::FIELD_TEXT,
        self::FIELD_RESPONSE_FORMAT
    ];

    public const MODELS = [
        'GPT 4o Audio Preview' => Model::GPT_4_O_AUDIO_PREVIEW
    ];

    public const VOICES = [
        'Alloy' => AudioVoice::ALLOW,
        'Ash' => AudioVoice::ASH,
        'Coral' => AudioVoice::CORAL,
        'Echo' => AudioVoice::ECHO,
        'Fable' => AudioVoice::FABLE,
        'Onyx' => AudioVoice::ONYX,
        'Nova' => AudioVoice::NOVA,
        'Sage' => AudioVoice::SAGE,
        'Shimmer' => AudioVoice::SHIMMER
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
