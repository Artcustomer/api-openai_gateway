<?php

namespace App\Form\Type\Gemini\Partials;

use App\Form\Type\AbstractExtendedType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author David
 */
class ReferenceImagesType extends AbstractExtendedType
{

    public const NUM_FIELDS = 3;

    public const FIELD_NAME = 'references_images_image_';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return array
     */
    protected function buildFields(FormBuilderInterface $builder, array $options): array
    {
        $fields = [];

        for ($i = 0; $i < self::NUM_FIELDS; ++$i) {
            $fieldName = self::FIELD_NAME . $i;

            $fields[$fieldName] = [
                'type' => FileType::class,
                'options' => [
                    'required' => false,
                    'label' => sprintf('Image file %s', $i + 1),
                    'attr' => [
                        'class' => 'form-control mt-1',
                        'accept' => '.jpg,.jpeg,.png',
                    ],
                    'row_attr' => [
                        'class' => 'mb-3'
                    ],
                ]
            ];
        }

        return $fields;
    }
}
