<?php

namespace App\Form\Type\Gemini;

use App\Form\Type\AbstractExtendedType;
use Artcustomer\GeminiClient\Enum\AspectRatio;
use Artcustomer\GeminiClient\Enum\Model;
use Artcustomer\GeminiClient\Enum\PersonGeneration;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class ImageGenerateType extends AbstractExtendedType
{

    public const FIELD_PROMPT = 'prompt';
    public const FIELD_MODEL = 'model';
    public const FIELD_ASPECT_RATIO = 'aspect_ratio';
    public const FIELD_PERSON_GENERATION = 'person_generation';
    public const FIELD_NUMBER_OF_IMAGES = 'number_of_images';

    public const FIELD_NAMES = [
        self::FIELD_PROMPT,
        self::FIELD_MODEL,
        self::FIELD_ASPECT_RATIO,
        self::FIELD_PERSON_GENERATION,
        self::FIELD_NUMBER_OF_IMAGES
    ];

    public const MODELS = [
        'Imagen 3.0' => Model::IMAGEN_3_0_GENERATE_002,
        'Imagen 4.0' => Model::IMAGEN_4_0_GENERATE_PREVIEW_06_06,
        'Imagen 4.0 Ultra' => Model::IMAGEN_4_0_ULTRA_GENERATE_PREVIEW_06_06,
    ];

    public const ASPECT_RATIOS = [
        AspectRatio::SQUARE_1_1 => AspectRatio::SQUARE_1_1,
        AspectRatio::LANDSCAPE_4_3 => AspectRatio::LANDSCAPE_4_3,
        AspectRatio::PORTRAIT_3_4 => AspectRatio::PORTRAIT_3_4,
        AspectRatio::LANDSCAPE_16_9 => AspectRatio::LANDSCAPE_16_9,
        AspectRatio::PORTRAIT_9_16 => AspectRatio::PORTRAIT_9_16
    ];

    public const PERSON_GENERATIONS = [
        'Allow adult' => PersonGeneration::ALLOW_ADULT,
        'Allow all' => PersonGeneration::ALLOW_ALL,
        'Don\'t allow' => PersonGeneration::DONT_ALLOW
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
                'choices' => self::MODELS,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ]
        ];
        $fields[self::FIELD_ASPECT_RATIO] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Aspect ratio',
                'choices' => self::ASPECT_RATIOS,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ]
        ];
        $fields[self::FIELD_PERSON_GENERATION] = [
            'type' => ChoiceType::class,
            'options' => [
                'label' => 'Person generation',
                'choices' => self::PERSON_GENERATIONS,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ]
        ];
        $fields[self::FIELD_NUMBER_OF_IMAGES] = [
            'type' => IntegerType::class,
            'options' => [
                'label' => 'Number of images',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'min' => 1,
                    'max' => 4,
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
