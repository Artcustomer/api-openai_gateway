<?php

namespace App\Controller\Application\OpenAI;

use App\Controller\Application\AbstractApplicationController;
use App\Form\Type\OpenAI\AudioCreateTranscriptionType;
use App\Form\Type\OpenAI\AudioCreateTranslationType;
use App\Form\Type\OpenAI\AudioGenerateAudioType;
use App\Form\Type\OpenAI\AudioSpeakToTextType;
use App\Form\Type\OpenAI\AudioTextToSpeechType;
use App\Service\OpenAIService;
use Artcustomer\OpenAIClient\Enum\Role;
use Artcustomer\OpenAIClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/openai/audio")
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
     * @Route("/transcription/create", name="application_openai_audio_create_transcription", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
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
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {
                $outputResponse = $content->text;
            } else {
                $errorMessage = $response->getMessage();

                if (empty($errorMessage)) {
                    if (!empty($content)) {
                        $errorMessage = $content->error->message ?? '';
                    }
                }

                $errorMessage = !empty($errorMessage) ? $errorMessage : $this->trans('error.occurred');
            }
        }

        return $this->render(
            'application/openai/audio/create_transcription.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }

    /**
     * @Route("/translation/create", name="application_openai_audio_create_translation", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
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
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {
                $outputResponse = $content->text;
            } else {
                $errorMessage = $response->getMessage();

                if (empty($errorMessage)) {
                    if (!empty($content)) {
                        $errorMessage = $content->error->message ?? '';
                    }
                }

                $errorMessage = !empty($errorMessage) ? $errorMessage : $this->trans('error.occurred');
            }
        }

        return $this->render(
            'application/openai/audio/create_translation.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }

    /**
     * @Route("/generate-audio", name="application_openai_audio_generate_audio", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function generateAudio(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, AudioGenerateAudioType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(AudioGenerateAudioType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $outputResponse = '';
        $output = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data[AudioGenerateAudioType::FIELD_TEXT];
            $outputformat = $data[AudioGenerateAudioType::FIELD_RESPONSE_FORMAT];
            $params = [
                'model' => $data[AudioGenerateAudioType::FIELD_MODEL],
                'modalities' => [
                    'text',
                    'audio'
                ],
                'audio' => [
                    'voice' => $data[AudioGenerateAudioType::FIELD_VOICE],
                    'format' => $outputformat
                ],
                'messages' => [
                    [
                        'role' => Role::USER,
                        'content' => $inputPrompt
                    ]
                ]
            ];
            $response = $this->openAIService->getApiGateway()->getChatConnector()->createCompletion($params);
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {
                $audio = $content->choices[0]->message->audio;
                $mimeType = sprintf('audio/%s', $outputformat);
                $output = sprintf('data:%s;base64, %s', $mimeType, $audio->data);
                $outputResponse = $audio->transcript;
            } else {
                $errorMessage = $response->getMessage();

                if (empty($errorMessage)) {
                    if (!empty($content)) {
                        $errorMessage = $content->error->message ?? '';
                    }
                }

                $errorMessage = !empty($errorMessage) ? $errorMessage : $this->trans('error.occurred');
            }
        }

        return $this->render(
            'application/openai/audio/generate_audio.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'inputPrompt' => $inputPrompt,
                'outputResponse' => $outputResponse,
                'output' => $output,
                'errorMessage' => $errorMessage
            ]
        );
    }

    /**
     * @Route("/text-to-speech", name="application_openai_audio_text_to_speech", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function textToSpeech(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, AudioTextToSpeechType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(AudioTextToSpeechType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $outputResponse = '';
        $output = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data[AudioTextToSpeechType::FIELD_INPUT];
            $responseFormat = $data[AudioTextToSpeechType::FIELD_RESPONSE_FORMAT];
            $params = [
                'model' => $data[AudioTextToSpeechType::FIELD_MODEL],
                'voice' => $data[AudioTextToSpeechType::FIELD_VOICE],
                'response_format' => $responseFormat,
                'speed' => $data[AudioTextToSpeechType::FIELD_SPEED],
                'input' => $inputPrompt
            ];
            $response = $this->openAIService->getApiGateway()->getAudioConnector()->createSpeech($params);
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {
                $mimeType = sprintf('audio/%s', $responseFormat);
                $output = sprintf('data:%s;base64, %s', $mimeType, base64_encode($content));
                $outputResponse = $inputPrompt;
            } else {
                $errorMessage = $response->getMessage();

                if (empty($errorMessage)) {
                    if (!empty($content)) {
                        $errorMessage = $content->error->message ?? '';
                    }
                }

                $errorMessage = !empty($errorMessage) ? $errorMessage : $this->trans('error.occurred');
            }
        }

        return $this->render(
            'application/openai/audio/text_to_speech.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'inputPrompt' => $inputPrompt,
                'outputResponse' => $outputResponse,
                'output' => $output,
                'errorMessage' => $errorMessage
            ]
        );
    }

    /**
     * @Route("/speak-to-text", name="application_openai_audio_speak_to_text", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
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
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {

            } else {
                $errorMessage = $response->getMessage();

                if (empty($errorMessage)) {
                    if (!empty($content)) {
                        $errorMessage = $content->error->message ?? '';
                    }
                }

                $errorMessage = !empty($errorMessage) ? $errorMessage : $this->trans('error.occurred');
            }
        }

        return $this->render(
            'application/openai/audio/speak_to_text.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
