<?php

namespace App\Form\Type\Mistral;

use App\Form\Type\AbstractExtendedType;
use Artcustomer\MistralAIClient\Enum\Model;
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
    public const FIELD_MESSAGE = 'message';
    public const FIELD_IMAGE_URL = 'image_url';
    public const FIELD_IMAGE = 'image';

    public const FIELD_NAMES = [
        self::FIELD_MODEL,
        self::FIELD_MAX_TOKENS,
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
                    'Pixtral 12B' => Model::PIXTRAL_12_B,
                    'Pixtral 12B 2409' => Model::PIXTRAL_12_B_2409,
                    'Pixtral 12B Latest' => Model::PIXTRAL_12_B_LATEST,
                    'Pixtral Large 2411' => Model::PIXTRAL_LARGE_2411,
                    'Pixtral Large Latest' => Model::PIXTRAL_LARGE_LATEST
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