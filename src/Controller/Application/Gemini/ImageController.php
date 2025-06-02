<?php

namespace App\Controller\Application\Gemini;

use App\Controller\Application\AbstractApplicationController;
use App\Form\Type\Gemini\ImageGenerateType;
use App\Service\GeminiService;
use Artcustomer\GeminiClient\Utils\ApiEndpoints;
use Artcustomer\GeminiClient\Utils\ApiInfos;
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
                $params = [
                    'contents' => [
                        'parts' => [
                            'text' => $inputPrompt
                        ]
                    ],
                    'generationConfig' => [
                        'responseModalities' => [
                            'TEXT',
                            'IMAGE'
                        ]
                    ]
                ];
            } elseif (str_contains($inputModel, 'imagen')) {
                $model = sprintf('%s:%s', $inputModel, ApiEndpoints::PREDICT);
                $params = [
                    'instances' => [
                        'prompt' => $inputPrompt
                    ],
                    'parameters' => [
                        'sampleCount' => $data[ImageGenerateType::FIELD_NUMBER_OF_IMAGES],
                    ]
                ];
            } else {
                $model = '';
                $params = [];
            }

            $response = $this->geminiService->getApiGateway()->getImageConnector()->generate($model, $params);
            $content = $response->getContent();

            if ($response->getStatusCode() === 200) {
                // TODO
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
}
