<?php

namespace App\Controller\Application;

use App\Form\Type\AudioCreateTranscriptionType;
use App\Form\Type\AudioCreateTranslationType;
use App\Form\Type\AudioSpeakToTextType;
use App\Service\OpenAIService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/audio")
 *
 * @author David
 */
class AudioController extends AbstractApplicationController
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
     * @throws \ReflectionException
     */
    public function createTranscription(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, AudioCreateTranscriptionType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(AudioCreateTranscriptionType::class, null, $options);
        $form->handleRequest($request);

        $outputResponse = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            /** @var UploadedFile $file */
            $file = $data['file'];
            $params = [
                'file' => [
                    'pathName' => $file->getPathname(),
                    'mimeType' => $file->getClientMimeType(),
                    'originalName' => $file->getClientOriginalName(),
                ],
                'model' => $data['model'],
                'prompt' => $data['prompt'],
                'response_format' => 'json',
                'temperature' => $data['temperature'],
                'language' => $data['language']
            ];
            $response = $this->openAIService->getApiGateway()->getAudioConnector()->createTranscription($params);

            if ($response->getStatusCode() === 200) {
                $content = json_decode((string)$response->getContent());
                $outputResponse = $content->text;
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
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }

    /**
     * @Route("/translation/create", name="application_audio_create_translation", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function createTranslation(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, AudioCreateTranslationType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(AudioCreateTranslationType::class, null, $options);
        $form->handleRequest($request);

        $outputResponse = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            /** @var UploadedFile $file */
            $file = $data['file'];
            $params = [
                'file' => [
                    'pathName' => $file->getPathname(),
                    'mimeType' => $file->getClientMimeType(),
                    'originalName' => $file->getClientOriginalName(),
                ],
                'model' => $data['model'],
                'prompt' => $data['prompt'],
                'response_format' => 'json',
                'temperature' => $data['temperature'],
                'language' => $data['language']
            ];
            $response = $this->openAIService->getApiGateway()->getAudioConnector()->createTranscription($params);

            if ($response->getStatusCode() === 200) {
                $content = json_decode((string)$response->getContent());
                $outputResponse = $content->text;
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
            'application/audio/create_translation.html.twig',
            [
                'form' => $form,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }

    /**
     * @Route("/speak-to-text", name="application_audio_speak_to_text", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function speakToText(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, AudioSpeakToTextType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(AudioSpeakToTextType::class, null, $options);
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
            'application/audio/speak_to_text.html.twig',
            [
                'form' => $form,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
