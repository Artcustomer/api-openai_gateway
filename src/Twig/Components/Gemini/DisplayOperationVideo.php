<?php

namespace App\Twig\Components\Gemini;

use App\Service\FileService;
use App\Service\GeminiService;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class DisplayOperationVideo
{

    use DefaultActionTrait;

    protected GeminiService $geminiService;
    protected FileService $fileService;

    #[LiveProp]
    public ?string $operationName = null;

    #[LiveProp]
    public array $responseData = [];

    #[LiveProp]
    public bool $isLoading = false;

    #[LiveProp]
    public bool $isOperationDone = false;

    #[LiveProp]
    public bool $isOperationFailed = false;

    #[LiveProp (writable: true)]
    public bool $isDone = false;

    /**
     * Constructor
     *
     * @param GeminiService $geminiService
     * @param FileService $fileService
     */
    public function __construct(GeminiService $geminiService, FileService $fileService)
    {
        $this->geminiService = $geminiService;
        $this->fileService = $fileService;
    }

    #[LiveAction]
    public function load()
    {
        $data = [];
        $messages = [];

        $this->responseData = [
            'done' => false,
            'success' => false,
            'data' => [],
            'messages' => []
        ];

        if (
            !is_null($this->operationName) &&
            !$this->isLoading
        ) {
            $this->isLoading = true;

            $response = $this->geminiService->getApiGateway()->getVideoConnector()->getOperation($this->operationName);
            $content = $response->getContent();
            $videos = [];
            $saveFiles = false;

            if ($response->getStatusCode() === 200) {
                if (
                    isset($content->done) &&
                    $content->done === true
                ) {
                    $samples = $content->response->generateVideoResponse->generatedSamples ?? [];

                    if (!empty($samples)) {
                        foreach ($samples as $sample) {
                            $videoUri = $sample->video->uri ?? '';
                            $videoItem = [
                                'uri' => $videoUri,
                                'url' => '',
                                'status' => false
                            ];

                            if (!empty($videoUri)) {
                                $downloadResponse = $this->geminiService->getApiGateway()->getVideoConnector()->download($videoUri);

                                if ($downloadResponse->getStatusCode() === 200) {
                                    $content = $downloadResponse->getContent();
                                    $format = 'mp4';
                                    $url = '';

                                    if ($saveFiles) {
                                        $fileName = sprintf('%s.%s', uniqid(), $format);
                                        $videoFile = $this->fileService->uploadContent($fileName, $content, false);

                                        if (!empty($videoFile)) {
                                            $url = sprintf('/%s', $videoFile);
                                        }
                                    } else {
                                        $url = sprintf(
                                            'data:video/%s;base64,%s',
                                            $format,
                                            base64_encode($content)
                                        );
                                    }

                                    if (!empty($url)) {
                                        $videoItem['url'] = $url;
                                        $videoItem['status'] = true;
                                    }
                                }
                            }

                            $videos[] = $videoItem;
                        }
                    }

                    $this->isOperationDone = true;
                }
            } else {
                $errorMessage = $response->getMessage();

                if (empty($errorMessage)) {
                    if (!empty($content)) {
                        $errorMessage = $content->error->message ?? '';
                    }
                }

                $errorMessage = !empty($errorMessage) ? $errorMessage : sprintf('API Error: %s', $response->getStatusCode());
                $messages = [$errorMessage];

                $this->isOperationFailed = true;
            }

            $data = ['videos' => $videos];
        }

        $this->isDone = $this->isOperationDone || $this->isOperationFailed;

        $this->responseData['done'] = $this->isDone;
        $this->responseData['success'] = !$this->isOperationFailed;
        $this->responseData['messages'] = $messages;
        $this->responseData['data'] = $data;

        $this->isLoading = false;
    }

    #[LiveAction]
    public function download(string $uri)
    {
        $content = '';
        $downloadResponse = $this->geminiService->getApiGateway()->getVideoConnector()->download($uri);

        if ($downloadResponse->getStatusCode() === 200) {
            $content = $downloadResponse->getContent();
        }

        return $content;
    }
}