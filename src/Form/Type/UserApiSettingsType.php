<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class UserApiSettingsType extends AbstractExtendedType
{

    public const FIELD_OPENAI_API_TOKEN = 'openai_api_token';
    public const FIELD_EDENAI_API_TOKEN = 'edenai_api_token';
    public const FIELD_ELEVENLABS_API_TOKEN = 'elevenlabs_api_token';
    public const FIELD_MISTRALAI_API_TOKEN = 'mistralai_api_token';
    public const FIELD_XAI_API_TOKEN = 'xai_api_token';

    public const FIELD_NAMES = [
        self::FIELD_OPENAI_API_TOKEN,
        self::FIELD_EDENAI_API_TOKEN,
        self::FIELD_ELEVENLABS_API_TOKEN,
        self::FIELD_MISTRALAI_API_TOKEN,
        self::FIELD_XAI_API_TOKEN
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return array
     */
    protected function buildFields(FormBuilderInterface $builder, array $options): array
    {
        $fields = [];
        $fields[self::FIELD_OPENAI_API_TOKEN] = [
            'type' => PasswordType::class,
            'options' => [
                'label' => 'OpenAI API Token',
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => '',
                'empty_data' => '',
                'required' => false,
                'disabled' => false
            ]
        ];
        $fields[self::FIELD_EDENAI_API_TOKEN] = [
            'type' => PasswordType::class,
            'options' => [
                'label' => 'EdenAI API Token',
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => '',
                'empty_data' => '',
                'required' => false,
                'disabled' => false
            ]
        ];
        $fields[self::FIELD_ELEVENLABS_API_TOKEN] = [
            'type' => PasswordType::class,
            'options' => [
                'label' => 'ElevenLabs API Token',
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => '',
                'empty_data' => '',
                'required' => false,
                'disabled' => false
            ]
        ];
        $fields[self::FIELD_MISTRALAI_API_TOKEN] = [
            'type' => PasswordType::class,
            'options' => [
                'label' => 'MistralAI API Token',
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => '',
                'empty_data' => '',
                'required' => false,
                'disabled' => false
            ]
        ];
        $fields[self::FIELD_XAI_API_TOKEN] = [
            'type' => PasswordType::class,
            'options' => [
                'label' => 'XAI API Token',
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => '',
                'empty_data' => '',
                'required' => false,
                'disabled' => false
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
