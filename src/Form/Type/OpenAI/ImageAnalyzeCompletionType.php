<?php

namespace App\Form\Type\OpenAI;

use App\Form\Type\AbstractExtendedType;
use Artcustomer\OpenAIClient\Enum\Model;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class ImageAnalyzeCompletionType extends AbstractExtendedType
{

    public const FIELD_MODEL = 'model';
    public const FIELD_MAX_TOKENS = 'max_tokens';
    public const FIELD_DETAIL = 'detail';
    public const FIELD_MESSAGE = 'message';
    public const FIELD_IMAGE_URL = 'image_url';
    public const FIELD_IMAGE = 'image';

    public const FIELD_NAMES = [
        self::FIELD_MODEL,
        self::FIELD_MAX_TOKENS,
        self::FIELD_DETAIL,
        self::FIELD_MESSAGE,
        self::FIELD_IMAGE_URL,
        self::FIELD_IMAGE
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
                'choices' => [
                    'GPT 4o Mini' => Model::GPT_4O_MINI
                ],
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
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
        $fields[self::FIELD_DETAIL] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Detail',
                'choices' => [
                    'Auto' => 'auto',
                    'Low' => 'low',
                    'High' => 'high',
                ],
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 'auto'
            ]
        ];
        $fields[self::FIELD_MESSAGE] = [
            'type' => TextareaType::class,
            'options' => [
                'label' => 'Message',
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 'What is in this image?'
            ]
        ];
        $fields[self::FIELD_IMAGE_URL] = [
            'type' => UrlType::class,
            'options' => [
                'required' => false,
                'label' => 'Image URL',
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'default_protocol' => 'https'
            ]
        ];
        $fields[self::FIELD_IMAGE] = [
            'type' => FileType::class,
            'options' => [
                'required' => false,
                'label' => 'Image file',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'accept' => '.jpg,.jpeg,.png',
                ],
                'row_attr' => [
                    'class' => 'mb-3'
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