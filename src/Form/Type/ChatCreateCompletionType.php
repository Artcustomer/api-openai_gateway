<?php

namespace App\Form\Type;

use Artcustomer\OpenAIClient\Enum\Model;
use Symfony\Component\Form\AbstractType;
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
class ChatCreateCompletionType extends AbstractType
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
                        'GPT-3.5 Turbo' => Model::GPT_3_5_TURBO,
                        'GPT-3.5 Turbo 0301' => Model::GPT_3_5_TURBO_0301,
                        'GPT-4' => Model::GPT_4,
                        'GPT-4 0314' => Model::GPT_4_0314,
                        'GPT-4 32k' => Model::GPT_4_32K,
                        'GPT-4 32k 0314' => Model::GPT_4_32K_0314,
                    ],
                    'attr' => [
                        'class' => 'form-control mt-1'
                    ],
                    'row_attr' => [
                        'class' => 'mb-3'
                    ]
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
