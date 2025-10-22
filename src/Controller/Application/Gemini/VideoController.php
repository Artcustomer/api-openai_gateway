<?php

namespace App\Controller\Application\Gemini;

use App\Controller\Application\AbstractApplicationController;
use App\Form\Type\Gemini\VideoGenerateType;
use App\Form\Type\Gemini\VideoRetrieveType;
use App\Form\Type\Gemini\VideoSummarizeYoutubeType;
use App\Service\GeminiService;
use Artcustomer\GeminiClient\Enum\Model;
use Artcustomer\GeminiClient\Utils\ApiInfos;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gemini/video")
 *
 * @author David
 */
class VideoController extends AbstractApplicationController
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
     * @Route("/generate", name="application_gemini_video_generate", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function generate(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, VideoGenerateType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(VideoGenerateType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $operationName = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->isValid()) {
                $data = $form->getData();
                $inputPrompt = $data[VideoGenerateType::FIELD_PROMPT];
                $inputModel = $data[VideoGenerateType::FIELD_MODEL];
                $response = $this->geminiService->getApiGateway()->getVideoConnector()->generate(
                    $inputModel,
                    $this->buildGenerateParameters($data)
                );
                $content = $response->getContent();

                if ($response->getStatusCode() === 200) {
                    $operationName = $content->name;
                } else {
                    $errorMessage = $response->getMessage();

                    if (empty($errorMessage)) {
                        if (!empty($content)) {
                            $errorMessage = $content->error->message ?? '';
                        }
                    }

                    $errorMessage = !empty($errorMessage) ? $errorMessage : $this->trans('error.occurred');
                }
            } else {
                $errorMessage = $this->trans('error.form.not_valid');
            }
        }

        return $this->render(
            'application/gemini/video/generate.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'operationName' => $operationName,
                'inputPrompt' => $inputPrompt,
                'errorMessage' => $errorMessage,
            ]
        );
    }

    /**
     * @Route("/retrieve", name="application_gemini_video_retrieve", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function retrieve(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, VideoRetrieveType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(VideoRetrieveType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $operationName = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $operationName = $data[VideoRetrieveType::FIELD_OPERATION_NAME];
        }

        return $this->render(
            'application/gemini/video/retrieve.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'operationName' => $operationName,
                'inputPrompt' => $inputPrompt,
                'errorMessage' => $errorMessage,
            ]
        );
    }

    /**
     * @Route("/summarize-youtube", name="application_gemini_video_summarize_youtube", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function summarizeYoutube(Request $request): Response
    {
        $formData = $this->cleanQueryParameters($request, VideoSummarizeYoutubeType::FIELD_NAMES);
        $options = ['data' => $formData];

        $form = $this->createForm(VideoSummarizeYoutubeType::class, null, $options);
        $form->handleRequest($request);

        $inputPrompt = '';
        $outputResponse = '';
        $errorMessage = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $inputPrompt = $data[VideoSummarizeYoutubeType::FIELD_PROMPT];
            $params = [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $inputPrompt
                            ],
                            [
                                'file_data' => [
                                    'file_uri' => $data[VideoSummarizeYoutubeType::FIELD_YOUTUBE_URL]
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $response = $this->geminiService->getApiGateway()->getTextConnector()->generate(Model::GEMINI_2_5_FLASH, $params);
            $content = $response->getContent();
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                $outputResponse = $content->candidates[0]->content->parts[0]->text;
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
            'application/gemini/video/summarize_youtube.html.twig',
            [
                'gatewayName' => ApiInfos::API_NAME,
                'form' => $form,
                'inputPrompt' => $inputPrompt,
                'outputResponse' => $outputResponse,
                'errorMessage' => $errorMessage
            ]
        );
    }

    /**
     * @param array $data
     * @return array
     */
    private function buildGenerateParameters(array $data): array
    {
        $inputPrompt = $data[VideoGenerateType::FIELD_PROMPT];
        $inputModel = $data[VideoGenerateType::FIELD_MODEL];
        $inputReferenceImages = $data[VideoGenerateType::FIELD_REFERENCE_IMAGES];

        /** @var ?UploadedFile $imageFile */
        $imageFile = $data[VideoGenerateType::FIELD_IMAGE];

        /** @var ?UploadedFile $lastFrameFile */
        $lastFrameFile = $data[VideoGenerateType::FIELD_LAST_FRAME];

        $instances = [
            'prompt' => $inputPrompt
        ];
        $parameters = [
            'aspectRatio' => $data[VideoGenerateType::FIELD_ASPECT_RATIO],
            'negativePrompt' => $data[VideoGenerateType::FIELD_NEGATIVE_PROMPT],
            'personGeneration' => $data[VideoGenerateType::FIELD_PERSON_GENERATION],
        ];

        if (!is_null($imageFile)) {
            $instances['image'] = [
                'bytesBase64Encoded' => base64_encode($imageFile->getContent()),
                'mimeType' => $imageFile->getMimeType(),
            ];
        }

        if (!is_null($lastFrameFile)) {
            $instances['lastFrame'] = [
                'bytesBase64Encoded' => base64_encode($lastFrameFile->getContent()),
                'mimeType' => $lastFrameFile->getMimeType(),
            ];
        }

        if (!empty($inputReferenceImages)) {
            $referenceImages = [];

            /** @var ?UploadedFile $inputReferenceImage */
            foreach ($inputReferenceImages as $inputReferenceImage) {
                if (!is_null($inputReferenceImage)) {
                    $referenceImages[]['image'] = [
                        'bytesBase64Encoded' => base64_encode($inputReferenceImage->getContent()),
                        'mimeType' => $inputReferenceImage->getMimeType(),
                    ];
                }
            }

            if (!empty($referenceImages)) {
                $instances['referenceImages'] = $referenceImages;
            }
        }

        if (str_contains($inputModel, '3.')) {
            // For VEO 3.^
            switch (true) {
                case str_contains($inputModel, '1'):
                    // For VEO 3.1
                    break;
                case str_contains($inputModel, '0'):
                default:
                    // For VEO 3.0
                    break;
            }

            $parameters['resolution'] = $data[VideoGenerateType::FIELD_RESOLUTION];
        }

        return [
            'instances' => $instances,
            'parameters' => $parameters
        ];
    }
}
