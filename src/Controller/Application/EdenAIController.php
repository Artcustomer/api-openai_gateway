<?php

namespace App\Controller\Application;

use App\Form\Type\EdenAI\AudioTextToSpeechCreateType;
use App\Service\EdenAIService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @Route("/edenai")
 *
 * @author David
 */
class EdenAIController extends AbstractApplicationController
{

    protected EdenAIService $edenAIService;

    /**
     * Constructor
     *
     * @param EdenAIService $edenAIService
     */
    public function __construct(EdenAIService $edenAIService)
    {
        $this->edenAIService = $edenAIService;
    }

    /**
     * @Route("/text-to-speech", name="application_edenai_textospeech", methods={"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function textToSpeech(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, AudioTextToSpeechCreateType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(AudioTextToSpeechCreateType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $outputResponse = '';
        $output = [];
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data['text'];
            $params = [
                'providers' => implode(',', $data['providers']),
                'response_as_dict' => $data['response_as_dict'],
                'attributes_as_list' => $data['attributes_as_list'],
                'show_original_response' => $data['show_original_response'],
                'rate' => $data['rate'],
                'pitch' => $data['pitch'],
                'volume' => $data['volume'],
                'sampling_rate' => $data['sampling_rate'] ?? 0,
                'text' => $data['text'],
                'language' => $data['language'],
                'option' => $data['option'],
            ];
            $response = $this->edenAIService->getApiGateway()->getAudioConnector()->textToSpeech($params);

            if ($response->getStatusCode() === 200) {
                $outputResponse = (string)$response->getContent();
                $content = json_decode($outputResponse);
                $providers = $data['providers'];

                foreach ($providers as $provider) {
                    $providerData = $content->{$provider} ?? NULL;

                    if ($providerData) {
                        $output[$provider] = [
                            'status' => $providerData->status,
                            'url' => $providerData->audio_resource_url ?? ''
                        ];
                    }
                }
            } else {
                $errorMessage = $response->getMessage();

                if (empty($errorMessage)) {
                    $content = $response->getContent();

                    if (!empty($content)) {
                        $content = json_decode((string)$content);
                        $errorMessage = $content->error->message ?? '';
                    }
                }

                $errorMessage = !empty($errorMessage) ? $errorMessage : $this->trans('error.occurred');
            }
        }

        return $this->render(
            'application/edenai/text_to_speech.html.twig',
            [
                'form' => $form,
                'inputPrompt' => $inputPrompt,
                'outputResponse' => $outputResponse,
                'output' => $output,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
