<?php

namespace App\Form\Type\XAI;

use App\Form\Type\AbstractExtendedType;
use Artcustomer\XAIClient\Enum\Model;
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
class ChatCompletionCreateType extends AbstractExtendedType
{

    public const FIELD_MESSAGE = 'message';
    public const FIELD_MODEL = 'model';
    public const FIELD_FREQUENCY_PENALTY = 'frequency_penalty';
    public const FIELD_LOGIT_BIAS = 'logit_bias';
    public const FIELD_LOGPROBS = 'logprobs';
    public const FIELD_MAX_TOKENS = 'max_tokens';
    public const FIELD_N = 'n';
    public const FIELD_PRESENCE_PENALTY = 'presence_penalty';
    public const FIELD_RESPONSE_FORMAT = 'response_format';
    public const FIELD_SEED = 'seed';
    public const FIELD_STOP = 'stop';
    public const FIELD_STREAM = 'stream';
    public const FIELD_STREAM_OPTIONS = 'stream_options';
    public const FIELD_TEMPERATURE = 'temperature';
    public const FIELD_TOOL_CHOICE = 'tool_choice';
    public const FIELD_TOOLS = 'tools';
    public const FIELD_TOP_LOGPROBS = 'top_logprobs';
    public const FIELD_TOP_P = 'top_p';
    public const FIELD_USER = 'user';

    public const FIELD_NAMES = [
        self::FIELD_MESSAGE,
        self::FIELD_MODEL,
        self::FIELD_FREQUENCY_PENALTY,
        self::FIELD_MAX_TOKENS,
        self::FIELD_N,
        self::FIELD_PRESENCE_PENALTY,
        self::FIELD_TEMPERATURE,
        self::FIELD_TOP_P,
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
        $fields[self::FIELD_MESSAGE] = [
            'type' => TextareaType::class,
            'options' => [
                'label' => 'Message',
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
                    'Grok Beta' => Model::GROK_BETA,
                    'Grok Vision Beta' => Model::GROK_VISION_BETA,
                    'Grok 2 Vision 1212' => Model::GROK_2_VISION_1212,
                    'Grok 2 1212' => Model::GROK_2_1212,
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
                'data' => 0
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
                'data' => 0
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
