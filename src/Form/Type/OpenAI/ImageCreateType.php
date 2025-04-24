<?php

namespace App\Form\Type\OpenAI;

use App\Form\Type\AbstractExtendedType;
use Artcustomer\OpenAIClient\Enum\ImageBackground;
use Artcustomer\OpenAIClient\Enum\ImageQuality;
use Artcustomer\OpenAIClient\Enum\ImageSize;
use Artcustomer\OpenAIClient\Enum\ImageStyle;
use Artcustomer\OpenAIClient\Enum\Model;
use Artcustomer\OpenAIClient\Enum\Moderation;
use Artcustomer\OpenAIClient\Enum\OutputFormat;
use Artcustomer\OpenAIClient\Enum\ResponseFormat;
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
    public const FIELD_QUALITY = 'quality';
    public const FIELD_STYLE = 'style';
    public const FIELD_BACKGROUND = 'background';
    public const FIELD_MODERATION = 'moderation';
    public const FIELD_OUTPUT_COMPRESSION = 'output_compression';
    public const FIELD_OUTPUT_FORMAT = 'output_format';
    public const FIELD_RESPONSE_FORMAT = 'response_format';
    public const FIELD_NUMBER = 'number';

    public const FIELD_NAMES = [
        self::FIELD_PROMPT,
        self::FIELD_MODEL,
        self::FIELD_SIZE,
        self::FIELD_QUALITY,
        self::FIELD_STYLE,
        self::FIELD_BACKGROUND,
        self::FIELD_MODERATION,
        self::FIELD_OUTPUT_COMPRESSION,
        self::FIELD_OUTPUT_FORMAT,
        self::FIELD_RESPONSE_FORMAT,
        self::FIELD_NUMBER
    ];

    public const MODELS = [
        Model::DALL_E_2 => Model::DALL_E_2,
        Model::DALL_E_3 => Model::DALL_E_3,
        Model::GPT_IMAGE_1 => Model::GPT_IMAGE_1
    ];

    public const IMAGE_SIZES = [
        ImageSize::SQUARE_256 => ImageSize::SQUARE_256,
        ImageSize::SQUARE_512 => ImageSize::SQUARE_512,
        ImageSize::SQUARE_1024 => ImageSize::SQUARE_1024,
        ImageSize::PORTRAIT_1024_1536 => ImageSize::PORTRAIT_1024_1536,
        ImageSize::PORTRAIT_1024_1792 => ImageSize::PORTRAIT_1024_1792,
        ImageSize::LANDSCAPE_1536_1024 => ImageSize::LANDSCAPE_1536_1024,
        ImageSize::LANDSCAPE_1792_1024 => ImageSize::LANDSCAPE_1792_1024
    ];

    public const IMAGE_QUALITIES = [
        'Auto' => ImageQuality::AUTO,
        'Standard' => ImageQuality::STANDARD,
        'HD' => ImageQuality::HD,
        'Low' => ImageQuality::LOW,
        'Medium' => ImageQuality::MEDIUM,
        'High' => ImageQuality::HIGH
    ];

    public const IMAGE_STYLES = [
        'Natural' => ImageStyle::NATURAL,
        'Vivid' => ImageStyle::VIVID
    ];

    public const IMAGE_BACKGROUNDS = [
        'Auto' => ImageBackground::AUTO,
        'Opaque' => ImageBackground::OPAQUE,
        'Transparent' => ImageBackground::TRANSPARENT
    ];

    public const IMAGE_MODERATIONS = [
        'Auto' => Moderation::AUTO,
        'Low' => Moderation::LOW
    ];

    public const IMAGE_OUTPUT_FORMATS = [
        'JPEG' => OutputFormat::JPEG,
        'PNG' => OutputFormat::PNG,
        'WEBP' => OutputFormat::WEBP
    ];

    public const IMAGE_RESPONSE_FORMATS = [
        'URL' => ResponseFormat::URL,
        'Base64 Json' => ResponseFormat::B64_JSON
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
                ],
                'data' => Model::GPT_IMAGE_1
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
                ],
                'data' => ImageSize::SQUARE_1024
            ]
        ];
        $fields[self::FIELD_QUALITY] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Quality',
                'choices' => self::IMAGE_QUALITIES,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => ImageQuality::MEDIUM
            ]
        ];
        $fields[self::FIELD_STYLE] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Style',
                'choices' => self::IMAGE_STYLES,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'required' => false
            ]
        ];
        $fields[self::FIELD_BACKGROUND] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Background',
                'choices' => self::IMAGE_BACKGROUNDS,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'required' => false,
                'data' => ImageBackground::AUTO
            ]
        ];
        $fields[self::FIELD_MODERATION] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Moderation',
                'choices' => self::IMAGE_MODERATIONS,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'required' => false,
                'data' => Moderation::AUTO
            ]
        ];
        $fields[self::FIELD_RESPONSE_FORMAT] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Response format',
                'choices' => self::IMAGE_RESPONSE_FORMATS,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'required' => false
            ]
        ];
        $fields[self::FIELD_OUTPUT_FORMAT] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Output format',
                'choices' => self::IMAGE_OUTPUT_FORMATS,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'required' => false,
                'data' => OutputFormat::PNG
            ]
        ];
        $fields[self::FIELD_OUTPUT_COMPRESSION] = [
            'type' => IntegerType::class,
            'options' => [
                'label' => 'Compression level',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                    'pattern' => "\d*"
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ],
                'data' => 100
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
