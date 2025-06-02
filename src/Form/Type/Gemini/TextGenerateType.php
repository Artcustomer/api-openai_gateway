<?php

namespace App\Form\Type\Gemini;

use App\Form\Type\AbstractExtendedType;
use Artcustomer\GeminiClient\Enum\Model;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class TextGenerateType extends AbstractExtendedType
{

    public const FIELD_PROMPT = 'prompt';
    public const FIELD_MODEL = 'model';
    public const FIELD_MAX_OUTPUT_TOKENS = 'max_output_tokens';
    public const FIELD_TEMPERATURE = 'temperature';
    public const FIELD_TOP_P = 'top_p';
    public const FIELD_TOP_K = 'top_k';
    public const FIELD_SEED = 'seed';
    public const FIELD_PRESENCE_PENALTY = 'presence_penalty';
    public const FIELD_FREQUENCY_PENALTY = 'frequency_penalty';

    public const FIELD_NAMES = [
        self::FIELD_PROMPT,
        self::FIELD_MODEL,
        self::FIELD_MAX_OUTPUT_TOKENS,
        self::FIELD_TEMPERATURE,
        self::FIELD_TOP_P,
        self::FIELD_TOP_K,
        self::FIELD_SEED,
        self::FIELD_PRESENCE_PENALTY,
        self::FIELD_FREQUENCY_PENALTY
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return array
     */
    protected function buildFields(FormBuilderInterface $builder, array $options): array
    {
        $fields = [];
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
        $fields[self::FIELD_MODEL] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Model',
                'choices' => [
                    'Gemini 2.0 Flash' => Model::GEMINI_2_0_FLASH
                ],
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ]
        ];
        $fields[self::FIELD_TEMPERATURE] = [
            'type' => NumberType::class,
            'options' => [
                'label' => 'Sampling temperature',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => 0,
                    'max' => 2,
                    'step' => 0.1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 1
            ]
        ];
        $fields[self::FIELD_TOP_P] = [
            'type' => NumberType::class,
            'options' => [
                'label' => 'Nucleus sampling',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => 0,
                    'max' => 2,
                    'step' => 0.1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 1
            ]
        ];
        $fields[self::FIELD_TOP_K] = [
            'type' => NumberType::class,
            'options' => [
                'label' => 'Most probable tokens with nucleus sampling',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => 0,
                    'max' => 2048,
                    'step' => 1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 10
            ]
        ];
        $fields[self::FIELD_SEED] = [
            'type' => IntegerType::class,
            'options' => [
                'label' => 'Seed used in decoding',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => 1,
                    'max' => 4096,
                    'step' => 1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => rand(1, 4096)
            ]
        ];
        $fields[self::FIELD_MAX_OUTPUT_TOKENS] = [
            'type' => IntegerType::class,
            'options' => [
                'label' => 'Maximum number of tokens',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => 1,
                    'max' => 1024,
                    'step' => 1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 800
            ]
        ];
        $fields[self::FIELD_PRESENCE_PENALTY] = [
            'type' => NumberType::class,
            'options' => [
                'label' => 'Presence penalty',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => -2,
                    'max' => 2,
                    'step' => 0.1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 0
            ]
        ];
        $fields[self::FIELD_FREQUENCY_PENALTY] = [
            'type' => NumberType::class,
            'options' => [
                'label' => 'Frequency penalty',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => -2,
                    'max' => 2,
                    'step' => 0.1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 0
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
