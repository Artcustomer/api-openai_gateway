<?php

namespace App\Form\Type\Core;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AudioType extends BaseType implements DataTransformerInterface
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
            $form = $event->getForm();
            $requestHandler = $form->getConfig()->getRequestHandler();

            dump($event);
            dump($requestHandler);
            exit;
        });
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => File::class,
            'auto_initialize' => false,
        ]);

        $resolver->setDefault('auto_initialize', false);
    }

    /**
     * @return string|null
     */
    public function getParent(): ?string
    {
        return FileType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'audio';
    }

    public function transform(mixed $data): mixed
    {
        // Model data should not be transformed
        dump('transform');
        dump($this);
        exit;
        return $data;
    }

    public function reverseTransform(mixed $data): mixed
    {
        dump('reverseTransform');
        dump($this);
        exit;
        return $data ?? '';
    }
}
