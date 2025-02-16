<?php

namespace App\Twig\Components\Application\OpenAI;

use App\Form\Type\OpenAI\FeatureAudioCompletionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class FeatureAudioCompletion extends AbstractController
{

    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public array $initialFormData = [];

    /**
     * @return FormInterface
     */
    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(FeatureAudioCompletionType::class, $this->initialFormData);
    }

    #[LiveAction]
    public function save()
    {
        $this->submitForm();

        $data = $this->getForm()->getData();
    }
}