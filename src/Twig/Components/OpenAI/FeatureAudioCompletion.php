<?php

namespace App\Twig\Components\OpenAI;

use App\Form\Type\OpenAI\FeatureAudioCompletionType;
use App\Service\OpenAIFeatureService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
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

    #[LiveProp]
    public array $responseData = [];

    #[LiveProp]
    public bool $isResponseSuccess = false;

    #[LiveProp(writable: true)]
    public bool $isLoading = false;

    #[LiveProp(writable: true)]
    public bool $isPrompting = false;

    /**
     * @return FormInterface
     */
    protected function instantiateForm(): FormInterface
    {
        $options = [
            'disabled' => $this->isLoading,
            'attr' => ['id' => FeatureAudioCompletionType::FORM_NAME . '_form']
        ];

        return $this->createForm(FeatureAudioCompletionType::class, $this->initialFormData, $options);
    }

    #[LiveAction]
    public function submit(Request $request, OpenAIFeatureService $openAIFeatureService)
    {
        $files = $request->files;
        $file = null;
        $output = [];
        $messages = [];

        $this->isPrompting = false;
        $this->isLoading = true;
        $this->responseData = [
            'success' => false,
            'data' => [],
            'messages' => []
        ];

        if ($files->count() > 0) {
            $file = $files->get(FeatureAudioCompletionType::FORM_NAME)[FeatureAudioCompletionType::FIELD_FILE] ?? null;
        }

        if ($file instanceof UploadedFile) {
            $output = $openAIFeatureService->completionAudioInAudioOut($file);
        } else {
            $messages[] = 'error.form.audio.not_defined';
        }

        if (!empty($output)) {
            $this->responseData['success'] = $output['success'] ?? false;

            if (!empty($output['data'])) {
                $messages = [
                    $output['data']['userPrompt'] ?? '',
                    $output['data']['transcript'] ?? ''
                ];

                $this->responseData['data'] = [
                    'audio' => $output['data']['audio'] ?? ''
                ];
                $this->isResponseSuccess = true;
            } else if (!empty($output['errors'])) {
                $messages = array_merge($messages, $output['errors']);
            }
        }

        if (!empty($messages)) {
            $this->responseData['messages'] = $messages;
        }

        $this->isLoading = false;
    }

    /**
     * @inheritDoc
     */
    private function getDataModelValue(): ?string
    {
        return 'norender|*';
    }
}
