<?php

namespace App\Controller\Application\Gemini;

use App\Controller\Application\AbstractApplicationController;
use App\Form\Type\Gemini\ImageGenerateType;
use App\Form\Type\OpenAI\ImageAnalyzeCompletionType;
use App\Service\GeminiService;
use App\Utils\FileUtils;
use Artcustomer\GeminiClient\Enum\ResponseModalities;
use Artcustomer\GeminiClient\Utils\ApiEndpoints;
use Artcustomer\GeminiClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gemini/image")
 *
 * @author David
 */
class ImageController extends AbstractApplicationController
{

    protected GeminiService $geminiService;

    /**
     * Constructor
     *
     * @param GeminiService $geminiService
     */
    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * @Route("/generate", name="application_gemini_image_generate", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function generate(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, ImageGenerateType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(ImageGenerateType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $imageUrls = [];
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data[ImageGenerateType::FIELD_PROMPT];
            $inputModel = $data[ImageGenerateType::FIELD_MODEL];

            if (str_contains($inputModel, 'gemini')) {
                $model = sprintf('%s:%s', $inputModel, ApiEndpoints::GENERATE_CONTENT);
                $params = $this->prepareGeminiRequest($data);
            } elseif (str_contains($inputModel, 'imagen')) {
                $model = sprintf('%s:%s', $inputModel, ApiEndpoints::PREDICT);
                $params = $this->prepareImagenRequest($data);
            } else {
                $model = '';
                $params = [];
            }

            $response = $this->geminiService->getApiGateway()->getImageConnector()->generate($model, $params);
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {
                if (str_contains($inputModel, 'gemini')) {
                    $items = $this->parseGeminiResponse($content);
                } elseif (str_contains($inputModel, 'imagen')) {
                    $items = $this->parseImagenResponse($content);
                } else {
                    $items = [];
                }

                if (!empty($items)) {
                    foreach ($items as $item) {
                        $url = '';

                        if (
                            isset($item['base64']) &&
                            isset($item['mimeType'])
                        ) {
                            $imageFormat = FileUtils::mimeTypeToExtension($item['mimeType']);

                            $url = sprintf(
                                'data:image/%s;base64, %s',
                                $imageFormat,
                                $item['base64']
                            );
                        }

                        if (!empty($url)) {
                            $imageUrls[] = $url;
                        }
                    }
                } else {
                    $errorMessage = 'No image data found on response';
                }
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
            'application/gemini/image/generate.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'imageUrls' => $imageUrls,
                'inputPrompt' => $inputPrompt,
                'errorMessage' => $errorMessage,
            ]
        );
    }

    /**
     * @param array $data
     * @return \array[][]
     */
    private function prepareGeminiRequest(array $data): array
    {
        /** @var ?UploadedFile $imageFile */
        $imageFile = $data[ImageAnalyzeCompletionType::FIELD_IMAGE];
        $parts = [];

        if (!is_null($imageFile)) {
            $parts[] = ['text' => $data[ImageGenerateType::FIELD_PROMPT]];
            $parts[] = ['inline_data' => [
                'mime_type' => $imageFile->getMimeType(),
                'data' => base64_encode($imageFile->getContent())
            ]];
        } else {
            $parts['text'] = $data[ImageGenerateType::FIELD_PROMPT];
        }

        return [
            'contents' => [
                'parts' => $parts
            ],
            'generationConfig' => [
                'responseModalities' => [
                    ResponseModalities::TEXT,
                    ResponseModalities::IMAGE
                ]
            ]
        ];
    }

    /**
     * @param array $data
     * @return array[]
     */
    private function prepareImagenRequest(array $data): array
    {
        return [
            'instances' => [
                'prompt' => $data[ImageGenerateType::FIELD_PROMPT]
            ],
            'parameters' => [
                'sampleCount' => $data[ImageGenerateType::FIELD_NUMBER_OF_IMAGES],
                'aspectRatio' => $data[ImageGenerateType::FIELD_ASPECT_RATIO],
                'personGeneration ' => $data[ImageGenerateType::FIELD_PERSON_GENERATION]
            ]
        ];
    }

    /**
     * @param $content
     * @return array
     */
    private function parseGeminiResponse($content): array
    {
        $items = [];
        $parts = $content->candidates[0]->content->parts ?? [];

        if (!empty($parts)) {
            array_walk(
                $parts,
                function ($part) use (&$items) {
                    if (isset($part->inlineData)) {
                        $inlineData = $part->inlineData;

                        if (
                            isset($inlineData->data) &&
                            isset($inlineData->mimeType)
                        ) {
                            $items[] = [
                                'mimeType' => $inlineData->mimeType,
                                'base64' => $inlineData->data
                            ];
                        }
                    }
                }
            );
        }

        return $items;
    }

    /**
     * @param $content
     * @return array
     */
    private function parseImagenResponse($content): array
    {
        $items = [];
        $predictions = $content->predictions ?? [];

        if (!empty($predictions)) {
            array_walk(
                $predictions,
                function ($prediction) use (&$items) {
                    if (
                        isset($prediction->bytesBase64Encoded) &&
                        isset($prediction->mimeType)
                    ) {
                        $items[] = [
                            'mimeType' => $prediction->mimeType,
                            'base64' => $prediction->bytesBase64Encoded
                        ];
                    }
                }
            );
        }

        return $items;
    }
}
