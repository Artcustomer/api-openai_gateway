<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractExtendedType extends AbstractType
{

    public const FIELD_SAVE = 'save';

    /**
     * Override it.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return array
     */
    protected function buildFields(FormBuilderInterface $builder, array $options): array
    {
        return [];
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $fields = $this->buildFields($builder, $options);
        $data = $options['data'] ?? [];

        foreach ($fields as $key => $field) {
            $fieldOptions = $field['options'];

            if (array_key_exists($key, $data)) {
                $fieldOptions['data'] = $data[$key];
            }

            $builder->add($key, $field['type'], $fieldOptions);
        }
    }
}
