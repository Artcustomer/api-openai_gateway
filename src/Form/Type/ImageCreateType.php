<?php

namespace App\Form\Type;

use Artcustomer\OpenAIClient\Enum\ImageSize;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class ImageCreateType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $imageSizes = [
            ImageSize::SQUARE_256 => ImageSize::SQUARE_256,
            ImageSize::SQUARE_512 => ImageSize::SQUARE_512,
            ImageSize::SQUARE_1024 => ImageSize::SQUARE_1024
        ];

        $builder
            ->add(
                'prompt',
                TextType::class,
                [
                    'label' => 'Prompt',
                    'attr' => [
                        'class' => 'form-control mt-1'
                    ],
                    'row_attr' => [
                        'class' => 'mb-3'
                    ]
                ]
            )
            ->add(
                'size',
                ChoiceType::class,
                [
                    'label' => 'Size',
                    'choices' => $imageSizes,
                    'attr' => [
                        'class' => 'form-control mt-1'
                    ],
                    'row_attr' => [
                        'class' => 'mb-3'
                    ],
                    'data' => 'english'
                ]
            )
            ->add(
                'number',
                IntegerType::class,
                [
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
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Submit',
                    'attr' => [
                        'class' => 'btn btn-outline-primary'
                    ]
                ]
            );
    }
}
