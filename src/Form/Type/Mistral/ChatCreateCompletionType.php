<?php

namespace App\Form\Type\Mistral;

use App\Form\Type\AbstractExtendedType;
use Artcustomer\MistralAIClient\Enum\Model;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class ChatCreateCompletionType extends AbstractExtendedType
{

    public const FIELD_MESSAGE = 'message';
    public const FIELD_MODEL = 'model';
    public const FIELD_MAX_TOKENS = 'max_tokens';
    public const FIELD_TEMPERATURE = 'temperature';
    public const FIELD_TOP_P = 'top_p';
    public const FIELD_N = 'n';
    public const FIELD_PRESENCE_PENALTY = 'presence_penalty';
    public const FIELD_FREQUENCY_PENALTY = 'frequency_penalty';
    public const FIELD_USER = 'user';
    public const FIELD_STREAM = 'stream';
    public const FIELD_STOP = 'stop';
    public const FIELD_RANDOM_SEED = 'random_seed';
    public const FIELD_RESPONSE_FORMAT = 'response_format';
    public const FIELD_SAFE_PROMPT = 'safe_prompt';
    public const FIELD_TOOLS = 'tools';
    public const FIELD_TOOL_CHOICES = 'tool_choices';

    public const FIELD_NAMES = [
        self::FIELD_MESSAGE,
        self::FIELD_MODEL,
        self::FIELD_MAX_TOKENS,
        self::FIELD_TEMPERATURE,
        self::FIELD_TOP_P,
        self::FIELD_N,
        self::FIELD_PRESENCE_PENALTY,
        self::FIELD_FREQUENCY_PENALTY,
        self::FIELD_USER,
        self::FIELD_STREAM,
        self::FIELD_STOP,
        self::FIELD_RANDOM_SEED,
        self::FIELD_RESPONSE_FORMAT,
        self::FIELD_SAFE_PROMPT,
        self::FIELD_TOOLS,
        self::FIELD_TOOL_CHOICES
    ];

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
                    'Mistral Small' => Model::MISTRAL_SMALL,
                    'Mistral Small Latest' => Model::MISTRAL_SMALL_LATEST,
                    'Mistral Medium' => Model::MISTRAL_MEDIUM,
                    'Mistral Medium Latest' => Model::MISTRAL_MEDIUM_LATEST,
                    'Mistral Large Latest' => Model::MISTRAL_LARGE_LATEST
                ],
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