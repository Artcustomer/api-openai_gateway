<?php

namespace App\Controller\Application\OpenAI;

use App\Controller\Application\AbstractApplicationController;
use App\Form\Type\OpenAI\AudioCreateCompletionType;
use App\Form\Type\OpenAI\AudioCreateTranscriptionType;
use App\Form\Type\OpenAI\AudioCreateTranslationType;
use App\Form\Type\OpenAI\AudioGenerateAudioType;
use App\Form\Type\OpenAI\AudioSpeakToTextType;
use App\Form\Type\OpenAI\AudioTextToSpeechType;
use App\Service\OpenAIService;
use Artcustomer\OpenAIClient\Enum\AudioFormat;
use Artcustomer\OpenAIClient\Enum\AudioVoice;
use Artcustomer\OpenAIClient\Enum\Model;
use Artcustomer\OpenAIClient\Enum\ResponseFormat;
use Artcustomer\OpenAIClient\Enum\Role;
use Artcustomer\OpenAIClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\Coroutine;

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
     * @Route("/completion/create", name="application_openai_audio_create_completion", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createCompletion(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, AudioCreateCompletionType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(AudioCreateCompletionType::class, null, $options);
        $form->handleRequest($request);

        $audioData = '';
        $audioTranscription = '';
        $userPrompt = '';
        $outputFormat = AudioFormat::MP3;
        $output = null;
        $errors = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $execute = true;

            /** @var ?UploadedFile $file */
            $file = $data[AudioCreateCompletionType::FIELD_FILE];

            if (is_null($file)) {
                $errors[] = $this->trans('error.form.audio.not_defined');
                $execute = false;
            }

            if ($execute) {
                $scope = $this;
                $inputPrompt = $data[AudioSpeakToTextType::FIELD_PROMPT];
                $inputLanguage = $data[AudioSpeakToTextType::FIELD_LANGUAGE];
                $transcriptionParams = [
                    'model' => Model::WHISPER_1,
                    'file' => [
                        'pathName' => $file->getPathname(),
                        'mimeType' => $file->getClientMimeType(),
                        'originalName' => $file->getClientOriginalName(),
                    ],
                    'response_format' => ResponseFormat::JSON,
                    'temperature' => 0
                ];
                $completionParams = [
                    'model' => Model::GPT_4_O_AUDIO_PREVIEW,
                    'modalities' => ['text', 'audio'],
                    'audio' => [
                        'voice' => AudioVoice::ECHO,
                        'format' => $outputFormat
                    ]
                ];

                if (!empty($inputPrompt)) {
                    $transcriptionParams['prompt'] = $inputPrompt;
                }

                if (!empty($inputLanguage)) {
                    $transcriptionParams['language'] = $inputLanguage;
                }

                $promise = Coroutine::of(function () use ($scope, &$errors, &$userPrompt, $transcriptionParams, $completionParams) {
                    $continue = true;
                    $result = null;
                    $response = $scope->openAIService->getApiGateway()->getAudioConnector()->createTranscription($transcriptionParams);
                    $content = $response->getContent();

                    if ($response->getStatusCode() === 200) {
                        $userPrompt = $content->text;
                    } else {
                        $continue = false;
                        $errorMessage = $response->getMessage();

                        if (
                            empty($errorMessage) &&
                            !empty($content)
                        ) {
                            $errorMessage = $content->error->message ?? $scope->trans('error.occurred');
                        }

                        $errors[] = sprintf('An error occurred while performing audio transcription. %s', $errorMessage);
                    }

                    if ($continue) {
                        $completionParams['messages'] = [
                            [
                                'role' => Role::USER,
                                'content' => $userPrompt
                            ]
                        ];
                        $lastResponse = $this->openAIService->getApiGateway()->getChatConnector()->createCompletion($completionParams);
                        $content = $response->getContent();

                        if ($lastResponse->getStatusCode() === 200) {
                            $result = $content->choices[0]->message->audio;
                        } else {
                            $errorMessage = $response->getMessage();

                            if (empty($errorMessage)) {
                                if (!empty($content)) {
                                    $errorMessage = $content->error->message ?? $scope->trans('error.occurred');
                                }
                            }

                            $errors[] = sprintf('An error occurred while performing chat completion. %s', $errorMessage);
                        }
                    }

                    yield $result;
                });

                $output = $promise->wait();
            }
        }

        if (is_object($output)) {
            $mimeType = sprintf('audio/%s', $outputFormat);
            $audioData = sprintf('data:%s;base64, %s', $mimeType, $output->data);
            $audioTranscription = $output->transcript;
        }

        $errorMessage = !empty($errors) ? implode(' | ', $errors) : '';

        return $this->render(
            'application/openai/audio/create_completion.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'errorMessage' => $errorMessage,
                'audioData' => $audioData,
                'audioTranscription' => $audioTranscription,
                'userPrompt' => $userPrompt,
            ]
        );
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
            $file = $data[AudioCreateTranscriptionType::FIELD_FILE];
            $params = [
                'file' => [
                    'pathName' => $file->getPathname(),
                    'mimeType' => $file->getClientMimeType(),
                    'originalName' => $file->getClientOriginalName(),
                ],
                'model' => $data[AudioCreateTranscriptionType::FIELD_MODEL],
                'prompt' => $data[AudioCreateTranscriptionType::FIELD_PROMPT],
                'response_format' => ResponseFormat::JSON,
                'temperature' => $data[AudioCreateTranscriptionType::FIELD_TEMPERATURE],
                'language' => $data[AudioCreateTranscriptionType::FIELD_LANGUAGE]
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
            $file = $data[AudioCreateTranslationType::FIELD_FILE];
            $params = [
                'file' => [
                    'pathName' => $file->getPathname(),
                    'mimeType' => $file->getClientMimeType(),
                    'originalName' => $file->getClientOriginalName(),
                ],
                'model' => $data[AudioCreateTranslationType::FIELD_MODEL],
                'prompt' => $data[AudioCreateTranslationType::FIELD_PROMPT],
                'response_format' => ResponseFormat::JSON,
                'temperature' => $data[AudioCreateTranslationType::FIELD_TEMPERATURE],
                'language' => $data[AudioCreateTranslationType::FIELD_LANGUAGE]
            ];
            $response = $this->openAIService->getApiGateway()->getAudioConnector()->createTranslation($params);
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

        $outputResponse = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $execute = true;

            /** @var ?UploadedFile $file */
            $file = $data[AudioSpeakToTextType::FIELD_FILE];

            if (is_null($file)) {
                $errorMessage = $this->trans('error.form.audio.not_defined');
                $execute = false;
            }

            if ($execute) {
                $inputPrompt = $data[AudioSpeakToTextType::FIELD_PROMPT];
                $inputLanguage = $data[AudioSpeakToTextType::FIELD_LANGUAGE];

                $params = [
                    'model' => Model::WHISPER_1,
                    'file' => [
                        'pathName' => $file->getPathname(),
                        'mimeType' => $file->getClientMimeType(),
                        'originalName' => $file->getClientOriginalName(),
                    ],
                    'response_format' => ResponseFormat::JSON,
                    'temperature' => 0
                ];

                if (!empty($inputPrompt)) {
                    $params['prompt'] = $inputPrompt;
                }

                if (!empty($inputLanguage)) {
                    $params['language'] = $inputLanguage;
                }

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
        }

        return $this->render(
            'application/openai/audio/speak_to_text.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }
}
