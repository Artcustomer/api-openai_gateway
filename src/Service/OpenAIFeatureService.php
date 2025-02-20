<?php

namespace App\Service;

use Artcustomer\OpenAIClient\Enum\AudioFormat;
use Artcustomer\OpenAIClient\Enum\AudioVoice;
use Artcustomer\OpenAIClient\Enum\Model;
use Artcustomer\OpenAIClient\Enum\ResponseFormat;
use Artcustomer\OpenAIClient\Enum\Role;
use GuzzleHttp\Promise\Coroutine;
use Symfony\Component\HttpFoundation\File\File;

class OpenAIFeatureService
{

    private OpenAIService $openAIService;

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
     * @param File $file
     * @param array $transcriptionParams
     * @param array $completionParams
     * @param $outputFormat
     * @return false[]
     */
    public function completionAudioInAudioOut(File $file, array $transcriptionParams = [], array $completionParams = [], $outputFormat = AudioFormat::MP3): array
    {
        $output = [
            'success' => false
        ];
        $scope = $this;
        $userPrompt = '';
        $errors = [];

        $transcriptionParams = array_merge(
            [
                'model' => Model::WHISPER_1,
                'file' => [
                    'pathName' => $file->getPathname(),
                    'mimeType' => $file->getClientMimeType(),
                    'originalName' => $file->getClientOriginalName(),
                ],
                'response_format' => ResponseFormat::JSON,
                'temperature' => 0
            ],
            $transcriptionParams
        );
        $completionParams = array_merge(
            [
                'model' => Model::GPT_4_O_AUDIO_PREVIEW,
                'modalities' => ['text', 'audio'],
                'audio' => [
                    'voice' => AudioVoice::SAGE,
                    'format' => $outputFormat
                ]
            ],
            $completionParams
        );

        $promise = Coroutine::of(function () use ($scope, &$errors, &$userPrompt, $transcriptionParams, $completionParams) {
            $continue = true;
            $result = null;
            $transcriptionResponse = $scope->openAIService->getApiGateway()->getAudioConnector()->createTranscription($transcriptionParams);
            $content = $transcriptionResponse->getContent();
            $userPrompt = '';

            if ($transcriptionResponse->getStatusCode() === 200) {
                $userPrompt = $content->text;
            } else {
                $continue = false;
                $errorMessage = $transcriptionResponse->getMessage();

                if (
                    empty($errorMessage) &&
                    !empty($content)
                ) {
                    $errorMessage = $content->error->message ?? '';
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
                $completionResponse = $this->openAIService->getApiGateway()->getChatConnector()->createCompletion($completionParams);
                $content = $completionResponse->getContent();

                if ($completionResponse->getStatusCode() === 200) {
                    $result = $content->choices[0]->message->audio;
                } else {
                    $errorMessage = $completionResponse->getMessage();

                    if (empty($errorMessage)) {
                        if (!empty($content)) {
                            $errorMessage = $content->error->message ?? '';
                        }
                    }

                    $errors[] = sprintf('An error occurred while performing chat completion. %s', $errorMessage);
                }
            }

            yield $result;
        });

        $response = $promise->wait();

        if (is_object($response)) {
            $mimeType = sprintf('audio/%s', $outputFormat);
            $audioData = sprintf('data:%s;base64, %s', $mimeType, $response->data);
            $audioTranscription = $response->transcript;

            $output['success'] = true;
            $output['data'] = [
                'userPrompt' => $userPrompt,
                'audio' => $audioData,
                'transcript' => $audioTranscription,
            ];
        } else {
            $output['errors'] = $errors;
        }

        return $output;
    }
}
