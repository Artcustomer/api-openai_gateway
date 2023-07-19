<?php

namespace App\Form\Type;

use App\Form\Type\Core\AudioType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class AudioSpeakToTextType extends AbstractExtendedType
{

    public const FIELD_BUTTON_START = 'button_start';
    public const FIELD_BUTTON_STOP = 'button_stop';
    public const FIELD_INPUT_AUDIO = 'input_audio';
    public const FIELD_INPUT_HIDDEN = 'input_hidden';

    public const FIELD_NAMES = [
        self::FIELD_BUTTON_START,
        self::FIELD_BUTTON_STOP,
        self::FIELD_INPUT_AUDIO,
        self::FIELD_INPUT_HIDDEN
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return array
     */
    protected function buildFields(FormBuilderInterface $builder, array $options): array
    {
        $fields = [];
        $fields[self::FIELD_BUTTON_START] = [
            'type' => ButtonType::class,
            'options' => [
                'label' => 'Start',
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ],
        ];
        $fields[self::FIELD_BUTTON_STOP] = [
            'type' => ButtonType::class,
            'options' => [
                'label' => 'Stop',
                'attr' => [
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ],
        ];
        $fields[self::FIELD_INPUT_AUDIO] = [
            'type' => AudioType::class,
            'options' => [
                'label' => false,
                'attr' => [
                    'controls' => true,
                    'class' => 'form-control mt-1'
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
            ],
        ];
        $fields[self::FIELD_INPUT_HIDDEN] = [
            'type' => HiddenType::class,
            'options' => [
                'required' => false
            ],
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
