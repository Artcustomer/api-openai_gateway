<?php

namespace App\Form\Type;

use Artcustomer\OpenAIClient\Enum\ImageSize;
use Artcustomer\OpenAIClient\Enum\Model;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class ImageCreateType extends AbstractExtendedType
{

    public const FIELD_PROMPT = 'prompt';
    public const FIELD_MODEL = 'model';
    public const FIELD_SIZE = 'size';
    public const FIELD_NUMBER = 'number';

    public const FIELD_NAMES = [
        self::FIELD_PROMPT,
        self::FIELD_MODEL,
        self::FIELD_SIZE,
        self::FIELD_NUMBER
    ];

    public const MODELS = [
        Model::DALL_E_2 => Model::DALL_E_2,
        Model::DALL_E_3 => Model::DALL_E_3
    ];

    public const IMAGE_SIZES = [
        ImageSize::SQUARE_256 => ImageSize::SQUARE_256,
        ImageSize::SQUARE_512 => ImageSize::SQUARE_512,
        ImageSize::SQUARE_1024 => ImageSize::SQUARE_1024,
        ImageSize::PORTRAIT_1024 => ImageSize::PORTRAIT_1024,
        ImageSize::LANDSCAPE_1024 => ImageSize::LANDSCAPE_1024
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
            'type' => TextType::class,
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
                'choices' => self::MODELS,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ]
        ];
        $fields[self::FIELD_SIZE] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Size',
                'choices' => self::IMAGE_SIZES,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ]
        ];
        $fields[self::FIELD_NUMBER] = [
            'type' => IntegerType::class,
            'options' => [
                'label' => 'Number of images',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 1
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
