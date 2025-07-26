<?php

namespace App\Form\Type\Gemini;

use App\Form\Type\AbstractExtendedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class VideoSummarizeYoutubeType extends AbstractExtendedType
{

    public const FIELD_PROMPT = 'prompt';
    public const FIELD_YOUTUBE_URL = 'youtube_url';

    public const FIELD_NAMES = [
        self::FIELD_PROMPT,
        self::FIELD_YOUTUBE_URL
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
                ],
                'data' => 'Please summarize the video in 3 sentences.'
            ]
        ];
        $fields[self::FIELD_YOUTUBE_URL] = [
            'type' => TextType::class,
            'options' => [
                'label' => 'Youtube URL',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'placeholder' => 'https://www.youtube.com/watch?v={id}',
                    'pattern' => '(https:\/\/www.youtube.com\/watch\?v=)([a-zA-Z0-9\-]{10,})',
                ],
                'row_attr' => [
                    'class' => 'mb-3'
                ]
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
