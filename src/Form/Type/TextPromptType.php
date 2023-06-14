<?php

namespace App\Form\Type;

use Artcustomer\OpenAIClient\Enum\Model;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
class TextPromptType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'prompt',
                TextareaType::class,
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
                'model',
                ChoiceType::class,
                [
                    'label' => 'Model',
                    'choices' => [
                        'Ada 001' => Model::TEXT_ADA_001,
                        'Babbage 001' => Model::TEXT_BABBAGE_001,
                        'Curie 001' => Model::TEXT_CURIE_001,
                        'Davinci 002' => Model::TEXT_DAVINCI_002,
                        'Davinci 003' => Model::TEXT_DAVINCI_003
                    ],
                    'attr' => [
                        'class' => 'form-control mt-1'
                    ],
                    'row_attr' => [
                        'class' => 'mb-3'
                    ],
                ]
            )
            ->add(
                'suffix',
                TextType::class,
                [
                    'label' => 'Suffix',
                    'attr' => [
                        'class' => 'form-control mt-1'
                    ],
                    'row_attr' => [
                        'class' => 'mb-3'
                    ],
                    'required' => false
                ]
            )
            ->add(
                'max_tokens',
                IntegerType::class,
                [
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
            )
            ->add(
                'temperature',
                NumberType::class,
                [
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
            )
            ->add(
                'top_p',
                NumberType::class,
                [
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
            )
            ->add(
                'n',
                IntegerType::class,
                [
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
            )
            ->add(
                'echo',
                CheckboxType::class,
                [
                    'label' => 'Echo back',
                    'attr' => [
                        'class' => 'form-check-input mt-1'
                    ],
                    'label_attr' => [
                        'class' => 'form-check-label'
                    ],
                    'row_attr' => [
                        'class' => 'form-check mb-3'
                    ],
                    'required' => false
                ]
            )
            ->add(
                'presence_penalty',
                NumberType::class,
                [
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
            )
            ->add(
                'frequency_penalty',
                NumberType::class,
                [
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
            )
            ->add(
                'best_of',
                IntegerType::class,
                [
                    'label' => 'Best of completions',
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
            )
            ->add(
                'user',
                TextType::class,
                [
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
