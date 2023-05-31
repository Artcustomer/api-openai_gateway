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
class TextCreateCompletionType extends AbstractType
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
                    'Ada 001' => 'text-ada-001',
                    'Babbage 001' => 'text-babbage-001',
                    'Curie 001' => 'text-curie-001',
                    'Davinci 002' => 'text-davinci-002',
                    'Davinci 003' => 'text-davinci-003'
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
