<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author David
 */
abstract class AbstractExtendedType extends AbstractType
{

    public const OPT_CUSTOM_FIELD_OPTIONS = '_custom_field_options';
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
        $customFieldOptions = $options[self::OPT_CUSTOM_FIELD_OPTIONS] ?? [];

        foreach ($fields as $key => $field) {
            $fieldOptions = $field['options'];

            if (isset($customFieldOptions[$key])) {
                $fieldOptions = array_merge($fieldOptions, $customFieldOptions[$key]);
            }

            if (array_key_exists($key, $data)) {
                $fieldOptions['data'] = $data[$key];
            }

            $builder->add($key, $field['type'], $fieldOptions);
        }
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            self::OPT_CUSTOM_FIELD_OPTIONS => []
        ]);
    }
}
