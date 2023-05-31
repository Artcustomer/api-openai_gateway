<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class ChatCreateCompletionType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add(
            'model',
            ChoiceType::class,
            [
                'label' => false,
                'choices' => [
                    'GPT-3.5 Turbo' => 'gpt-3.5-turbo',
                    'GPT-3.5 Turbo 0301' => 'gpt-3.5-turbo-0301',
                    'GPT-4' => 'gpt-4',
                    'GPT-4 0314' => 'gpt-4-0314',
                    'GPT-4 32k' => 'gpt-4-32k',
                    'GPT-4 32k 0314' => 'gpt-4-32k-0314'
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
            'prompt',
            TextareaType::class,
            [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
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
            )
    ;
    }
}
