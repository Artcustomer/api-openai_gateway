<?php

namespace App\Controller\Application;

use App\Form\Type\AudioCreateTranscriptionType;
use App\Service\OpenAIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/audio")
 *
 * @author David
 */
class AudioController extends AbstractController
{

    protected OpenAIService $openAIService;

    /**
     * Constructor
     *
     * @param OpenAIService $openAIService
     */
    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    /**
     * @Route("/transcription/create", name="application_audio_create_transcription", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function createTranscription(Request $request): Response
    {
        $form = $this->createForm(AudioCreateTranscriptionType::class);
        $form->handleRequest($request);

        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $params = [
                'file' => '',
                'model' => '',
                'prompt' => '',
                'response_format' => 'json',
                'temperature' => 0,
                'language' => ''
            ];
            $response = $this->openAIService->getApiGateway()->getAudioConnector()->createTranscription($params);

            if ($response->getStatusCode() === 200) {
                $content = json_decode((string)$response->getContent());
            } else {
                $errorMessage = $response->getMessage();

                if (empty($errorMessage)) {
                    $content = $response->getContent();

                    if (!empty($content)) {
                        $content = json_decode((string)$content);
                        $errorMessage = $content->error->message ?? '';
                    }
                }

                $errorMessage = !empty($errorMessage) ? $errorMessage : 'An error occurred';
            }
        }

        return $this->render(
            'application/audio/create_transcription.html.twig',
            [
                'form' => $form,
                'error' => $errorMessage
            ]
        );
    }
}
