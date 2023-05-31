<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class ImageCreateType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'prompt',
                TextType::class,
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
