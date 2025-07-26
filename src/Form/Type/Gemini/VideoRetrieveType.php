<?php

namespace App\Form\Type\Gemini;

use App\Form\Type\AbstractExtendedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class VideoRetrieveType extends AbstractExtendedType
{

    public const FIELD_OPERATION_NAME = 'prompt';

    public const FIELD_NAMES = [
        self::FIELD_OPERATION_NAME,
    ];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return array
     */
    protected function buildFields(FormBuilderInterface $builder, array $options): array
    {
        $fields = [];
        $fields[self::FIELD_OPERATION_NAME] = [
            'type' => TextType::class,
            'options' => [
                'label' => 'Operation name',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'placeholder' => 'models/{modelName}/operations/{id}',
                    'pattern' => '(models\/)([a-z0-9\-\.]*)(\/operations\/)([a-z0-9]{12})',
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
