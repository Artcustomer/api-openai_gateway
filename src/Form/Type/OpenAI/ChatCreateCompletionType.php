<?php

namespace App\Form\Type\OpenAI;

use App\Form\Type\AbstractExtendedType;
use Artcustomer\OpenAIClient\Enum\Model;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class ChatCreateCompletionType extends AbstractExtendedType
{

    public const FIELD_PROMPT = 'prompt';
    public const FIELD_MODEL = 'model';
    public const FIELD_MAX_TOKENS = 'max_tokens';
    public const FIELD_TEMPERATURE = 'temperature';
    public const FIELD_TOP_P = 'top_p';
    public const FIELD_N = 'n';
    public const FIELD_PRESENCE_PENALTY = 'presence_penalty';
    public const FIELD_FREQUENCY_PENALTY = 'frequency_penalty';
    public const FIELD_USER = 'user';

    public const FIELD_NAMES = [
        self::FIELD_PROMPT,
        self::FIELD_MODEL,
        self::FIELD_MAX_TOKENS,
        self::FIELD_TEMPERATURE,
        self::FIELD_TOP_P,
        self::FIELD_N,
        self::FIELD_PRESENCE_PENALTY,
        self::FIELD_FREQUENCY_PENALTY,
        self::FIELD_USER
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
                    'GPT-3.5 Turbo' => Model::GPT_3_5_TURBO,
                    'GPT-3.5 Turbo 0613' => Model::GPT_3_5_TURBO_0613,
                    'GPT-3.5 Turbo 16K' => Model::GPT_3_5_TURBO_16K,
                    'GPT-3.5 Turbo 16K 0613' => Model::GPT_3_5_TURBO_16K_0613,
                    'GPT-4' => Model::GPT_4,
                    'GPT-4 0613' => Model::GPT_4_0613,
                    'GPT-4 32k' => Model::GPT_4_32K,
                    'GPT-4 32k 0613' => Model::GPT_4_32K_0613,
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
        $fields[self::FIELD_N] = [
            'type' => IntegerType::class,
            'options' => [
                'label' => 'Number of completions',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 1
            ]
        ];
        $fields[self::FIELD_MAX_TOKENS] = [
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
                'data' => 256
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
        $fields[self::FIELD_USER] = [
            'type' => TextType::class,
            'options' => [
                'label' => 'User',
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
