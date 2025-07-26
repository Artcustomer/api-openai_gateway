<?php

namespace App\Controller\Application\Gemini;

use App\Controller\Application\AbstractApplicationController;
use App\Form\Type\Gemini\VideoGenerateType;
use App\Service\GeminiService;
use Artcustomer\GeminiClient\Utils\ApiInfos;
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
            $data = $form->getData();
            $inputPrompt = $data[VideoGenerateType::FIELD_PROMPT];
            $inputModel = $data[VideoGenerateType::FIELD_MODEL];
            $params = [
                'instances' => [
                    'prompt' => $inputPrompt
                ],
                'parameters' => [
                    'aspectRatio' => $data[VideoGenerateType::FIELD_ASPECT_RATIO],
                    'negativePrompt' => $data[VideoGenerateType::FIELD_NEGATIVE_PROMPT],
                    'personGeneration' => $data[VideoGenerateType::FIELD_PERSON_GENERATION]
                ]
            ];

            $response = $this->geminiService->getApiGateway()->getVideoConnector()->generate($inputModel, $params);
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
}
