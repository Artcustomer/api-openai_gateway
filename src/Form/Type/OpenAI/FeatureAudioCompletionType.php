<?php

namespace App\Form\Type\OpenAI;

use App\Form\Type\AbstractExtendedType;
use App\Form\Type\Core\AudioType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class FeatureAudioCompletionType extends AbstractExtendedType
{

    public const FIELD_BUTTON_RECORD = 'button_record';
    public const FIELD_INPUT_AUDIO = 'input_audio';
    public const FIELD_FILE = 'file';

    public const FIELD_NAMES = [
        self::FIELD_BUTTON_RECORD,
        self::FIELD_INPUT_AUDIO,
        self::FIELD_FILE
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return array
     */
    protected function buildFields(FormBuilderInterface $builder, array $options): array
    {
        $fields = [];
        $fields[self::FIELD_BUTTON_RECORD] = [
            'type' => ButtonType::class,
            'options' => [
                'label_html' => true,
                'label' => '<i class="bx bxs-microphone"></i>',
                'attr' => [
                    'class' => 'btn btn-outline-primary'
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
        $fields[self::FIELD_FILE] = [
            'type' => FileType::class,
            'options' => [
                'required' => false,
                'label' => 'File',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'accept' => '.mp3,.mp4,.mpeg,.mpga,.m4a,.wav,.webm',
                ],
                'row_attr' => [
                    'class' => 'mb-3 d-none'
                ],
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
